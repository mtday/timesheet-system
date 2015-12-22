<?php

class Manager_ContractController extends BaseController
{
	/**
	 * Go show the contract management home page.
	 */
	function indexAction()
	{
		// Set the page title.
		$this->view->title = "Contract Management";

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the contract management scripts on the page.
		$this->view->scripts = "contract";
	}

	/**
	 * Add a contract.
	 */
    public function addAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the contract.
			$data = array(
				'contract_num' => $this->getStr('contract_num'),
				'description'  => $this->getStr('description'),
				'job_code'     => $this->getStr('job_code'),
				'admin'        => $this->getBool('admin'),
				'active'       => $this->getBool('active')
			);

			// Get the DAO.
			$contractDao = new ContractDao();

			// Add the contract.
			$id = $contractDao->add($data);

			// Retrieve the new contract.
			$contract = $contractDao->get($id);

			// Make sure the contract was returned.
			if (isset($contract)) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->msg = 'The contract was created successfully.';
				$json->contract = $contract;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to create the contract.';
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
	 * Activate a contract.
	 */
    public function activateAction()
    {
		// Get the ids of the contracts to activate.
		$ids = $this->getInts('ids');

		// Determine if there are multiple contracts to activate.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$contractDao = new ContractDao();

			// Activate all the contracts.
			$count = $contractDao->activate($ids);

			// Make sure some contracts were activated.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The contracts were activated successfully.';
				else
					$json->msg = 'The contract was activated successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to activate the contracts.';
				else
					$json->msg = 'Failed to activate the contract.';
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
	 * Deactivate a contract.
	 */
    public function deactivateAction()
    {
		// Get the ids of the contracts to deactivate.
		$ids = $this->getInts('ids');

		// Determine if there are multiple contracts to deactivate.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$contractDao = new ContractDao();

			// Deactivate all the contracts.
			$count = $contractDao->deactivate($ids);

			// Make sure some contracts were deactivated.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The contracts were deactivated successfully.';
				else
					$json->msg = 'The contract was deactivated successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to deactivate the contracts.';
				else
					$json->msg = 'Failed to deactivate the contract.';
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
	 * Delete a contract.
	 */
    public function deleteAction()
    {
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		if (!$session->employee->admin)
			throw new Exception("Only administrators can delete contracts.");

		// Get the ids of the contracts to delete.
		$ids = $this->getInts('ids');

		// Determine if there are multiple contracts to delete.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$contractDao = new ContractDao();

			// Delete all the contracts.
			$count = $contractDao->remove($ids);

			// Make sure some contracts were deleted.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The contracts were removed successfully.';
				else
					$json->msg = 'The contract was removed successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to delete the contracts.';
				else
					$json->msg = 'Failed to delete the contract.';
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
	 * Update a contract.
	 */
    public function updateAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the contract.
			$data = array(
				'contract_num' => $this->getStr('contract_num'),
				'description'  => $this->getStr('description'),
				'job_code'     => $this->getStr('job_code'),
				'admin'        => $this->getBool('admin'),
				'active'       => $this->getBool('active')
			);

			// Get the id of the contract to modify.
			$id = $this->getInt('id');

			// Make sure the id is set.
			if (isset($id)) {
				// Update the contract.
				$contractDao = new ContractDao();

				// Save the new values.
				$contractDao->save($id, $data);

				// Retrieve the updated contract.
				$contract = $contractDao->get($id);

				// Make sure the contract was returned.
				if (isset($contract)) {
					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'The contract was updated successfully.';
					$json->contract = $contract;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to update the contract.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'The id of the contract to modify must ' .
					'be specified.';
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
	 * Retrieve the JSON for all the contracts.
	 */
    public function jsonAction()
    {
		// Determine if we should include administrative contracts.
		$regularOnly = $this->getBool('regularOnly');

		// Wrap the whole thing in a try/catch.
		try {
			// Get all the contracts.
			$contractDao = new ContractDao();
			$contracts = $contractDao->getAll($regularOnly);

			// Create the JSON object to return.
			$json = new stdClass();
			$json->success = true;
			$json->contracts = $contracts;
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->contracts = array();
		}

		// Return all the contracts as JSON.
		$this->_helper->json($json);
	}

	/**
	 * Retrieve the JSON for all the contracts assigned to an employee as of a
	 * specific day.
	 */
    public function employeeAction()
    {
		// Get the id of the employee for which contracts are to be retrieved.
		$id = $this->getInt('id');

		// Get the day for which contract data is to be retrieved.
		$day = $this->getDate('day');

		// Determine whether administrative contracts should be retrieved.
		$regularOnly = $this->getBool('regularOnly');

		// Wrap the whole thing in a try/catch.
		try {
			// Check to see if the provided employee id is valid.
			if (isset($id) && is_numeric($id)) {
				// Used to retrieve the necessary pay period.
				$payPeriodDao = new PayPeriodDao();

				// Used to retrieve the contracts.
				$contractDao = new ContractDao();

				// Get the contracts for the specified employee.
				$contracts = $contractDao->getEmployeeContracts(
						$id, $day, $regularOnly);

				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->msg = 'The assigned contracts were ' .
					'retrieved successfully.';
				$json->contracts = $contracts;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'A valid employee id must be specified.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->employees = array();
		}

		// Return all the contracts as JSON.
		$this->_helper->json($json);
	}
}

