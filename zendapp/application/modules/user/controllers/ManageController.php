<?php

class User_ManageController extends BaseController
{
	/**
	 * Go show the management page.
	 */
	function indexAction()
	{
		// Set the title for this action.
		$this->view->title = "Management";

		// Get the employee.
		$emp = $this->view->employee;

		// Determine where to go based on the employee's roles.
		if ($emp->supervisor)
			// Redirect to the supervisor page.
			$this->_helper->redirector('index', 'index', 'supervisor');
		else if ($emp->payroll)
			// Redirect to the payroll page.
			$this->_helper->redirector('index', 'index', 'payroll');
		else if ($emp->manager)
			// Redirect to the manager page.
			$this->_helper->redirector('index', 'index', 'manager');
		else
			// This user doesn't have the appropriate roles.
			throw new Exception("Employee $emp->full_name has no management "
					. "roles.");
	}
}

