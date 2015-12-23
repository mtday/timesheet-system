<?php

class Manager_AssignmentController extends BaseController
{
	/**
	 * Add a contract assignment.
	 */
    public function addAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the assignment.
			$data = array(
				'contract_id'    => $this->getInt('contract_id'),
				'employee_id'    => $this->getInt('employee_id'),
				'labor_cat'      => $this->getStr('labor_cat'),
				'item_name'      => $this->getStr('item_name'),
				'start'          => $this->getDate('start'),
				'end'            => $this->getDate('end')
			);

			// Get the DAO.
			$assignmentDao = new ContractAssignmentDao();

			// Add the assignment.
			$assignmentId = $assignmentDao->add($data);

			// Retrieve the new assignment.
			$assignment = $assignmentDao->getAssignment($assignmentId);

			// Make sure the assignment was returned.
			if (isset($assignment)) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->msg = 'The new contract assignment was ' .
					'created successfully.';
				$json->assignment = $assignment;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to create the contract assignment.';
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
	 * Delete a contract assignment.
	 */
    public function deleteAction()
    {
		// Get the ids of the assignments to delete.
		$ids = $this->getInts('ids');

		// Determine if there are multiple assignments to delete.
		$mult = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			if (count($ids) > 0) {
				// Get the DAO.
				$assignmentDao = new ContractAssignmentDao();

				// Delete all the contract assignments.
				$count = $assignmentDao->removeAssignments($ids);

				// Make sure some employees were deleted.
				if (isset($count) && $count > 0) {
					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					if ($mult)
						$json->msg = 'The contract assignments were ' .
							'removed successfully.';
					else
						$json->msg = 'The contract assignment was ' .
							'removed successfully.';
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					if ($mult)
						$json->msg = 'Failed to delete the contract assignments.';
					else
						$json->msg = 'Failed to delete the contract assignment.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to delete - no assignment ids provided.';
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
	 * Update a contract assignment.
	 */
    public function updateAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get the id.
			$id = $this->getInt('id');

			// Create an array of the fields that represent the assignment.
			$data = array(
				'contract_id'    => $this->getStr('contract_id'),
				'employee_id'    => $this->getStr('employee_id'),
				'labor_cat'      => $this->getStr('labor_cat'),
				'item_name'      => $this->getStr('item_name'),
				'start'          => $this->getDate('start'),
				'end'            => $this->getDate('end')
			);

			// Make sure the necessary ids are set.
			if (isset($id)) {
				// Get the DAO.
				$assignmentDao = new ContractAssignmentDao();

				// Save the new values.
				$assignmentDao->saveAssignment($id, $data);

				// Retrieve the updated employee.
				$assignment = $assignmentDao->getAssignment($id);

				// Make sure the contract assignment was returned.
				if (isset($assignment)) {
					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'The contract assignment was ' .
						'updated successfully.';
					$json->assignment = $assignment;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to update the contract assignment.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'The contract and employee ids of the ' .
					'assignment to modify must be specified.';
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

