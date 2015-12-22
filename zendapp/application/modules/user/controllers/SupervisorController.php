<?php

class User_SupervisorController extends BaseController
{
	/**
	 * Retrieve the JSON representing all the supervisors for an employee.
	 */
    public function jsonAction()
    {
		// Retrieve the employee id.
		$id = $this->getInt('id');

		// Wrap the whole thing in a try/catch.
		try {
			// Make sure the id is valid.
			if (isset($id) && is_numeric($id)) {
				// Get all the supervisors.
				$employeeDao = new EmployeeDao();
				$supervisors = $employeeDao->getSupervisorEmployees($id);

				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->supervisors = $supervisors;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'A valid employee id must be provided.';
				$json->supervisors = array();
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->supervisors = array();
		}

		// Return all the employees as JSON.
		$this->_helper->json($json);
	}
}

