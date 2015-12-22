<?php

class Admin_IndexController extends BaseController
{
	/**
	 * Go to the holiday page.
	 */
    public function indexAction()
    {
		// Go to the holiday index.
		$this->_forward('index', 'holiday', null);
    }
}

