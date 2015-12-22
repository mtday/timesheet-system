<?php

class Admin_HolidayController extends BaseController
{
	/**
	 * Go to the holiday page.
	 */
    public function indexAction()
    {
		// Set the title for this action.
		$this->view->title = "Holiday Management";

		// Set the manage layout.
		$this->_helper->layout->setLayout('manage');

		// Include the holiday management scripts on the page.
		$this->view->scripts = "holiday";
    }

	/**
	 * Add a holiday.
	 */
    public function addAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the holiday.
			$data = array(
				'description'  => $this->getStr('description'),
				'config'       => $this->getStr('config')
			);

			// Get the DAO.
			$holidayDao = new HolidayDao();

			// Add the holiday.
			$id = $holidayDao->add($data);

			// Retrieve the new holiday.
			$holiday = $holidayDao->get($id);

			// Make sure the holiday was returned.
			if (isset($holiday)) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				$json->msg = 'The holiday was created successfully.';
				$json->holiday = $holiday;
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to create the holiday.';
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
	 * Delete a holiday.
	 */
    public function deleteAction()
    {
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the ids of the holidays to delete.
		$ids = $this->getInts('ids');

		// Determine if there are multiple holidays to delete.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$holidayDao = new HolidayDao();

			// Delete all the holidays.
			$count = $holidayDao->remove($ids);

			// Make sure some holidays were deleted.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The holidays were removed successfully.';
				else
					$json->msg = 'The holiday was removed successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to delete the holidays.';
				else
					$json->msg = 'Failed to delete the holiday.';
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
	 * Update a holiday.
	 */
    public function updateAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Create an array of the fields that represent the holiday.
			$data = array(
				'description'  => $this->getStr('description'),
				'config'       => $this->getStr('config')
			);

			// Get the id of the holiday to modify.
			$id = $this->getInt('id');

			// Make sure the id is set.
			if (isset($id)) {
				// Update the holiday.
				$holidayDao = new HolidayDao();

				// Save the new values.
				$holidayDao->save($id, $data);

				// Retrieve the updated holiday.
				$holiday = $holidayDao->get($id);

				// Make sure the holiday was returned.
				if (isset($holiday)) {
					// Create the JSON object to return.
					$json = new stdClass();
					$json->success = true;
					$json->msg = 'The holiday was updated successfully.';
					$json->holiday = $holiday;
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to update the holiday.';
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'The id of the holiday to modify must ' .
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
	 * Retrieve the JSON for all the holidays.
	 */
    public function jsonAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get all the holidays.
			$holidayDao = new HolidayDao();
			$holidays = $holidayDao->getAll();

			// Create the JSON object to return.
			$json = new stdClass();
			$json->success = true;
			$json->holidays = $holidays;
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
			$json->holidays = array();
		}

		// Return all the holidays as JSON.
		$this->_helper->json($json);
	}
}

