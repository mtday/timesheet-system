<?php

class Manager_EmployeeController extends BaseController
{
	/**
	 * Go show the employee management home page.
	 */
	function indexAction()
	{
		// Set the page title.
		$this->view->title = "Employee Management";

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the employee management scripts on the page.
		$this->view->scripts = "employee";
	}

	/**
	 * Add a employee.
	 */
    public function addAction()
    {
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the employee.
			$data = array(
				'first_name'     => $this->getStr('first_name'),
				'last_name'      => $this->getStr('last_name'),
				'suffix'         => $this->getStr('suffix'),
				'login'          => $this->getStr('login'),
				'email'          => $this->getStr('email'),
				'division'       => $this->getStr('division'),
				'personnel_type' => $this->getStr('personnel_type'),
				'active'         => $this->getBool('active')
			);

			// Set the hashed password value if necessary.
			$password = $this->getStr('password');
			$data['hashed_pass'] = hash('SHA512', $password);

			// Collect the privileges for this user.
			$privileges = array();
			if ($this->getBool('admin') && $me->admin)
				$privileges[] = 'admin';
			if ($this->getBool('payroll') && ($me->payroll || $me->admin))
				$privileges[] = 'payroll';
			if ($this->getBool('manager') && ($me->manager || $me->admin))
				$privileges[] = 'manager';
			if ($this->getBool('security') && ($me->security || $me->admin))
				$privileges[] = 'security';
			if ($this->getBool('wiki') && ($me->wiki || $me->admin))
				$privileges[] = 'wiki';

			// Get the DAO.
			$employeeDao = new EmployeeDao();

			// Check to see if the login already exists.
			$exists = $employeeDao->getEmployeeByLogin($data['login']);

			// Check to see if the requested login already exists.
			if (isset($exists)) {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to create the employee - an account ' .
					'with the specified login already exists.';
			} else {
				// Add the employee.
				$id = $employeeDao->add($data);

				// Retrieve the new employee.
				$employee = $employeeDao->get($id);

				// Make sure the employee was returned.
				if (isset($employee)) {
					// Check to see if privileges need to be added.
					if (count($privileges) > 0) {
						// Get the RoleDao.
						$roleDao = new RoleDao();

						// Create the roles for this user.
						foreach ($privileges as $priv)
							// Add this role.
							$roleDao->add(array(
								'name'        => $priv,
								'employee_id' => $employee->id
							));
					}

					// Get the primary supervisor id.
					$supervisor = $this->getInt('supervisor');

					// Make sure the supervisor is valid.
					if (isset($supervisor) && is_numeric($supervisor)) {
						// Get the DAO.
						$supervisorDao = new SupervisorDao();

						// Add the primary supervisor.
						$supervisorDao->add(array(
							'employee_id'   => $employee->id,
							'supervisor_id' => $supervisor,
							'primary'       => true
						));
					}

					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'The employee was created successfully.';
					$json->employee = $employee;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to create the employee.';
				}
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
	 * Activate a employee.
	 */
    public function activateAction()
    {
		// Get the ids of the employees to activate.
		$ids = $this->getInts('ids');

		// Determine if there are multiple employees to activate.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$employeeDao = new EmployeeDao();

			// Activate all the employees.
			$count = $employeeDao->activate($ids);

			// Make sure some employees were activated.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The employees were activated successfully.';
				else
					$json->msg = 'The employee was activated successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to activate the employees.';
				else
					$json->msg = 'Failed to activate the employee.';
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
	 * Deactivate a employee.
	 */
    public function deactivateAction()
    {
		// Get the ids of the employees to deactivate.
		$ids = $this->getInts('ids');

		// Determine if there are multiple employees to deactivate.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$employeeDao = new EmployeeDao();

			// Deactivate all the employees.
			$count = $employeeDao->deactivate($ids);

			// Make sure some employees were deactivated.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The employees were deactivated successfully.';
				else
					$json->msg = 'The employee was deactivated successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to deactivate the employees.';
				else
					$json->msg = 'Failed to deactivate the employee.';
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
	 * Delete a employee.
	 */
    public function deleteAction()
    {
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		if (!$session->employee->admin)
			throw new Exception("Only administrators can delete employees.");

		// Get the ids of the employees to delete.
		$ids = $this->getInts('ids');

		// Determine if there are multiple employees to delete.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$employeeDao = new EmployeeDao();

			// Delete all the employees.
			$count = $employeeDao->remove($ids);

			// Make sure some employees were deleted.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The employees were removed successfully.';
				else
					$json->msg = 'The employee was removed successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to delete the employees.';
				else
					$json->msg = 'Failed to delete the employee.';
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
	 * Update a employee.
	 */
    public function updateAction()
    {
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the current user.
		$me = $session->employee;

		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the employee.
			$data = array(
				'first_name'     => $this->getStr('first_name'),
				'last_name'      => $this->getStr('last_name'),
				'suffix'         => $this->getStr('suffix'),
				'login'          => $this->getStr('login'),
				'email'          => $this->getStr('email'),
				'division'       => $this->getStr('division'),
				'personnel_type' => $this->getStr('personnel_type'),
				'active'         => $this->getBool('active')
			);

			// Set the hashed password value if necessary.
			$password = $this->getStr('password');
			if (isset($password))
				$data['hashed_pass'] = hash('SHA512', $password);

			// Collect the privileges for this user.
			$privileges = array();
			if ($this->getBool('admin') && $me->admin)
				$privileges[] = 'admin';
			if ($this->getBool('payroll') && ($me->payroll || $me->admin))
				$privileges[] = 'payroll';
			if ($this->getBool('manager') && ($me->manager || $me->admin))
				$privileges[] = 'manager';
			if ($this->getBool('security') && ($me->security || $me->admin))
				$privileges[] = 'security';
			if ($this->getBool('wiki') && ($me->wiki || $me->admin))
				$privileges[] = 'wiki';

			// Get the id of the employee to modify.
			$id = $this->getInt('id');

			// Make sure the id is set.
			if (isset($id)) {
				// Get the DAO.
				$employeeDao = new EmployeeDao();

				// Save the new values.
				$employeeDao->save($id, $data);

				// Retrieve the updated employee.
				$employee = $employeeDao->get($id);

				// Make sure the employee was returned.
				if (isset($employee)) {
					// Get the RoleDao.
					$roleDao = new RoleDao();

					// Remove any existing privileges for this employee.
					$roleDao->removeForEmployee($employee->id);

					// Check to see if privileges need to be added.
					if (count($privileges) > 0)
						// Create the roles for this user.
						foreach ($privileges as $priv)
							// Add this role.
							$roleDao->add(array(
								'name'        => $priv,
								'employee_id' => $employee->id
							));

					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'The employee was updated successfully.';
					$json->employee = $employee;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to update the employee.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'The id of the employee to modify must ' .
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
	 * Retrieve the JSON for all the employees.
	 */
    public function jsonAction()
    {
		// Determine if we should retrieve only active employees.
		$activeOnly = $this->getBool('activeOnly');

		// Wrap the whole thing in a try/catch.
		try {
			// Get all the employees.
			$employeeDao = new EmployeeDao();
			$employees = $employeeDao->getAll($activeOnly);

			// Create the JSON object to return.
			$json = new stdClass();
			$json->success = true;
			$json->employees = $employees;
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->employees = array();
		}

		// Return all the employees as JSON.
		$this->_helper->json($json);
	}

	/**
	 * Retrieve the JSON for all the employees assigned to a contract as of a
	 * specific day.
	 */
    public function contractAction()
    {
		// Get the id of the contract for which employees are to be retrieved.
		$id = $this->getInt('id');

		// Get the day for which employee data is to be retrieved.
		$day = $this->getDate('day');

		// Wrap the whole thing in a try/catch.
		try {
			// Check to see if the provided contract id is valid.
			if (isset($id) && is_numeric($id)) {
				// Used to retrieve the necessary pay period.
				$payPeriodDao = new PayPeriodDao();

				// Used to retrieve the employees.
				$employeeDao = new EmployeeDao();

				// Get the employees for the specified contract.
				$employees = $employeeDao->getContractEmployees(
						$id, $day);

				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->msg = 'The assigned employees were ' .
					'retrieved successfully.';
				$json->employees = $employees;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'A valid contract id must be specified.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->employees = array();
		}

		// Return all the employees as JSON.
		$this->_helper->json($json);
	}
}

