<?php

class LoginController extends BaseController
{
	/**
	 * Log into the application.
	 */
	function loginAction()
	{
		// Set the title for this action.
		$this->view->title = "Login";

		// Wrap the whole thing in a try/catch.
		try {
			// Get a login form.
			$form = new LoginForm();

			// Check to see if this is an invalid form submission.
			if (! $this->getRequest()->isPost() || ! $form->isValid($_POST)) {
				$this->view->loginForm = $form;
				return;
			}

			// Get the form values.
			$values = $form->getValues();

			// Get a new authentication adapter.
			$adapter = new AppAuthAdapter(
					$values['login'], $values['password']);

			// Perform the authentication using the adapter.
			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($adapter);

			// Save the identity in the session.
			$session = new Zend_Session_Namespace('Web');
			$session->employee = $auth->getIdentity();

			// Make sure the result is valid.
			if (! $result->isValid()) {
				// Authentication failed.
				$this->view->failedAuthentication = true;
				$this->view->loginForm = $form;
			} else {
				// Authentication succeeded. Determine where to go.
				$this->_helper->redirector('index', 'timesheet', 'user');

				// Save the employee to the view.
				$this->view->employee = $session->employee;
			}
		} catch (Zend_Exception $ex) {
			// Log the error.
			Logger::getLogger()->debug($ex->getMessage());

			// Authentication failed.
			$this->view->failedAuthentication = true;
			$this->view->loginForm = $form;
		}
	}

	/**
	 * Forgot password.
	 */
	function forgotAction()
	{
		// Wrap the whole thing in a try/catch.
		try {
			// Get the login name.
			$login = $this->getStr('login');

			// Make sure the login is valid.
			if (isset($login)) {
				// Get the DAO used to retrieve employee info.
				$employeeDao = new EmployeeDao();

				// Get the employee attempting to log in.
				$employee = $employeeDao->getEmployeeByLogin($login);

				// Make sure the employee was found.
				if (isset($employee)) {
					// Make sure the employee has an email address.
					if (isset($employee->email)) {
						// The new password.
						$password = $this->generatePassword();

						// Log the password.
						Logger::getLogger()->debug(
								"Resetting password for $login: $password");

						// Get the mail configuration.
						$config = Bootstrap::$registry->config->mail;

						// Create the login info.
						$mailconfig = array('auth' => 'login',
											'port' => $config->port,
											'username' => $config->user,
											'password' => $config->pass);

						// Create the transport.
						$transport = new Zend_Mail_Transport_Smtp(
								$config->host, $mailconfig);

						$mail = new Zend_Mail();
						$mail->setBodyText("\nForgot Password Request:\n\n" .
								"Your company timesheet system web site received a request \n" .
								"indicating your account password was forgotten and should \n" .
								"be reset. If you did not make this request, please notify the\n" .
								"web site administrator.\n\n" .
								"Here is your new login information:\n" .
								"      Login:    $login\n" .
								"      Password: $password\n\n" .
								"Once you login, you can change your password by viewing\n" .
								"your profile information.\n")
							 ->setFrom($config->from, $config->name)
							 ->addTo($employee->email, $employee->full_name)
							 ->setSubject('Timesheet System - Password Reset')
							 ->send($transport);

						// Create the JSON object to return.
						$json = new stdClass();
						$json->success = true;
						$json->msg = 'An email with a new random password was sent ' .
							'to the email address associated with your account. ' .
							'Please check your email for your updated login info. ' .
							'If you have any problems, please contact the web site ' .
							'administrator.';

						// Set a random password on the user account.
						$employee->hashed_pass = hash('SHA512', $password);

						// Turn the employee info into an array.
						$data = array(
							'id'             => $employee->id,
							'login'          => $employee->login,
							'hashed_pass'    => $employee->hashed_pass,
							'email'          => $employee->email,
							'first_name'     => $employee->first_name,
							'last_name'      => $employee->last_name,
							'suffix'         => $employee->suffix,
							'division'       => $employee->division,
							'personnel_type' => $employee->personnel_type,
							'active'         => $employee->active
						);

						// Save the updated employee data.
						$employeeDao->save($employee->id, $data);
					} else {
						// No email address on file.
						$json = new stdClass();
						$json->success = false;
						$json->msg = 'No email address is specified within your ' .
							'profile information, so your password was not reset. ' .
							'Please contact the web site administrator for your new password.';
					}
				} else {
					// No user account found.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'No user account was found with the specified ' .
						'login or email address. Please specify the correct ' .
						'user information before requesting a password reset.';
				}

			} else {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'A login or email address must be provided ' .
					'when requesting a password reset.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON response.
		$this->_helper->json($json);
	}

	/**
	 * Log out of the application.
	 */
	function logoutAction()
	{
		// Clear the user identify from the auth session.
        Zend_Auth::getInstance()->clearIdentity();

		// Clear the user from the session.
		$session = new Zend_Session_Namespace('Web');
		$session->employee = null;
		$session->payPeriod = null;

		// Go to the home page.
		$this->_forward('index', 'index');
	}

	/**
	 * Generate a random password.
	 *
	 * @param length The length of the generated password.
	 *
	 * @return Returns the new generated password.
	 */
	private function generatePassword($length = 8)
	{
		// The valid vowels.
		$vowels = 'aeuAEU';

		// The valid numbers.
		$numbers = '23456789';

		// The valid consonants.
		$consonants = 'bdghjmnpqrstvzBDGHJLMNPQRSTVWXZ';

		// This will hold the password.
		$password = '';

		// Get random values and add to the password.
		for ($i = 0; $i < $length; $i++) {
			$alt = rand(0, 2);
			if ($alt == 0) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else if ($alt == 1) {
				$password .= $numbers[(rand() % strlen($numbers))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}

		// Return the password.
		return $password;
	}
}

