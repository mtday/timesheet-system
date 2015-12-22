<?php

class PayPeriodDateHelper
{
	/**
	 * Retrieve just the year of the pay period.
	 */
    public static function getYear($payPeriod)
    {
		// Make sure the pay period is valid.
		if (!isset($payPeriod) || !isset($payPeriod->start)
				|| !isset($payPeriod->end))
			return "Unknown";

		// Convert the dates into times.
		$startTime = strtotime($payPeriod->start);
		$endTime   = strtotime($payPeriod->end);

		// Get the years.
		$startYear = date('Y', $startTime); // 2008, 2009, etc.
		$endYear   = date('Y', $endTime);

		// When the years are the same, return the start one.
		if ($startYear == $endYear)
			return $startYear;

		// The years are different, so return them as a range.
		return $startYear . " - " . $endYear;
    }

	/**
	 * Retrieve a summary of the pay period date range.
	 */
    public static function getDateRange($payPeriod)
    {
		// Make sure the pay period is valid.
		if (!isset($payPeriod) || !isset($payPeriod->start)
				|| !isset($payPeriod->end))
			return "Unknown";

		// Convert the dates into times.
		$startTime = strtotime($payPeriod->start);
		$endTime   = strtotime($payPeriod->end);

		// Create the years.
		$startYear = date('Y', $startTime); // 2008, 2009, etc.
		$endYear   = date('Y', $endTime);

		// Create the months.
		$startMonth = date('M', $startTime); // Jan to Dec.
		$endMonth   = date('M', $endTime);

		// Create the days.
		$startDay = strftime("%d", $startTime); // 01 to 31
		$endDay   = strftime("%d", $endTime);

		// Cut off the preceding 0 on the days.
		if (strpos($startDay, "0") === 0) $startDay = substr($startDay, 1);
		if (strpos($endDay,   "0") === 0) $endDay   = substr($endDay,   1);

		// Combine and return the date range.
		return $startMonth . '. ' . $startDay . ', ' . $startYear . ' - ' .
			   $endMonth   . '. ' . $endDay   . ', ' . $endYear;
    }
}

