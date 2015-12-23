<?php

class BaseController extends Zend_Controller_Action
{
	/**
	 * This is the user's session.
	 */
	protected static $cachedSession = null;

	/**
	 * Initialize the user session information.
	 */
	function init()
	{
		try {
			// Make sure the session has been created.
			if (!isset(self::$cachedSession))
				self::$cachedSession = new Zend_Session_Namespace('Web');
		} catch (Zend_Session_Exception $ex) {
			// Log the error.
			Logger::getLogger()->debug(
					"Zend_Session_Exception" . $ex->getMessage());
		}

		// Get the session.
		$session = self::$cachedSession;

		// Fix the request.
		self::fixRequest($this->getRequest());

		// Get the request URI.
		$requestUri = $this->getRequest()->getRequestUri();

		// Write a log of this page request.
		if (isset($session->employee))
			Logger::getLogger()->info("Request [" . $session->employee->login .
					"]: " . $requestUri);
		else
			Logger::getLogger()->info("Request [guest]: " . $requestUri);

		// Wrap the whole thing in a try/catch.
		try {
			// Check to see if the user is trying to access an admin page.
			if (preg_match("/^\/admin/i", $requestUri)) {
				// Make sure the employee is an administrator.
				if (! isset($session->employee) || ! $session->employee->admin)
					// Redirect to the home page.
					$this->_helper->redirector('index', 'index', 'default');
			}

			// Check to see if the user is trying to access a manager page.
			else if (preg_match("/^\/manager/i", $requestUri)) {
				// Make sure the employee is a manager.
				if (! isset($session->employee) || ! $session->employee->manager)
					// Redirect to the home page.
					$this->_helper->redirector('index', 'index', 'default');
				else
					// Set the manage layout.
					$this->_helper->layout->setLayout('manage');
			}

			// Check to see if the user is trying to access a payroll page.
			else if (preg_match("/^\/payroll/i", $requestUri)) {
				// Make sure the employee is in payroll.
				if (! isset($session->employee) || ! $session->employee->payroll)
					// Redirect to the home page.
					$this->_helper->redirector('index', 'index', 'default');
				else
					// Set the manage layout.
					$this->_helper->layout->setLayout('manage');
			}

			// Check to see if the user is trying to access a supervisor page.
			else if (preg_match("/^\/supervisor/i", $requestUri)) {
				// Make sure the employee is a supervisor.
				if (! isset($session->employee) ||
						! $session->employee->supervisor)
					// Redirect to the home page.
					$this->_helper->redirector('index', 'index', 'default');
				else
					// Set the manage layout.
					$this->_helper->layout->setLayout('manage');
			}

			// Check to see if the user is trying to access a user page.
			else if (preg_match("/^\/user/i", $requestUri)) {
				// Make sure the employee is logged in.
				if (! isset($session->employee))
					// Redirect to the home page.
					$this->_helper->redirector('index', 'index', 'default');
			}

			// Set the pay period in the session.
			if (! isset($session->payPeriod)) {
				// Save the current pay period to the session.
				$payPeriodDao = new PayPeriodDao();
				$session->payPeriod = $payPeriodDao->getCurrent();

				// Make sure the pay period was found.
				if (!isset($session->payPeriod)) {
					// Make sure all the pay periods exist in the database,
					// then retrieve the current pay period.
					$session->payPeriod = $payPeriodDao->addThroughCurrent();
                }
			}
		} catch (Zend_Exception $ex) {
			// Log the exception.
			Logger::getLogger()->debug("Base Controller Error: " .
					$ex->getMessage());
		}

		// Save the pay period to the view.
		$this->view->payPeriod = $session->payPeriod;

		// Save the employee to the view.
		$this->view->employee = $session->employee;
	}

	/**
	 * Fix the request object to make sure the parts are valid.
	 */
	public static function fixRequest($request)
	{
		// Make sure a request is available.
		if (isset($request)) {
			// Get the values.
			$act = $request->getActionName();
			$ctl = $request->getControllerName();
			$mod = $request->getModuleName();

			// Remove any trailing . or - characters.
			if (preg_match("/.*(\.|-)/", $act))
				$request->setActionName(substr($act, 0, strlen($act)-1));
			if (preg_match("/.*(\.|-)/", $ctl))
				$request->setControllerName(substr($ctl, 0, strlen($ctl)-1));
			if (preg_match("/.*(\.|-)/", $mod))
				$request->setModuleName(substr($mod, 0, strlen($mod)-1));
		}
	}

