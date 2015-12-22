<?php

class Manager_ContactController extends BaseController
{
	/**
	 * Go show the contact management home page.
	 */
	function indexAction()
	{
		// Set the page title.
		$this->view->title = "Contact Management";

		// Set the manage layout for this action.
		$this->_helper->layout->setLayout('manage');

		// Include the contact management scripts on the page.
		$this->view->scripts = "contact";
	}

	/**
	 * Add a contact.
	 */
    public function addAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get the user's session.
			$session = new Zend_Session_Namespace('Web');

			// Make sure the user can add contacts.
			if (!$session->employee->manager)
				throw new Exception(
						'Only managers can add contacts.');

			// Create an array of the fields that represent the contact.
			$data = array(
				'company_name' => $this->getStr('company_name'),
				'poc_name'     => $this->getStr('poc_name'),
				'poc_title'    => $this->getStr('poc_title'),
				'poc_phone'    => $this->getStr('poc_phone'),
				'poc_phone2'   => $this->getStr('poc_phone2'),
				'poc_fax'      => $this->getStr('poc_fax'),
				'poc_email'    => $this->getStr('poc_email'),
				'street'       => $this->getStr('street'),
				'city'         => $this->getStr('city'),
				'state'        => $this->getStr('state'),
				'zip'          => $this->getInt('zip'),
				'comments'     => $this->getStr('comments')
			);

			// Get the DAO.
			$contactDao = new ContactDao();

			// Add the contact.
			$id = $contactDao->add($data);

			// Retrieve the new contact.
			$contact = $contactDao->get($id);

			// Make sure the contact was returned.
			if (isset($contact)) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->msg = 'The contact was created successfully.';
				$json->contact = $contact;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to create the contact.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON.
		$this->_helper->json($json);
    }

	/**
	 * Delete one or more contacts.
	 */
    public function deleteAction()
    {
		// Get the ids of the contacts to delete.
		$ids = $this->getInts('ids');

		// Determine if there are multiple contacts to delete.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the user's session.
			$session = new Zend_Session_Namespace('Web');

			// Make sure the current user is a manager.
			if (!$session->employee->manager)
				throw new Exception('Only managers can delete contacts.');

			// Get the DAO.
			$contactDao = new ContactDao();

			// Delete all the contacts.
			$count = $contactDao->remove($ids);

			// Make sure some contacts were deleted.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The contacts were removed successfully.';
				else
					$json->msg = 'The contact was removed successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to delete the contacts.';
				else
					$json->msg = 'Failed to delete the contact.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON.
		$this->_helper->json($json);
    }

	/**
	 * Update a contact.
	 */
    public function updateAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get the user's session.
			$session = new Zend_Session_Namespace('Web');

			// Make sure the current user is a manager.
			if (!$session->employee->manager)
				throw new Exception('Only managers can update contacts.');

			// Create an array of the fields that represent the contact.
			$data = array(
				'company_name' => $this->getStr('company_name'),
				'poc_name'     => $this->getStr('poc_name'),
				'poc_title'    => $this->getStr('poc_title'),
				'poc_phone'    => $this->getStr('poc_phone'),
				'poc_phone2'   => $this->getStr('poc_phone2'),
				'poc_fax'      => $this->getStr('poc_fax'),
				'poc_email'    => $this->getStr('poc_email'),
				'street'       => $this->getStr('street'),
				'city'         => $this->getStr('city'),
				'state'        => $this->getStr('state'),
				'zip'          => $this->getInt('zip'),
				'comments'     => $this->getStr('comments')
			);

			// Get the id of the contact to modify.
			$id = $this->getInt('id');

			// Make sure the id is set.
			if (isset($id)) {
				// Get the DAO.
				$contactDao = new ContactDao();

				// Save the new values.
				$contactDao->save($id, $data);

				// Retrieve the updated contact.
				$contact = $contactDao->get($id);

				// Make sure the contact was returned.
				if (isset($contact)) {
					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'The contact was updated successfully.';
					$json->contact = $contact;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to update the contact.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'The id of the contact to modify must ' .
					'be specified.';
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON.
		$this->_helper->json($json);
    }

	/**
	 * Retrieve the JSON for all the contacts.
	 */
    public function jsonAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get the user's session.
			$session = new Zend_Session_Namespace('Web');

			// Make sure the current user is a manager.
			if (!$session->employee->manager)
				throw new Exception('Only managers can retrieve contacts.');

			// Get all the contacts.
			$contactDao = new ContactDao();
			$contacts = $contactDao->getAll();

			// Create the JSON object to return.
			$json = new stdClass();
			$json->success = true;
			$json->contacts = $contacts;
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->contacts = array();
		}

		// Return all the contacts as JSON.
		$this->_helper->json($json);
	}
}

