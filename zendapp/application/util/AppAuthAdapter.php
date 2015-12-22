<?php

class AppAuthAdapter implements Zend_Auth_Adapter_Interface
{
	/**
	 * The login to authenticate.
	 */
	protected $login = null;

	/**
	 * The password to authenticate.
	 */
	protected $password = null;

    /**
     * Sets login and password for authentication.
     */
    public function __construct($login, $password)
    {
        // Save the provided parameters.
		$this->login = $login;
		$this->password = $password;
    }

    /**
     * Performs an authentication attempt for a user.
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        // Get the DAO used to retrieve employee info.
		$employeeDao = new EmployeeDao();

		// Get the employee attempting to log in.
		$employee = $employeeDao->getAuthEmployee(
				$this->login, $this->password);

		// Check to see if an employee was found.
		if ($employee != null) {
			// Log what we are doing.
			Logger::getLogger()->info(
					"Authentication successful: " . $this->login);

			// Return the result.
			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $employee);
		}

		// Log what we are doing.
		Logger::getLogger()->warn("Authentication failed: " . $this->login);

		// On failure, return a bad result.
		return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null,
				array('Invalid login or password.'));
    }
}

