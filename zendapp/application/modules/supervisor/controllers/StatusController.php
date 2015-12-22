<?php

class Supervisor_StatusController extends BaseController
{
	/**
	 * Retrieve the timesheet status information.
	 */
	function jsonAction()
	{
		// Get the pay period start date.
		$ppstart = $this->getDate('ppStart');

		// Attempt to convert the pay period start time into a date.
		if (!strtotime($ppstart))
			// Throw an exception.
			throw new Exception("Invalid date: $ppstart");

		// Get the requested pay period.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($ppstart);

		// Make sure a start date was provided.
		if (isset($ppstart)) {
			// Get the user's session.
			$session = new Zend_Session_Namespace('Web');

			// Get the current user.
			$me = $session->employee;

			// Get an instance of the TimesheetDao.
			$timesheetDao = new TimesheetDao();

			// Retrieve the status information for the specified pay period.
			$status = $timesheetDao->getStatusForSupervised($payPeriod, $me);

			// Check to see if the status was retrieved.
			if (isset($status)) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->status = $status;
				$json->success = true;
				$json->msg = 'The requested status information was ' .
					'retrieved successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to retrieve the status information.';
			}
		} else {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = 'You must specify the pay period start date.';
		}

		// Return the JSON.
		$this->_helper->json($json);
	}
}

