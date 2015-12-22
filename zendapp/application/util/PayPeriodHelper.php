<?php

class PayPeriodHelper
{
	/**
	 * Get the pay period following the one provided.
	 */
    public static function getNext($payPeriod)
    {
		// Make sure we have something to work with.
		if (! isset($payPeriod))
			return $payPeriod;

		// Create the new pay period.
		$newPayPeriod = new stdClass();

		// Copy over the type.
		$newPayPeriod->type = $payPeriod->type;

		// Move the start and end dates based on the type.
		if ($payPeriod->type == "weekly") {
			// Add 7 days to the start and end.
			$newPayPeriod->start = gmdate('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->start . " -0000") +
					(7 * 24 * 60 * 60))));
			$newPayPeriod->end = gmdate('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->end . " -0000") +
					(7 * 24 * 60 * 60))));
		} else if ($payPeriod->type == "biweekly") {
			// Add 14 days to the start and end.
			$newPayPeriod->start = gmdate('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->start . " -0000") +
					(14 * 24 * 60 * 60))));
			$newPayPeriod->end = gmdate('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->end . " -0000") +
					(14 * 24 * 60 * 60))));
		} else if ($payPeriod->type == "semimonthly") {
			// Get the current day.
			$currDay = gmdate('d', strtotime($payPeriod->start . " -0000"));

			// Check to see if it is the first day of the month.
			if ($currDay == "01") {
				// Set the next pay period to the 16th through the end of the
				// month.
				$ym = gmdate('Y-m', strtotime($payPeriod->start . ' -0000'));
				$start = $ym . '-16 -0000';
				$newPayPeriod->start = gmdate('Y-m-d', strtotime($start));

				$end = $ym . '-31 -0000';
				$newPayPeriod->end = gmdate('Y-m-d', strtotime($end));
				while (gmdate('Y-m', strtotime(
								$newPayPeriod->end . ' -0000')) != $ym)
					$newPayPeriod->end = gmdate('Y-m-d', strtotime(
						$newPayPeriod->end . ' -0000') - (24 * 60 * 60));
			} else {
				// Set the next pay period to the 1st through the 15th of the
				// next month.
				$month = (int) gmdate('m', strtotime($payPeriod->start)) + 1;
				$year = (int) gmdate('Y', strtotime($payPeriod->start));
				if ($month == 13) {
					$year++;
					$month = "01";
				}
				$newPayPeriod->start = gmdate('Y-m-d',
						strtotime("$year-$month-01 -0000"));
				$newPayPeriod->end = gmdate('Y-m-d',
						strtotime("$year-$month-15 -0000"));
			}
		}

		// Return the generated pay period.
		return $newPayPeriod;
    }

	/**
	 * Get the pay period prior to the one provided.
	 */
    public static function getPrev($payPeriod)
    {
		// Make sure we have something to work with.
		if (! isset($payPeriod))
			return $payPeriod;

		// Create the new pay period.
		$newPayPeriod = new stdClass();

		// Copy over the type.
		$newPayPeriod->type = $payPeriod->type;

		// Move the start and end dates based on the type.
		if ($payPeriod->type == "weekly") {
			// Remove 7 days from the start and end.
			$newPayPeriod->start = date('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->start . " -0000") -
					(7 * 24 * 60 * 60))));
			$newPayPeriod->end = date('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->end . " -0000") -
					(7 * 24 * 60 * 60))));
		} else if ($payPeriod->type == "biweekly") {
			// Remove 14 days from the start and end.
			$newPayPeriod->start = date('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->start . " -0000") -
					(14 * 24 * 60 * 60))));
			$newPayPeriod->end = date('Y-m-d', strtotime(gmdate('Y-m-d',
					strtotime($payPeriod->end . " -0000") -
					(14 * 24 * 60 * 60))));
		} else if ($payPeriod->type == "semimonthly") {
			// Get the current day.
			$currDay = gmdate('d', strtotime($payPeriod->start . " -0000"));

			// Check to see if it is the first day of the month.
			if ($currDay == "01") {
				// Set the new pay period to the 16th through the end of the
				// prior month.
				$month = (int) gmdate('m', strtotime($payPeriod->start)) - 1;
				$year = (int) gmdate('Y', strtotime($payPeriod->start));
				if ($month == 0) {
					$year--;
					$month = 12;
				}
				$newPayPeriod->start = gmdate('Y-m-d',
						strtotime("$year-$month-16 -0000"));

				$ym = gmdate('Y-m', strtotime($newPayPeriod->start . ' -0000'));
				$end = $ym . '-31 -0000';
				$newPayPeriod->end = gmdate('Y-m-d', strtotime($end));
				while (gmdate('Y-m', strtotime(
								$newPayPeriod->end . ' -0000')) != $ym)
					$newPayPeriod->end = gmdate('Y-m-d', strtotime(
						$newPayPeriod->end . ' -0000') - (24 * 60 * 60));
			} else {
				// Set the new pay period to the 1st through the 15th of the
				// current month.
				$ym = gmdate('Y-m', strtotime($payPeriod->start));
				$newPayPeriod->start = gmdate('Y-m-d', strtotime("$ym-01 -0000"));
				$newPayPeriod->end = gmdate('Y-m-d', strtotime("$ym-15 -0000"));
			}
		}

		// Return the generated pay period.
		return $newPayPeriod;
    }

	/**
	 * Determine if the provided pay period is the current pay period.
	 */
    public static function isCurrent($payPeriod)
    {
		// Make sure we have something to work with.
		if (! isset($payPeriod))
			return false;

		// Get the milliseconds for right now.
		$now = strtotime(date('Y-m-d'));

		// Check to see if now is within the provided pay period.
		return ($now >= strtotime($payPeriod->start) &&
				$now <= strtotime($payPeriod->end));
    }
}

