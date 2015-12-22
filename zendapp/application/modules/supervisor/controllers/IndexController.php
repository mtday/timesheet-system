<?php

class Supervisor_IndexController extends BaseController
{
	/**
	 * Go show the supervisor home page.
	 */
	function indexAction()
	{
		// Set the page title.
		$this->view->title = "Supervisor Management";

		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Check the session for the active pay period.
		if (! isset($session->activePayPeriod)) {
			// Retrieve the current pay period and save it to the view.
			$payPeriodDao = new PayPeriodDao();
			$this->view->payPeriod = $payPeriodDao->getCurrent();
		} else
			// Use the pay period stored in the session.
			$this->view->payPeriod = $session->activePayPeriod;

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the supervisor management scripts on the page.
		$this->view->scripts = "supervisor";
	}

	/**
	 * Go show the supervisor home page for the specified pay period.
	 */
	function viewAction()
	{
		// Set the page title.
		$this->view->title = "Supervisor Management";

		// Get the requested date.
		$day = $this->getDate('day');

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$this->view->payPeriod = $payPeriodDao->getContaining($day);

		// Save the active pay period in the session.
		$session = new Zend_Session_Namespace('Web');
		$session->activePayPeriod = $this->view->payPeriod;

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the supervisor management scripts on the page.
		$this->view->scripts = "supervisor";

		// Use the index view.
		$this->render('index');
	}

	/**
	 * Retrieve the next pay period and go back to the supervisor page.
	 */
	function nextAction()
	{
		// Set the page title.
		$this->view->title = "Supervisor Management";

		// Get the requested date.
		$day = $this->getDate('day');

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

		// Get the next pay period.
		$next = PayPeriodHelper::getNext($payPeriod);

		// Attempt to retrieve the next pay period from the database.
		$realnext = $payPeriodDao->get($next->start);

		// Make sure it was found.
		if (!isset($realnext)) {
			// Create the new pay period.
			$payPeriodDao->add(array(
				'start' => $next->start,
				'end'   => $next->end,
				'type'  => $next->type
			));
			$realnext = $next;
		}

		// Get the next pay period.
		$this->view->payPeriod = $realnext;

		// Save the active pay period in the session.
		$session = new Zend_Session_Namespace('Web');
		$session->activePayPeriod = $this->view->payPeriod;

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the supervisor management scripts on the page.
		$this->view->scripts = "supervisor";

		// Use the index view.
		$this->render('index');
	}

	/**
	 * Retrieve the previous pay period and go back to the supervisor page.
	 */
	function prevAction()
	{
		// Set the page title.
		$this->view->title = "Supervisor Management";

		// Get the requested date.
		$day = $this->getDate('day');

		// Retrieve the current pay period and save it to the view.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

		// Get the previous pay period.
		$prev = PayPeriodHelper::getPrev($payPeriod);

		// Attempt to retrieve the previous pay period from the database.
		$realprev = $payPeriodDao->get($prev->start);

		// Make sure it was found.
		if (!isset($realprev)) {
			// Create the new pay period.
			$payPeriodDao->add(array(
				'start' => $prev->start,
				'end'   => $prev->end,
				'type'  => $prev->type
			));
			$realprev = $prev;
		}

		// Get the previous pay period.
		$this->view->payPeriod = $realprev;

		// Save the active pay period in the session.
		$session = new Zend_Session_Namespace('Web');
		$session->activePayPeriod = $this->view->payPeriod;

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the supervisor management scripts on the page.
		$this->view->scripts = "supervisor";

		// Use the index view.
		$this->render('index');
	}
}