	/**
	 * Retrieve an object parameter value.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter, or null if it was not set.
	 */
    public function getObj($param)
    {
		// Get and return the value.
		return $this->_getParam($param);
    }

	/**
	 * Retrieve a string parameter value.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter, or null if it was not set.
	 */
    public function getStr($param)
    {
		// Get the parameter value.
		$val = $this->_getParam($param);

		// Check to see if the value is set.
		if (isset($val)) {
			// Ext's HtmlEditor adds some weird characters for some reason.
			$val = str_replace("\xE2\x80\x8B", "", $val);

			// Trim the value.
			$val = trim('' . $val);
		}

		// If it is an empty string, return null.
		if (! isset($val) || $val === '')
			return null;

		// Return the value.
		return $val;
    }

	/**
	 * Retrieve a comma-delimited list of string parameter values.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter as an array of strings, or null
	 *         if it was not set.
	 */
    public function getStrs($param)
    {
		// Get the string value.
		$list = $this->getStr($param);

		// Make sure the value is set.
		if (! isset($list))
			return null;

		// Convert the comma-delimited list into an array of values.
		$vals = preg_split("/,/", $list);

		// This will hold the valid values.
		$valid = array();

		// Iterate over the values.
		foreach ($vals as $val) {
			// Get the trimmed value.
			$trimmed = trim($val);

			// Add the value to the list if it is valid.
			if (isset($trimmed) && strlen($trimmed) > 0)
				$valid[] = $trimmed;
		}

		// Return the array of valid values.
		return $valid;
    }

	/**
	 * Retrieve an integer parameter value.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter as an integer, or null if it was
	 *         not set.
	 */
    public function getInt($param)
    {
		// Get the string value.
		$val = $this->getStr($param);

		// If it is not set or non-numeric, return null.
		if (! isset($val) || ! is_numeric($val))
			return null;

		// Return the value as an integer.
		return (int) $val;
    }

	/**
	 * Retrieve a comma-delimited list of integer parameter values.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter as an array of integers, or null
	 *         if it was not set.
	 */
    public function getInts($param)
    {
		// Get the string value.
		$list = $this->getStr($param);

		// Make sure the value is set.
		if (! isset($list))
			return null;

		// Convert the comma-delimited list into an array of values.
		$vals = preg_split("/,/", $list);

		// This will hold the valid integer values.
		$ints = array();

		// Iterate over the values.
		foreach ($vals as $val) {
			// Get the trimmed value.
			$trimmed = trim($val);

			// Add the integer to the list if it is valid.
			if (is_numeric($trimmed))
				$ints[] = (int) $trimmed;
		}

		// Return the array of valid values.
		return $ints;
    }

	/**
	 * Retrieve a boolean parameter value.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter as a boolean, or null if it was
	 *         not set.
	 */
    public function getBool($param)
    {
		// Get the string value.
		$val = $this->getStr($param);

		// Return false if it was not set.
		if (! isset($val))
			return 0;

		// Lowercase the value.
		$val = strtolower($val);

		// Check for common boolean values.
		if ($val == 'yes' || $val == '1' || $val == 'true' || $val == 'on')
			return 1;
		if ($val == 'no' || $val == '0' || $val == 'false' || $val == 'off')
			return 0;

		// Unknown boolean value, so return false.
		return 0;
    }

	/**
	 * Retrieve a date parameter value.
	 *
	 * @param param The name of the parameter to retrieve.
	 *
	 * @return Returns the requested parameter formatted as a date
	 *         (yyyy-MM-dd), or null if it was not set or invalid.
	 */
    public function getDate($param)
    {
		// Get the string value.
		$val = $this->getStr($param);

		// Return null if the value is not set.
		if (! isset($val))
			return null;

		// Convert the value into a time.
		$time = strtotime($val);

		// Make sure the time was valid.
		if ($time == 0)
			return null;

		// Return the time value as a date.
		return date('Y-m-d', $time);
    }
}

