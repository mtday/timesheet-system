<?php

// Identify the root of the zendapp system.
$root = dirname(__FILE__) . "/../../zendapp"; // (The zendapp directory)

// Set the include path.
set_include_path(
    $root . '/application'                                  . PATH_SEPARATOR .
    $root . '/application/daos'                             . PATH_SEPARATOR .
    $root . '/application/util'                             . PATH_SEPARATOR .
    $root . '/application/modules/admin/controllers'        . PATH_SEPARATOR .
    $root . '/application/modules/admin/forms'              . PATH_SEPARATOR .
    $root . '/application/modules/admin/views/helpers'      . PATH_SEPARATOR .
    $root . '/application/modules/payroll/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/payroll/views/helpers'    . PATH_SEPARATOR .
    $root . '/application/modules/manager/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/manager/views/helpers'    . PATH_SEPARATOR .
    $root . '/application/modules/supervisor/controllers'   . PATH_SEPARATOR .
    $root . '/application/modules/supervisor/views/helpers' . PATH_SEPARATOR .
    $root . '/application/modules/user/controllers'         . PATH_SEPARATOR .
    $root . '/application/modules/user/views/helpers'       . PATH_SEPARATOR .
    $root . '/application/modules/default/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/default/forms'            . PATH_SEPARATOR .
    $root . '/application/modules/default/views/helpers'    . PATH_SEPARATOR .
    $root . '/library'                                      . PATH_SEPARATOR .
    get_include_path()
);

// Perform all system initialization via Bootstrap.
require_once 'Bootstrap.php';
Bootstrap::prepare();

Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

// Load the authentication plugin.
require_once('AuthPlugin.php');
 
// Define the plugin class.
class MilestoneAuthPlugin extends AuthPlugin
{
	// Check to see if the user exists.
    public function userExists($login)
    {
		// Get an instance of our employee DAO.
		$employeeDao = new EmployeeDao();

		// Attempt to retrieve the user.
		$employee = $employeeDao->getByLogin($login);

		// Return whether the employee was found.
        return isset($employee);
    }
 
	// Authenticate a user and password.
    public function authenticate($login, $password)
    {
		// Get an instance of our employee DAO.
		$employeeDao = new EmployeeDao();

		// Attempt to retrieve the user based on login and password.
		$employee = $employeeDao->getAuthEmployee($login, $password);

		// Make sure the employee was found.
        if (! isset($employee))
			return false;

		// Make sure the employee has the wiki role.
		return isset($employee->wiki) && $employee->wiki;
    }
 
	// Update the specified user information.
    public function updateUser(&$user)
    {
		// Get an instance of our employee DAO.
		$employeeDao = new EmployeeDao();

		// Attempt to retrieve the user based on login.
		$employee = $employeeDao->getByLogin($user->getName());

		// Make sure the employee was found.
        if (! isset($employee))
			return false;

		// Update the user object.
		$user->setRealName($employee->full_name);
		$user->setEmail($employee->email);
		$user->confirmEmail();

		// Everything looks good.
        return true;
    }
 
	// Update the UI template.
    public function modifyUITemplate(&$template, &$type)
    {
        $template->set('usedomain', false);
        $template->set('useemail', false);
        $template->set('create', false);
    }
 
	// Do not automatically create users.
    public function autoCreate()
    {
        return true;
    }
 
 
	// We don't care about domains.
    public function validDomain($domain)
    {
        return true;
    }
 
	// We don't allow password changes from the wiki.
    public function allowPasswordChange()
    {
        return false;
    }
 
	// We don't do password changes from the wiki.
    public function setPassword($user, $password)
    {
        return false;
    }
 
	// Update the external database with the user info.
    public function updateExternalDB($user)
    {
		// TODO: We might do this in the future.
        return false;
    }
 
	// No account creation available.
    public function canCreateAccounts()
    {
        return false;
    }
 
	// We don't do account creation from the wiki.
    public function addUser($user, $password, $email = '', $realname = '')
    {
        return false;
    }
 
	// Be strict about something...
    public function strict()
    {
        return true;
    }
 
	// Perform user initialization.
    public function initUser(&$user, $autoCreate = false)
	{
		// Nothing to do.
	}
}
 
 
$wgExtensionCredits['other'][] = array(
    'name' => 'MilestoneAuthPlugin',
    'version' => '1.0.0',
    'author' => 'Mike Day (mike.day@milestoneintellignece.com)',
    'description' => 'Login via the Milestone employee database.'
);

