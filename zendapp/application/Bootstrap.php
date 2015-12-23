<?php

require_once 'Zend/Loader/Autoloader.php';

class Bootstrap
{
	/**
	 * The internal instance of the front controller.
	 */
	public static $frontController = null;

	/**
	 * Keep track of the root application directory.
	 */
	public static $root = '';

	/**
	 * Keep an instance of the registry.
	 */
	public static $registry = null;

	/**
	 * Begin dispatching Zend events.
	 */
	public static function run()
	{
		// Prepare the system for processing.
		self::prepare();

		// Let the front controller dispatch the event.
		$response = self::$frontController->dispatch();

		// Send the response to the client.
		self::sendResponse($response);
	}

	/**
	 * Prepare the system for processing.
	 */
	public static function prepare()
	{
		self::setupEnvironment();
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);
		self::setupRegistry();
		self::setupConfiguration();
		self::setupFrontController();
		self::setupRoutes();
		self::setupView();
		self::setupDatabase();
	}

	/**
	 * Setup the application environment.
	 */
	public static function setupEnvironment()
	{
		// Turn on error reporting.
		error_reporting(E_ALL | E_STRICT);

		// Set the default time zone.
		date_default_timezone_set('America/New_York');

		// Set the application root directory.
		self::$root = dirname(dirname(__FILE__));
	}

	/**
	 * Configure the front controller.
	 */
	public static function setupFrontController()
	{
		// Get an instance.
		self::$frontController = Zend_Controller_Front::getInstance();

		// Return a response object after dispatch.
		self::$frontController->returnResponse(true);

		// Set the directories that containing controllers.
		self::$frontController->setControllerDirectory(array(
			'default'    => self::$root .
				'/application/modules/default/controllers',
			'admin'      => self::$root .
				'/application/modules/admin/controllers',
			'manager'    => self::$root .
				'/application/modules/manager/controllers',
			'payroll'    => self::$root .
				'/application/modules/payroll/controllers',
			'supervisor' => self::$root .
				'/application/modules/supervisor/controllers',
			'user'       => self::$root .
				'/application/modules/user/controllers'
		));

		// Save the registry as a parameter in the front controller.
		self::$frontController->setParam('registry', self::$registry);
	}

	/**
	 * Configure the additional routes for the application.
	 */
	public static function setupRoutes()
	{
		// Get the router for the front controller.
		$router = self::$frontController->getRouter();

		// Add the view timesheet route.
		$router->addRoute('viewTimesheet',
			new Zend_Controller_Router_Route('user/timesheet/view/:date',
				array('module' => 'user', 'controller' => 'timesheet',
					'action' => 'view')));

		// Add the previous timesheet route.
		$router->addRoute('previousTimesheet',
			new Zend_Controller_Router_Route('user/timesheet/prev/:currstart',
				array('module' => 'user', 'controller' => 'timesheet',
					'action' => 'prev')));

		// Add the next timesheet route.
		$router->addRoute('nextTimesheet',
			new Zend_Controller_Router_Route('user/timesheet/next/:currstart',
				array('module' => 'user', 'controller' => 'timesheet',
					'action' => 'next')));
	}

	/**
	 * Configure the view.
	 */
	public static function setupView()
	{
		// Create a new instance.
		$view = new Zend_View();

		// Set the encoding.
		$view->setEncoding('UTF-8');

		// Get a renderer and add it as a helper.
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

		// Set the layout.
		Zend_Layout::startMvc(array(
			'layoutPath' => array(
				self::$root . '/application/modules/default/views/layouts',
				self::$root . '/application/modules/user/views/layouts'
			),
			'layout' => 'default'
		));
	}

	/**
	 * Used to send the server response to the client.
	 */
	public static function sendResponse(Zend_Controller_Response_Http $response)
	{
		// Set the response headers.
		$response->setHeader('Content-Type', 'text/html; charset=UTF-8', true);

		// Send the response to the client.
		$response->sendResponse();
	}

	/**
	 * Setup the application registry.
	 */
	public static function setupRegistry()
	{
		// Create an instance of the registry.
		self::$registry = new Zend_Registry(
				array(), ArrayObject::ARRAY_AS_PROPS);

		// Set the default instance.
		Zend_Registry::setInstance(self::$registry);
	}

	/**
	 * Setup the application configuration.
	 */
	public static function setupConfiguration()
	{
		// Load the configuration.
		$config = new Zend_Config_Ini(
			self::$root . '/config/config.ini',
			'general'
		);

		// Save the configuration in the registry.
		self::$registry->config = $config;
	}

	/**
	 * Setup the application database.
	 */
	public static function setupDatabase()
	{
		// Retrieve the configuration from the registry.
		$config = self::$registry->config;

		// Let the DB factory create an instance of the database.
		$db = Zend_Db::factory($config->db->adapter,
				$config->db->toArray());

		// Configure for UTF-8.
		$db->query("SET NAMES 'utf8'");

		// Store the database in the registry.
		self::$registry->database = $db;

		// Set the default adapter.
		Zend_Db_Table::setDefaultAdapter($db);
	}
}

