<?php

class HolidayDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'holidays';

	/**
	 * Used to retrieve all the available holidays.
	 *
	 * @return Returns all the holidays from the database.
	 */
	public function getAll()
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name);

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj)
				$this->postProcess($obj);

		// This will hold the identified holidays.
		$holidays = array();

		// Get the current year.
		$year = date('Y');

		// Iterate over all the holidays.
		foreach ($objs as $holiday) {
			// Get the holidays.
			$day = $this->getHolidayForYear($holiday, $year);

			// Make sure the holiday is set.
			if (isset($holiday)) {
				// Set the day in the holiday object.
				$holiday->day = date('Y-m-d', $day);
				$holidays[] = $holiday;
			}
		}

		// Return the identified holidays.
		return $holidays;
	}

	/**
	 * Used to retrieve all the holidays within a pay period.
	 *
	 * @param payPeriod The pay period for which holidays are to be retrieved.
	 *
	 * @return Returns all the identified pay periods.
	 */
	public function getForPayPeriod($payPeriod)
	{
		// Make sure the provided id is valid.
		if (!isset($payPeriod))
			return array();

		// This will hold the identified holidays.
		$holidays = array();

		// Get all the holidays.
		$all = $this->getAll();

		// Get the start and end dates in the pay period.
		$start = strtotime($payPeriod->start);
		$end = strtotime($payPeriod->end);

		// Expand the pay period date range by 2 days on both ends.
		$start -= 2 * 24 * 60 * 60;
		$end += 2 * 24 * 60 * 60;

		// Iterate over all the holidays.
		foreach ($all as $holiday) {
			// Get the holidays.
			$hdayA = $this->getHolidayForYear($holiday, date('Y', $start));
			$hdayB = $this->getHolidayForYear($holiday, date('Y', $end));

			// Check to see if the holidays fall within the pay period.
			if ($hdayA >= $start && $hdayA <= $end) {
				// Make a shallow clone of the holiday.
				$hol = clone $holiday;
				$hol->day = date('Y-m-d', $hdayA);
				$holidays[] = $hol;
			}
			if ($hdayB != $hdayA && $hdayB >= $start && $hdayB <= $end) {
				// Make a shallow clone of the holiday.
				$hol = clone $holiday;
				$hol->day = date('Y-m-d', $hdayB);
				$holidays[] = $hol;
			}
		}

		// Return the identified holidays.
		return $holidays;
	}

	/**
	 * Get the holiday date for the provided holiday and year.
	 */
	private function getHolidayForYear($holiday, $year)
	{
		// Get the holiday configuration pattern.
		$pattern = $holiday->config;

		// Define the pattern parts.
		$OCCURRENCES = "(1st|2nd|3rd|4th|5th|Last)";
		$DAYS = "(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday)";
		$MONTHS = "(January|February|March|April|May|June|July|August|" .
			"September|October|November|December)";
		$DAY_OCCS = "(1st|2nd|3rd|4th|5th|6th|7th|8th|9th|10th|11th|12th|" .
			"13th|14th|15th|16th|17th|18th|19th|20th|21st|22nd|23rd|24th|" .
			"25th|26th|27th|28th|29th|30th|31st)";

		// Define the patterns.
		//   "1st Monday in January"
		$WEEK_OCCURRENCE_PATTERN = "/$OCCURRENCES $DAYS in $MONTHS/";
		//   "January 1st Observance"
		$DATE_PATTERN = "/$MONTHS $DAY_OCCS( Observance)?/";

		// Check for patterns following this example:
		if (preg_match($WEEK_OCCURRENCE_PATTERN, $pattern, $matches)) {
			// Get the week number for the provided holiday.
			$week = $this->convertToInt($matches[1]);

			// Get the full day for the provided holiday.
			$day = $matches[2]; // e.g., "Thursday"

			// Get the month number for the provided holiday.
			$month = $this->convertMonth($matches[3]);

			// Which week are we looking for?
			if ($week < 0) {
				// Looking for "Last".

				// Get the initial date.
				$holiday = strtotime("$year-" . ($month + 1) . "-01 -0000");
				while ($day != gmdate('l', $holiday))
					$holiday -= 60 * 60 * 24;
			} else {
				// Get the initial date.
				$holiday = strtotime("$year-$month-01 -0000");
				while ($day != gmdate('l', $holiday))
					$holiday += 60 * 60 * 24;
				for ($i = 1; $i < $week; $i++)
					$holiday += 7 * 60 * 60 * 24;
			}

			// Return the identified time stamp in local time.
			return strtotime(gmdate('Y-m-d', $holiday));
		} else if (preg_match($DATE_PATTERN, $pattern, $matches)) {
			// Get the month number for the provided holiday.
			$month = $this->convertMonth($matches[1]);

			// Get the day number for the provided holiday.
			$day = $this->convertToInt($matches[2]);

			// Get the initial holiday value.
			$holiday = strtotime("$year-$month-$day -0000");

			// Offset for the observance.
			if ($matches[3] == ' Observance') {
				// Move backwards so that Friday is the holiday.
				if (gmdate('l', $holiday) == 'Saturday')
					$holiday -= 60 * 60 * 24;
				// Move forwards so that Monday is the holiday.
				if (gmdate('l', $holiday) == 'Sunday')
					$holiday += 60 * 60 * 24;
			}

			// Return the identified time stamp in local time.
			return strtotime(gmdate('Y-m-d', $holiday));
		}

		// No date was found.
		return null;
	}

	/**
	 * Converts "1st" to 1, "2nd" to 2, "30th" to 30, etc. Also converts
	 * "Last" to -1.
	 */
	private function convertToInt($value)
	{
		// Make sure value is set.
		if (!isset($value))
			return 0;

		// Handle the "Last" case.
		if ("Last" == $value)
			return -1;

		// Get the first to characters.
		$first = $value[0];
		$second = $value[1];

		// This will hold the return value.
		$num = "";

		// Add the first character if it is numeric.
		if (is_numeric($first))
			$num .= $first;

		// Add the second character if it is numeric.
		if (is_numeric($second))
			$num .= $second;

		// Return the identified number.
		return (int) $num;
	}

	/**
	 * Convert a month (e.g., "January") into its numeric value (e.g., '01').
	 */
	private function convertMonth($month)
	{
		if ("January" == $month)        return '01';
		else if ("February" == $month)  return '02';
		else if ("March" == $month)     return '03';
		else if ("April" == $month)     return '04';
		else if ("May" == $month)       return '05';
		else if ("June" == $month)      return '06';
		else if ("July" == $month)      return '07';
		else if ("August" == $month)    return '08';
		else if ("September" == $month) return '09';
		else if ("October" == $month)   return '10';
		else if ("November" == $month)  return '11';
		else if ("December" == $month)  return '12';
		return -1;
	}
}

