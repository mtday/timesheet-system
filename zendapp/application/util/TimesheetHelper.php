<?php

class TimesheetHelper
{
	/**
	 * Retrieve the HTML used to display the timesheet status.
	 */
    public static function getStatus($timesheet)
    {
		// Make sure the pay period is valid.
		if (!isset($timesheet))
			return "Unknown";

		// This will hold the HTML.
		$html = '<span class="status">Completed</span>';

		// Add the completion status span.
		$html .= '<span id="completion-status-' . $timesheet->id . '">';

		// Check to see if the timesheet has been completed.
		if ($timesheet->completed)
			// Add the completed image.
			$html .= '
				<img src="/images/icons/bullet_green.png" border="0"
					 alt="Completed" title="Completed"/>';
		else
			// Not completed.
			$html .= '
				<img src="/images/icons/bullet_red.png" border="0"
					 alt="Incomplete" title="Incomplete"/>';

		// Close the completion status span.
		$html .= '</span>';

		// Add the approval status.
		$html .= '<span class="status">Approved</span>';

		// Add the approval status span.
		$html .= '<span id="approval-status-' . $timesheet->id . '">';

		// Check to see if the timesheet has been approved.
		if ($timesheet->approved)
			// Add the approved image.
			$html .= '
				<img src="/images/icons/bullet_green.png" border="0"
					 alt="Approved" title="Approved"/>';
		else
			// Not approved.
			$html .= '
				<img src="/images/icons/bullet_red.png" border="0"
					 alt="Not Approved" title="Not Approved"/>';

		// Close the approval status span.
		$html .= '</span>';

		// Add the payroll status.
		$html .= '<span class="status">Payroll</span>';

		// Add the payroll status span.
		$html .= '<span id="payroll-status-' . $timesheet->id . '">';

		// Check to see if the timesheet has been approved.
		if ($timesheet->verified)
			// Add the verified image.
			$html .= '
				<img src="/images/icons/bullet_green.png" border="0"
					 alt="Payroll Processed" title="Payroll Processed"/>';
		else
			// Not processed.
			$html .= '
				<img src="/images/icons/bullet_red.png" border="0"
					 alt="Not Processed" title="Not Processed"/>';

		// Close the payroll status span.
		$html .= '</span>';

		// Return the generated HTML.
		return $html;
    }

	/**
	 * Determine whether the LCAT should be displayed.
	 */
	public static function shouldDisplayLcat($timesheet, $contractId)
	{
		// Keep track of the number of contract matches.
		$count = 0;

		// Iterate over the contracts in the timesheet.
		if (isset($timesheet) && isset($timesheet->contracts) &&
				count($timesheet->contracts) > 0)
			foreach ($timesheet->contracts as $contract)
				if ($contract->contract_id == $contractId)
					$count = $count + 1;

		// If there are multiple matches, then display the LCAT info.
		return $count > 1;
	}

	/**
	 * Retrieve the number of hours for a contract on a day.
	 */
	public static function getHours($timesheet, $date, $contractId, $assignmentId)
	{
		// Get the day we are looking for.
		$day = gmdate('Y-m-d', $date);

		// Iterate over the bills in the timesheet.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			foreach ($timesheet->bills as $bill) {
				if (isset($assignmentId)) {
					if ($bill->assignment_id == $assignmentId &&
							$bill->day == $day)
						// Return the hours in this bill.
						return number_format($bill->hours, 2);
				} else {
					if ($bill->contract_id == $contractId &&
							$bill->day == $day)
						// Return the hours in this bill.
						return number_format($bill->hours, 2);
				}
			}

