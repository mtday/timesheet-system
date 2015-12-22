<?php

class Manager_SupervisorController extends BaseController
{
	/**
	 * Add a supervisor to an employee.
	 */
    public function addAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the supervisor.
			$data = array(
				'employee_id'   => $this->getInt('employee_id'),
				'supervisor_id' => $this->getInt('supervisor_id'),
				'primary'       => $this->getBool('primary')
			);

			// Get the DAO.
			$supervisorDao = new SupervisorDao();

			// Add the supervisor.
			$supervisorDao->add($data);

			// Create the JSON object to return.
			$json = new stdClass();
			$json->success = true;
			$json->msg = 'The supervisor was added successfully.';
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
	 * Delete a supervisor.
	 */
    public function deleteAction()
    {
		// Get the employee id for which supervisors are to be deleted.
		$empId = $this->getInt('employee_id');

		// Get the ids of the supervisors to delete.
		$ids = $this->getInts('supervisor_ids');

		// Determine if there are multiple contracts to delete.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$supervisorDao = new SupervisorDao();

			// Delete all the supervisors.
			$count = $supervisorDao->removeAll($empId, $ids);

			// Make sure some supervisors were deleted.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The supervisors were removed successfully.';
				else
					$json->msg = 'The supervisor was removed successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to delete the supervisors.';
				else
					$json->msg = 'Failed to delete the supervisor.';
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

