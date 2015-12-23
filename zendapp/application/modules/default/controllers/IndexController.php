<?php

class IndexController extends BaseController
{
	/**
	 * The main index page redirects to the timesheet page.
	 */
	function indexAction()
	{
		// Write a log of this page request.
		if (isset($session->employee)) {
            // Redirect to the user's timesheet page - this should force a login
            // when the user has not yet authenticated with the system.
            $this->_forward('index', 'timesheet', 'user');
        } else {
            $this->_forward('login', 'login');
        }
	}
}

