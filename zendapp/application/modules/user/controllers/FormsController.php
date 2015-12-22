<?php

class User_FormsController extends BaseController
{
	/**
	 * Go show the forms page.
	 */
	function indexAction()
	{
		// Set the title for this action.
		$this->view->title = "Forms";

		// Set the forms layout for this action.
		$this->_helper->layout->setLayout('forms');
	}

	/**
	 * Retrieve the JSON for all the forms.
	 */
    public function jsonAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get all the forms.
			$formDao = new FormDao();
			$forms = $formDao->getAll();

			// Create the JSON object to return.
			$json = new stdClass();
			$json->success = true;
			$json->forms = $forms;
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->forms = array();
		}

		// Return all the forms as JSON.
		$this->_helper->json($json);
	}
}

