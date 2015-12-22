<?php

class Supervisor_TimesheetController extends BaseController
{
	/**
	 * View the specified timesheets.
	 */
	function viewAction()
	{
		// Set the page title.
		$this->view->title = "Employee Timesheets";

		// Get the ids of the timesheets to verify.
		$ids = $this->getInts('ids');

		// Flag that the timesheets should be editable.
		$this->view->editable = $this->getBool('edit');
		if (!isset($this->view->editable))
			$this->view->editable = false;

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Get the DAO.
		$timesheetDao = new TimesheetDao();

		// Retrieve all the requested timesheets and save them in the view.
		$this->view->timesheets = $timesheetDao->getGroup($ids);

		// Save the pay period to the view.
		if (isset($this->view->timesheets) && count($this->view->timesheets) > 0)
			$this->view->payPeriod = $this->view->timesheets[0]->pay_period;

		// Get the AuditLogDao instance used to log timesheet actions.
		$auditLogDao = new AuditLogDao();

		// Add audit log entries for viewing the timesheet.
		foreach ($ids as $id)
			$auditLogDao->add(array(
				'timesheet_id' => $id,
				'log' => ($this->view->editable) ?
					"Supervisor $me->full_name viewing timesheet in edit mode." :
					"Supervisor $me->full_name viewing timesheet."
			));

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');
	}

	/**
	 * Retrieve the next pay period and show the employee timesheets.
	 */
	function nextAction()
	{
		// Set the page title.
		$this->view->title = "Employee Timesheets";

		// Get the requested date.
		$day = $this->getDate('day');

		// Get the ids of the employees whose timesheets are to be displayed.
		$ids = $this->getInts('ids');

		// Flag that the timesheets should be editable.
		$this->view->editable = $this->getBool('edit');
		if (!isset($this->view->editable))
			$this->view->editable = false;

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

		// Make sure the pay period was found.
		if (!isset($payPeriod)) {
			// Save an error message.
			$this->view->error = "Failed to find a system pay period " .
				"containing $day, so displaying the current pay period.\n";

			// Retrieve the current pay period.
			$payPeriod = $payPeriodDao->getCurrent();
		}

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

		// Get the next pay period.
		$this->view->payPeriod = $realnext;

		// Save the active pay period in the session.
		$session = new Zend_Session_Namespace('Web');
		$session->activePayPeriod = $this->view->payPeriod;

		// Get the DAO.
		$timesheetDao = new TimesheetDao();

		// This will hold all the timesheets.
		$this->view->timesheets = array();

		// Retrieve all the timesheets.
		foreach ($ids as $id)
			$this->view->timesheets[] = $timesheetDao->getForEmployee(
					$id, $this->view->payPeriod);

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');

		// Render the view.phtml page.
		$this->render('view');
	}

	/**
	 * Retrieve the previous pay period and show the employee timesheets.
	 */
	function prevAction()
	{
		// Set the page title.
		$this->view->title = "Employee Timesheets";

		// Get the requested date.
		$day = $this->getDate('day');

		// Get the ids of the employees whose timesheets are to be displayed.
		$ids = $this->getInts('ids');

		// Flag that the timesheets should be editable.
		$this->view->editable = $this->getBool('edit');
		if (!isset($this->view->editable))
			$this->view->editable = false;

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

		// Make sure the pay period was found.
		if (!isset($payPeriod)) {
			// Save an error message.
			$this->view->error = "Failed to find a system pay period " .
				"containing $day, so displaying the current pay period.\n";

			// Retrieve the current pay period.
			$payPeriod = $payPeriodDao->getCurrent();
		}

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

		// Get the previous pay period.
		$this->view->payPeriod = $realprev;

		// Save the active pay period in the session.
		$session = new Zend_Session_Namespace('Web');
		$session->activePayPeriod = $this->view->payPeriod;

		// Get the DAO.
		$timesheetDao = new TimesheetDao();

		// This will hold all the timesheets.
		$this->view->timesheets = array();

		// Retrieve all the timesheets.
		foreach ($ids as $id)
			$this->view->timesheets[] = $timesheetDao->getForEmployee(
					$id, $this->view->payPeriod);

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');

		// Render the view.phtml page.
		$this->render('view');
	}

	/**
	 * Retrieve the chosen pay period and show the employee timesheets.
	 */
	function chooseAction()
	{
		// Set the page title.
		$this->view->title = "Employee Timesheets";

		// Get the requested date.
		$day = $this->getDate('day');

		// Get the ids of the employees whose timesheets are to be displayed.
		$ids = $this->getInts('ids');

		// Flag that the timesheets should be editable.
		$this->view->editable = $this->getBool('edit');
		if (!isset($this->view->editable))
			$this->view->editable = false;

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$this->view->payPeriod = $payPeriodDao->getContaining($day);

		// Save the active pay period in the session.
		$session = new Zend_Session_Namespace('Web');
		$session->activePayPeriod = $this->view->payPeriod;

		// Get the DAO.
		$timesheetDao = new TimesheetDao();

		// This will hold all the timesheets.
		$this->view->timesheets = array();

		// Retrieve all the timesheets.
		foreach ($ids as $id)
			$this->view->timesheets[] = $timesheetDao->getForEmployee(
					$id, $this->view->payPeriod);

		// Set the timesheet layout for this action.
		$this->_helper->layout->setLayout('timesheet');

		// Render the view.phtml page.
		$this->render('view');
	}

