<?php

class Payroll_TimesheetController extends BaseController
{
	/**
	 * View the specified timesheets.
	 */
	function viewAction()
	{
		// Set the page title.
		$this->view->title = "Payroll Timesheets";

		// Get the ids of the timesheets to verify.
		$ids = $this->getInts('ids');

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
				'log' => "Payroll $me->full_name viewing timesheet."
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
		$this->view->title = "Payroll Timesheets";

		// Get the requested date.
		$day = $this->getDate('day');

		// Get the ids of the employees whose timesheets are to be displayed.
		$ids = $this->getInts('ids');

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

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
		$this->view->title = "Payroll Timesheets";

		// Get the requested date.
		$day = $this->getDate('day');

		// Get the ids of the employees whose timesheets are to be displayed.
		$ids = $this->getInts('ids');

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

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
		$this->view->title = "Payroll Timesheets";

		// Get the requested date.
		$day = $this->getDate('day');

		// Get the ids of the employees whose timesheets are to be displayed.
		$ids = $this->getInts('ids');

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
	 * Verify the specified timesheets.
	 */
	function verifyAction()
	{
		// Get the ids of the timesheets to verify.
		$ids = $this->getInts('ids');

		// Determine if there are multiple timesheets to verify.
		$multiple = count($ids) > 1 ? true : false;

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$timesheetDao = new TimesheetDao();

			// Verify all the timesheets.
			$count = $timesheetDao->verify($ids, $me, true);

			// Make sure some timesheets were verified.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The timesheets were verified successfully.';
				else
					$json->msg = 'The timesheet was verified successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to verify the timesheets.';
				else
					$json->msg = 'Failed to verify the timesheet.';
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
	 * Unverify the specified timesheets.
	 */
	function unverifyAction()
	{
		// Get the ids of the timesheets to unverify.
		$ids = $this->getInts('ids');

		// Determine if there are multiple timesheets to unverify.
		$multiple = count($ids) > 1 ? true : false;

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$timesheetDao = new TimesheetDao();

			// Unverify all the timesheets.
			$count = $timesheetDao->verify($ids, $me, false);

			// Make sure some timesheets were unverified.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The timesheets were unverified successfully.';
				else
					$json->msg = 'The timesheet was unverified successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to unverify the timesheets.';
				else
					$json->msg = 'Failed to unverify the timesheet.';
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
	 * Export the specified timesheets.
	 */
	function exportAction()
	{
		// Get the ids of the timesheets to export.
		$ids = $this->getInts('ids');

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Get the DAO.
		$timesheetDao = new TimesheetDao();

		// Get the mail configuration.
		$config = Bootstrap::$registry->config;

		// Generate all the export data for the timesheets.
		$qbdata = $timesheetDao->export($ids, $me, $config);

		// Define the output file.
		$OUTFILE = dirname(dirname(__FILE__)) . '/timesheet-export.iif';

		// Write the file data.
		$handle = fopen($OUTFILE, "w");
		fwrite($handle, $qbdata);
		fclose($handle);

		// Set the headers.
		$this->getResponse()
				->clearHeaders()
				->setHeader('Content-Type', 'application/iif')
				->setHeader('Content-Disposition',
						'attachment; filename="timesheet-export.iif"')
				->setHeader('Content-Transfer-Encoding', 'Binary')
				->setHeader('Content-Length', strlen($qbdata))
				->sendHeaders();

		// Read the data file to the response.
		readfile($OUTFILE);

		// Disable the view and layout.
		$this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
	}

	/**
	 * Unexport the specified timesheets.
	 */
	function unexportAction()
	{
		// Get the ids of the timesheets to unexport.
		$ids = $this->getInts('ids');

		// Determine if there are multiple timesheets to unexport.
		$multiple = count($ids) > 1 ? true : false;

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$timesheetDao = new TimesheetDao();

			// Unexport all the timesheets.
			$count = $timesheetDao->unexport($ids, $me);

			// Make sure some timesheets were unexported.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The timesheets were unexported successfully.';
				else
					$json->msg = 'The timesheet was unexported successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to unexport the timesheets.';
				else
					$json->msg = 'Failed to unexport the timesheet.';
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
}