		// Not found.
		return "";
	}

	/**
	 * Retrieve the number of hours in a timesheet for a work week.
	 */
	public static function getWeekHours($timesheet, $date)
	{
		// Get the day we are looking for.
		$day = gmdate('D', $date);

		$begin = $date;
		$end = $date;
		while (gmdate('D', $begin) != 'Mon')
			$begin -= 60 * 60 * 24;
		while (gmdate('D', $end) != 'Fri')
			$end += 60 * 60 * 24;

		// Keep track of the total hours.
		$total = 0;

		// Iterate over the bills in the timesheet.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			foreach ($timesheet->bills as $bill)
				if (strtotime($bill->day . " -0000") >= $begin &&
						strtotime($bill->day . " -0000") <= $end)
					// Include the hours in this bill.
					$total += $bill->hours;

		return number_format($total, 2);
	}

	/**
	 * Retrieve the total number of hours for a contract.
	 */
	public static function getContractTotal($timesheet, $contractId, $assignmentId)
	{
		// This will hold the total.
		$total = 0;

		// Iterate over the bills in the timesheet.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			foreach ($timesheet->bills as $bill) {
				if (isset($assignmentId)) {
					if ($bill->assignment_id == $assignmentId)
						// Add these hours.
						$total += $bill->hours;
				} else if ($bill->contract_id == $contractId)
					// Add these hours.
					$total += $bill->hours;
			}

		// Return the number of hours found.
		return number_format($total, 2);
	}

	/**
	 * Retrieve the total number of hours for a day.
	 */
	public static function getDayTotal($timesheet, $date)
	{
		// Get the day we are looking for.
		$day = gmdate('Y-m-d', $date);

		// This will hold the total.
		$total = 0;

		// Iterate over the bills in the timesheet.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			foreach ($timesheet->bills as $bill)
				if ($bill->day == $day)
					// Add these hours.
					$total += $bill->hours;

		// Return the number of hours found.
		return number_format($total, 2);
	}

	/**
	 * Retrieve the total number of hours in the timesheet.
	 */
	public static function getTotal($timesheet)
	{
		// This will hold the total.
		$total = 0;

		// Iterate over the bills in the timesheet.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			foreach ($timesheet->bills as $bill)
				// Add these hours.
				$total += $bill->hours;

		// Return the number of hours found.
		return number_format($total, 2);
	}

	/**
	 * Retrieve the number of hours specified in a timesheet for a specific
	 * contract and day.
	 */
	public static function getBill($timesheet, $contractId, $day)
	{
		// Make sure the timesheet and it's bills are valid.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			// Iterate over the bills in the timesheet.
			foreach ($timesheet->bills as $bill)
				// Is this bill for the requested contract and day?
				if ($bill->contract_id == $contractId && $bill->day == $day)
					// Return this bill.
					return $bill;

		// Not found.
		return null;
	}

	/**
	 * Retrieve the number of hours specified in a timesheet for a specific
	 * contract assignment and day.
	 */
	public static function getBillFromAssignment($timesheet, $assignmentId, $day)
	{
		// Make sure the timesheet and it's bills are valid.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			// Iterate over the bills in the timesheet.
			foreach ($timesheet->bills as $bill)
				// Is this bill for the requested contract and day?
				if ($bill->assignment_id == $assignmentId && $bill->day == $day)
					// Return this bill.
					return $bill;

		// Not found.
		return null;
	}

	/**
	 * Retrieve a contract from the provided timesheet.
	 */
	public static function getContract($timesheet, $contractId)
	{
		// Make sure the timesheet and it's contracts are valid.
		if (isset($timesheet) && isset($timesheet->contracts) &&
				count($timesheet->contracts) > 0)
			// Iterate over the contracts in the timesheet.
			foreach ($timesheet->contracts as $contract)
				// Is this contract the one requested?
				if ($contract->contract_id == $contractId)
					// Return this contract.
					return $contract;

		// Not found.
		return null;
	}

	/**
	 * Retrieve a contract from the provided timesheet based on assignment id.
	 */
	public static function getContractFromAssignment($timesheet, $assignmentId)
	{
		// Make sure the timesheet and it's contracts are valid.
		if (isset($timesheet) && isset($timesheet->contracts) &&
				count($timesheet->contracts) > 0)
			// Iterate over the contracts in the timesheet.
			foreach ($timesheet->contracts as $contract)
				// Is this contract the one requested?
				if ($contract->assignment_id == $assignmentId)
					// Return this contract.
					return $contract;

		// Not found.
		return null;
	}

	/**
	 * Determine if the provided date is today.
	 */
	public static function isToday($date)
	{
		// Compare the provided date to today.
		return gmdate('Y-m-d', $date) == gmdate('Y-m-d');
	}

	/**
	 * Determine if the provided date is a weekend.
	 */
	public static function isWeekend($date)
	{
		// Get the day of week for the provided date.
		$dayOfWeek = gmdate('l', $date);

		// Return wether it is Sat or Sun.
		return $dayOfWeek == 'Saturday' || $dayOfWeek == 'Sunday';
	}

	/**
	 * Determine if the provided date is a holiday.
	 */
	public static function isHoliday($timesheet, $date)
	{
		// Get the specified day.
		$day = gmdate('Y-m-d', $date);

		// Check each of the timesheet holidays to see if the days match.
		if (count($timesheet->holidays) > 0)
			foreach ($timesheet->holidays as $holiday)
				if ($holiday->day == $day)
					return true;

		// Not a holiday.
		return false;
	}

	/**
	 * Determine if the specified contract is expired for the specified date.
	 */
	public static function isExpired($timesheet, $date, $assignmentId)
	{
		// Administrative contracts don't expire.
		if (!isset($assignmentId))
			return false;

		// Find the contract.
		if (count($timesheet->contracts) > 0)
			foreach ($timesheet->contracts as $contract)
				if ($contract->assignment_id == $assignmentId)
					// Check the contract bounds.
					return (isset($contract->end) &&
							strtotime($contract->end . " -0000") < $date) ||
						   (isset($contract->start) &&
							strtotime($contract->start . " -0000") > $date);

		// Couldn't find specified contract.
		return false;
	}

	/**
	 * Determine if the specified contract could be edited on the specified
	 * date.
	 */
	public static function canEdit($timesheet, $date, $assignmentId)
	{
		// Administrative contracts can be edited.
		if (!isset($assignmentId))
			return true;

		// Find the contract.
		if (count($timesheet->contracts) > 0)
			foreach ($timesheet->contracts as $contract)
				if ($contract->assignment_id == $assignmentId) {
					// If the contract is administrative, then it can be
					// edited.
					if ($contract->admin)
						return true;

					// If date is in the future, then cannot edit.
					return ($date <= strtotime(gmdate('Y-m-d') . " -0000"));
				}

		// Couldn't find specified contract.
		return false;
	}
}

