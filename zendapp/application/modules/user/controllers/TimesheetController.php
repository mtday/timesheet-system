<?php

class User_TimesheetController extends BaseController
{
	/**
	 * Go show the timesheet page.
	 */
	function indexAction()
	{
		// Set the page title.
		$this->view->title = "Viewer";

		// Get the employee id.
		$id = $this->view->employee->id;

		// Get the employee's latest timesheet and save it to the view.
		$timesheetDao = new TimesheetDao();
		$this->view->timesheet = $timesheetDao->getLatestForEmployee($id);

		// Is this timesheet already completed?
		if ($this->view->timesheet->completed == "1") {
			// Get the pay period in the timesheet.
			$payPeriodDao = new PayPeriodDao();
			$payPeriod = $payPeriodDao->get($this->view->timesheet->pp_start);

			// Get the next pay period.
			$next = PayPeriodHelper::getNext($payPeriod);

			// Attempt to retrieve the next pay period from the database.
			$realnext = $payPeriodDao->get($next->start);

			// Make sure it was found.
			if (!isset($realnext)) {
				// Create the new pay period.
				$payPeriodDao->add(array(
					'start' => $next->start,
					'end'   => $next->end,
					'type'  => $next->type
				));
				$realnext = $next;
			}

			// Now get the timesheet.
			$this->view->timesheet = $timesheetDao->getForEmployee($id, $realnext);
		}

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');
	}

	/**
	 * Retrieve the specified timesheet and show it on the timesheet page.
	 */
	function viewAction()
	{
		// Set the page title.
		$this->view->title = "Viewer";

		// Get the date within the pay period.
		$date = $this->getStr('date');

		// Attempt to convert the current start time into a date.
		if (!strtotime($date))
			// Throw an exception.
			throw new Exception("Invalid date passed into " .
					"the timesheet controller: $date");

		// Get the requested pay period.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($date);

		// Make sure the pay period was found.
		if (!isset($payPeriod)) {
			// Save an error message.
			$this->view->error = "Failed to find a system pay period " .
				"containing $date, so displaying the current pay period.\n";

			// Retrieve the current pay period.
			$payPeriod = $payPeriodDao->getCurrent();
		}

		// Get the employee id.
		$id = $this->view->employee->id;

		// Get the employee's latest timesheet and save it to the view.
		$timesheetDao = new TimesheetDao();
		$this->view->timesheet =
			$timesheetDao->getForEmployee($id, $payPeriod);

		// Make sure a timesheet was found.
		if (! isset($this->view->timesheet))
			// Throw an exception if we couldn't find the timesheet.
			throw new Exception("Failed to find a timesheet for the " .
					"requested pay period.");

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');

		// Render the timesheet using the index view.
		$this->render('index');
	}

	/**
	 * Retrieve the previous timesheet and show it on the timesheet page.
	 */
	function prevAction()
	{
		// Set the page title.
		$this->view->title = "Viewer";

		// Get the current pay period start date.
		$currstart = $this->getStr('currstart');

		// Attempt to convert the current start time into a date.
		if (!strtotime($currstart))
			// Throw an exception.
			throw new Exception("Invalid pay period start date passed into " .
					"the timesheet controller: $currstart");

		// Get the requested pay period.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->get($currstart);

		// Get the previous pay period.
		$prev = PayPeriodHelper::getPrev($payPeriod);

		// Attempt to retrieve the previous pay period from the database.
		$realprev = $payPeriodDao->get($prev->start);

		// Make sure it was found.
		if (!isset($realprev)) {
			// Create the new pay period.
			$payPeriodDao->add(array(
				'start' => $prev->start,
				'end'   => $prev->end,
				'type'  => $prev->type
			));
			$realprev = $prev;
		}

		// Get the employee id.
		$id = $this->view->employee->id;

		// Get the employee's latest timesheet and save it to the view.
		$timesheetDao = new TimesheetDao();
		$this->view->timesheet = $timesheetDao->getForEmployee($id, $realprev);

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');

		// Render the timesheet using the index view.
		$this->render('index');
	}

