<?php

class User_ProfileController extends BaseController
{
	/**
	 * Go show the profile page.
	 */
	function indexAction()
	{
		// Set the page title.
		$this->view->title = "User Profile";

		// Set the profile layout for this action.
		$this->_helper->layout->setLayout('profile');
	}

	/**
	 * Update the employee's profile information.
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
				'email'          => $this->getStr('email')
			);

			// Set the hashed password value if necessary.
			$password = $this->getStr('password');
			if (isset($password))
				$data['hashed_pass'] = hash('SHA512', $password);

			// Make sure the id is set.
			if (isset($me) && isset($me->id) && is_numeric($me->id)) {
				// Get the DAO.
				$employeeDao = new EmployeeDao();

				// Save the new values.
				$employeeDao->save($me->id, $data);

				// Retrieve the updated employee.
				$employee = $employeeDao->get($me->id);

				// Make sure the employee was returned.
				if (isset($employee)) {
					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'Your profile was updated successfully.';
					$json->employee = $employee;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to update your profile.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Unable to find your profile.';
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