	/**
	 * Approve the specified timesheets.
	 */
	function approveAction()
	{
		// Get the ids of the timesheets to approve.
		$ids = $this->getInts('ids');

		// Determine if there are multiple timesheets to approve.
		$multiple = count($ids) > 1 ? true : false;

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$timesheetDao = new TimesheetDao();

			// Approve all the timesheets.
			$count = $timesheetDao->approve($ids, $me, true);

			// Make sure some timesheets were approved.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The timesheets were approved successfully.';
				else
					$json->msg = 'The timesheet was approved successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to approve the timesheets.';
				else
					$json->msg = 'Failed to approve the timesheet.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Disapprove the specified timesheets.
	 */
	function disapproveAction()
	{
		// Get the ids of the timesheets to disapprove.
		$ids = $this->getInts('ids');

		// Determine if there are multiple timesheets to disapprove.
		$multiple = count($ids) > 1 ? true : false;

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$timesheetDao = new TimesheetDao();

			// Disapprove all the timesheets.
			$count = $timesheetDao->approve($ids, $me, false);

			// Make sure some timesheets were disapproved.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The timesheets were disapproved successfully.';
				else
					$json->msg = 'The timesheet was disapproved successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to disapprove the timesheets.';
				else
					$json->msg = 'Failed to disapprove the timesheet.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Retrieve the audit logs for a timesheet.
	 */
	function auditAction()
	{
		// Get the id of the timesheet for which audit log information will be
		// retrieved.
		$id = $this->getInt('id');

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$auditLogDao = new AuditLogDao();

			// Make sure the id is valid.
			if (isset($id) && is_numeric($id)) {
				// Retrieve all the audit logs.
				$logs = $auditLogDao->getForTimesheet($id);

				// Create the JSON object to return.
				$json = new stdClass();
				$json->logs = $logs;
				$json->success = true;
				$json->msg = 'The audit log info was retrieved successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Invalid timesheet id specified.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Save updated timesheet data into the database on behalf of an employee.
	 */
	function saveAction()
	{
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Get the id of the active timesheet.
		$timesheetId = $this->getInt('id');

		// Get the timesheet data to save.
		$data = $this->getStr('data');

		// Get the employee's current timesheet and save it to the view.
		$timesheetDao = new TimesheetDao();
		$this->view->timesheet = $timesheetDao->get($timesheetId);

		// Save the timesheet data in the timesheet.
		$this->saveTimesheetData($me, $this->view->timesheet, $data);

		// Create the JSON object to return.
		$json = new stdClass();
		$json->success = true;
		$json->msg = 'The timesheet information was saved successfully.';

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Save updated timesheet data into the database and mark the timesheet
	 * as being completed.
	 */
	function completeAction()
	{
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Get the id of the active timesheet.
		$timesheetId = $this->getInt('id');

		// Get the timesheet data to save.
		$data = $this->getStr('data');

		// Get the employee's current timesheet.
		$timesheetDao = new TimesheetDao();
		$timesheet = $timesheetDao->get($timesheetId);

		// Save the timesheet data in the timesheet.
		$this->saveTimesheetData($me, $timesheet, $data);

		// Mark the timesheet as being completed.
		$timesheetDao->save($timesheet->id, array(
			'completed' => true
		));

		// Add an audit log for this completion.
		$auditLogDao = new AuditLogDao();
		$auditLogDao->add(array(
			'timesheet_id' => $timesheet->id,
			'log' => "Timesheet completed by supervisor " . $me->full_name .
				" on behalf of user."
		));

		// Create the JSON object to return.
		$json = new stdClass();
		$json->success = true;
		$json->payPeriod = $timesheet->pp_start;
		$json->msg = 'The timesheet was completed successfully.';

		// Return the JSON.
		$this->_helper->json($json);
	}

	/**
	 * Save updated timesheet data into the database and mark the timesheet
	 * as being completed.
	 */
	private function saveTimesheetData($me, $timesheet, $data)
	{
		// Make sure the timesheet and data are both valid.
		if (!isset($me) || !isset($timesheet) || !isset($timesheet->id))
			return;

		// Get the AuditLogDao instance used to log timesheet changes.
		$auditLogDao = new AuditLogDao();

		// Get the BilDao instance used to save timesheet hour changes.
		$billDao = new BillDao();

		// Get the employee id.
		$empId = $timesheet->employee->id;

		// $data looks like this: "8:20100602:5.00;8:20100605:8.00"

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
						"on $day changed from $bill->hours to $newHours " .
						"by supervisor $me->full_name." .
						($pieces[3] ? " The specified reason: $pieces[3]" : "");

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
					'log' => "Supervisor $me->full_name Added $newHours hours for contract " .
						"$contract->description " .
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
								"on $bill->day changed from $bill->hours to 0.00 " .
								"by supervisor $me->full_name."
						));
				}
			}
	}

	/**
	 * Re-open a completed timesheet so that the hours can be modified.
	 */
	function fixAction()
	{
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

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
			'log' => "Timesheet re-opened by supervisor $me->full_name."
		));

		// Create the JSON object to return.
		$json = new stdClass();
		$json->success = true;
		$json->payPeriod = $timesheet->pp_start;
		$json->msg = 'Your timesheet was re-opened successfully.';

		// Return the JSON.
		$this->_helper->json($json);
	}
}