	/**
	 * Retrieve the next timesheet and show it on the timesheet page.
	 */
	function nextAction()
	{
		// Set the page title.
		$this->view->title = "Viewer";

		// Get the current pay period start date.
		$currstart = $this->getStr('currstart');

		// Attempt to convert the current start time into a date.
		if (!strtotime($currstart))
			// Throw an exception.
			throw new Exception("Invalid pay period start date passed into " .
					"the timesheet controller: $currstart");

		// Get the requested pay period.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->get($currstart);

		// Get the next pay period.
		$next = PayPeriodHelper::getNext($payPeriod);

		// Attempt to retrieve the next pay period from the database.
		$realnext = $payPeriodDao->get($next->start);

		// Make sure it was found.
		if (!isset($realnext)) {
			// Create the new pay period.
			$payPeriodDao->add(array(
				'start' => $next->start,
				'end'   => $next->end,
				'type'  => $next->type
			));
			$realnext = $next;
		}

		// Get the employee id.
		$id = $this->view->employee->id;

		// Get the employee's latest timesheet and save it to the view.
		$timesheetDao = new TimesheetDao();
		$this->view->timesheet = $timesheetDao->getForEmployee($id, $realnext);

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');

		// Render the timesheet using the index view.
		$this->render('index');
	}

	/**
	 * Save updated timesheet data into the database.
	 */
	function saveAction()
	{
		// Get the id of the active timesheet.
		$timesheetId = $this->getInt('id');

		// Get the timesheet data to save.
		$data = $this->getStr('data');

		// Get the employee's current timesheet and save it to the view.
		$timesheetDao = new TimesheetDao();
		$this->view->timesheet = $timesheetDao->get($timesheetId);

		// Save the timesheet data in the timesheet.
		$this->saveTimesheetData($this->view->timesheet, $data);

		// Create the JSON object to return.
		$json = new stdClass();
		$json->success = true;
		$json->msg = 'Your timesheet information was saved successfully.';

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Save updated timesheet data into the database and mark the timesheet
	 * as being completed.
	 */
	function completeAction()
	{
		// Get the id of the active timesheet.
		$timesheetId = $this->getInt('id');

		// Get the timesheet data to save.
		$data = $this->getStr('data');

		// Get the employee's current timesheet.
		$timesheetDao = new TimesheetDao();
		$timesheet = $timesheetDao->get($timesheetId);

		// Save the timesheet data in the timesheet.
		$this->saveTimesheetData($timesheet, $data);

		// Mark the timesheet as being completed.
		$timesheetDao->save($timesheet->id, array(
			'completed' => true
		));

		// Add an audit log for this completion.
		$auditLogDao = new AuditLogDao();
		$auditLogDao->add(array(
			'timesheet_id' => $timesheet->id,
			'log' => "Timesheet completed."
		));

		// Create the JSON object to return.
		$json = new stdClass();
		$json->success = true;
		$json->payPeriod = $timesheet->pp_start;
		$json->msg = 'Your timesheet was completed successfully. Moving to ' .
			'the next pay period...';

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Save updated timesheet data into the database and mark the timesheet
	 * as being completed.
	 */
	private function saveTimesheetData($timesheet, $data)
	{
		// Make sure the timesheet and data are both valid.
		if (!isset($timesheet) || !isset($timesheet->id))
			return;

		// Get the AuditLogDao instance used to log timesheet changes.
		$auditLogDao = new AuditLogDao();

		// Get the BillDao instance used to save timesheet hour changes.
		$billDao = new BillDao();

		// Get the employee id.
		$empId = $timesheet->employee->id;

		// $data looks like this: "8_57:20100602:5.00;8_56:20100605:8.00"

		// Explode the provided data based on semi-colon.
		$explodedData = explode(';', $data);

		// This will hold all the processed hours.
		$processed = array();

		// Iterate over the hourly parts.
		foreach ($explodedData as $dailyPart) {
			// Handle an empty data value.
			if (! $dailyPart)
				continue;

			// Explode the daily part into its pieces.
			$pieces = explode(':', $dailyPart, 4);

			// Convert the date into the 'Y-m-d' format.
			$day = gmdate('Y-m-d', strtotime($pieces[1] . " -0000"));

			// Add this bill to the array of processed bills.
			$processed["$pieces[0]:$day"] = 1;

			// Parse the contract id and assignment id from piece 0.
			$ids = explode('_', $pieces[0], 2);

			// Get the contract for this bill.
			if (is_numeric($ids[1]))
				$contract = TimesheetHelper::getContractFromAssignment($timesheet, $ids[1]);
			else
				$contract = TimesheetHelper::getContract($timesheet, $ids[0]);

			// Make sure the contract is valid.
			if (!isset($contract))
				continue;

			// Get the existing timesheet bill.
			if (is_numeric($ids[1]))
				$bill = TimesheetHelper::getBillFromAssignment($timesheet, $ids[1], $day);
			else
				$bill = TimesheetHelper::getBill($timesheet, $ids[0], $day);

			// Get the new hours as a float.
			$newHours = 0.0 + $pieces[2];

			// Check to see if the hours changed.
			if (isset($bill) && $newHours != $bill->hours) {
				// Update the bill.
				$billDao->save($bill->id, array(
					'hours' => $newHours,
					'employee_id' => $empId,
					'timestamp' => null
				));

				// Build the log message.
				$log = "Hours for contract $contract->description " .
						(isset($contract->labor_cat) ? "(LCAT: $contract->labor_cat) " : "") .
						"on $day changed from $bill->hours to $newHours." .
						(isset($pieces[3]) ? " The user-specified reason: $pieces[3]" : "");

				// Add an audit log for this change in hours.
				$auditLogDao->add(array(
					'timesheet_id' => $timesheet->id,
					'log' => $log
				));
			} else if (!isset($bill)) {
				// Create the bill.
				$billDao->add(array(
					'assignment_id' => (is_numeric($ids[1]) ? $ids[1] : null),
					'contract_id' => $contract->contract_id,
					'employee_id' => $empId,
					'day' => $day,
					'hours' => $newHours
				));

				// Add an audit log for this addition of hours.
				$auditLogDao->add(array(
					'timesheet_id' => $timesheet->id,
					'log' => "Added $newHours hours for contract $contract->description " .
						(isset($contract->labor_cat) ? "(LCAT: $contract->labor_cat) " : "") .
						"on $day."
				));
			}
	  	}

		// Make sure the timesheet and it's bills are valid.
		if (isset($timesheet) && isset($timesheet->bills) &&
				count($timesheet->bills) > 0)
			// Iterate over the bills in the timesheet.
			foreach ($timesheet->bills as $bill) {
				$id = $bill->contract_id . "_" . (isset($bill->assignment_id) ? $bill->assignment_id : "");

				// Check to see if this bill was processed.
				if (!isset($processed["$id:$bill->day"])) {
					// This bill needs to be deleted since we didn't see it
					// in the incoming data.
					$billDao->remove(array($bill->id));

					// Get the contract for this bill.
					if (isset($bill->assignment_id))
						$contract = TimesheetHelper::getContractFromAssignment($timesheet,
								$bill->assignment_id);
					else
						$contract = TimesheetHelper::getContract($timesheet,
								$bill->contract_id);

					// Make sure the contract was found.
					if (isset($contract))
						// Add an audit log for this change in hours.
						$auditLogDao->add(array(
							'timesheet_id' => $timesheet->id,
							'log' => "Hours for contract $contract->description " .
								(isset($contract->labor_cat) ? "(LCAT: $contract->labor_cat) " : "") .
								"on $bill->day changed from $bill->hours to 0.00."
						));
				}
			}
	}

	/**
	 * Re-open a completed timesheet so that the hours can be modified.
	 */
	function fixAction()
	{
		// Get the id of the active timesheet.
		$timesheetId = $this->getInt('id');

		// Get the employee's current timesheet.
		$timesheetDao = new TimesheetDao();
		$timesheet = $timesheetDao->get($timesheetId);

		// Re-open the timesheet.
		$timesheetDao->save($timesheet->id, array(
			'completed' => false
		));

		// Add an audit log for this completion.
		$auditLogDao = new AuditLogDao();
		$auditLogDao->add(array(
			'timesheet_id' => $timesheet->id,
			'log' => "Timesheet re-opened."
		));

		// Create the JSON object to return.
		$json = new stdClass();
		$json->success = true;
		$json->payPeriod = $timesheet->pp_start;
		$json->msg = 'Your timesheet was re-opened successfully. Refreshing ' .
			'the display...';

		// Return the JSON.
		$this->_helper->json($json);
	}
}

