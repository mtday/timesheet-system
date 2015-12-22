<?php

class Manager_IndexController extends BaseController
{
	/**
	 * Go show the manager home page.
	 */
	function indexAction()
	{
		// Go to the employee controller.
		$this->_helper->redirector('index', 'employee', 'manager');
	}
}

