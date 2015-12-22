<?php

class Manager_FormsController extends BaseController
{
	/**
	 * Determine if a string ends with another string.
	 */
	public function endsWith($str, $ending)
	{
		// Get the lowercase versions.
		$full = strtolower($str);
		$end = strtolower($ending);

		// Check to see if the ending matches.
		return substr($full, strlen($full) - strlen($end)) == $end;
	}

	/**
	 * Determine if a file is a valid document or not.
	 */
	public function isValidFile($file)
	{
		// . and .. are not images.
		if ("." == $file || ".." == $file)
			return false;

		// Make sure the extension implies an image.
		return self::endsWith($file, ".doc")  ||
			   self::endsWith($file, ".docx") ||
			   self::endsWith($file, ".pdf")  ||
			   self::endsWith($file, ".ppt")  ||
			   self::endsWith($file, ".pptx") ||
			   self::endsWith($file, ".xls")  ||
			   self::endsWith($file, ".xlsx");
	}

	/**
	 * Add a form.
	 */
    public function addAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get the uploaded file names.
			$filename = $_FILES['file']['name'];
			$tmp      = $_FILES['file']['tmp_name'];

			// Get the parameters.
			$name = $this->getStr('name');
			$description = $this->getStr('description');

			// Make sure the upload was successful.
			if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'Failed to upload the file: ' .
					$_FILES['file']['error'];
			} else if (isset($name) && isset($description)) {
				// Make sure the file has a valid extension.
				if (self::isValidFile($filename)) {
					// Determine the new file location.
					$file = "./forms/$filename";

					// Make sure the file does not already exist.
					if (! file_exists($file)) {
						// Attempt to move the uploaded file to the correct
						// place.
						if (move_uploaded_file($tmp, $file)) {
							// Create an array of the fields that represent the form.
							$data = array(
								'name'         => $name,
								'file_name'    => $filename,
								'description'  => $description
							);

							// Get the DAO.
							$formDao = new FormDao();

							// Add the form.
							$id = $formDao->add($data);

							// Retrieve the new form.
							$form = $formDao->get($id);

							// Make sure the form was returned.
							if (isset($form)) {
								// Create the JSON object to return.
								$json = new stdClass();
								$json->success = true;
								$json->msg = 'The form was uploaded successfully.';
								$json->form = $form;
							} else {
								// Create the error JSON object to return.
								$json = new stdClass();
								$json->success = false;
								$json->msg = 'Failed to create the form.';

								// Get rid of the uploaded file.
								if (isset($file))
									unlink($file);
							}
						} else {
							// Create the error JSON object to return.
							$json = new stdClass();
							$json->success = false;
							$json->msg = "Failed to upload the file.";

							// Get rid of the uploaded file.
							if (isset($tmp))
								unlink($tmp);
						}
					} else {
						// Create the error JSON object to return.
						$json = new stdClass();
						$json->success = false;
						$json->msg = "The specified file already exists, " .
							"and will not be overwritten. Please delete " .
							"or rename the existing file first.";

						// Get rid of the uploaded file.
						if (isset($tmp))
							unlink($tmp);
					}
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'The uploaded file has an invalid ' .
						'document extension. Only PDF, Microsoft DOC, ' .
						'and Microsoft XLS files can be uploaded.';

					// Get rid of the uploaded file.
					if (isset($tmp))
						unlink($tmp);
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'A name and description must be specified when ' .
					'uploading a document.';

				// Get rid of the uploaded file.
				if (isset($tmp))
					unlink($tmp);
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON response.
		$content = Zend_Json::encode($json);
		$this->getResponse()
			 ->setHeader('Content-Type', 'text/html')
			 ->setBody($content)
			 ->sendResponse();
		exit();
    }

	/**
	 * Delete a form.
	 */
    public function deleteAction()
    {
		// Get the user's session.
		$session = new Zend_Session_Namespace('Web');

		// Get the ids of the forms to delete.
		$ids = $this->getInts('ids');

		// Determine if there are multiple forms to delete.
		$multiple = count($ids) > 1 ? true : false;

		// Wrap the whole thing in a try/catch.
		try {
			// Get the DAO.
			$formDao = new FormDao();

			// Keep track of the deleted forms.
			$count = 0;

			// Iterate over the specified ids.
			foreach ($ids as $id) {
				// Get this form.
				$form = $formDao->get($id);

				// If the form exists, delete it.
				if (isset($form) && isset($form->file_name)) {
					// Delete the file from the file system.
					unlink(dirname(__FILE__) . "/../../../../../forms/" . $form->file_name);

					// Remove the form from the database.
					$idarr = array(); $idarr[] = $id;
					$count += $formDao->remove($idarr);
				}
			}

			// Make sure some forms were deleted.
			if (isset($count) && $count > 0) {
				// Create the JSON object to return.
				$json = new stdClass();
				$json->success = true;
				if ($multiple)
					$json->msg = 'The forms were removed successfully.';
				else
					$json->msg = 'The form was removed successfully.';
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				if ($multiple)
					$json->msg = 'Failed to delete the forms.';
				else
					$json->msg = 'Failed to delete the form.';
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
	 * Update a form.
	 */
    public function updateAction()
    {
		// Wrap the whole thing in a try/catch.
		try {
			// Get the uploaded file names.
			$filename = $_FILES['file']['name'];
			$tmp      = $_FILES['file']['tmp_name'];

			// Get the parameters.
			$id = $this->getInt('id');
			$name = $this->getStr('name');
			$description = $this->getStr('description');

			// Make sure the id is valid.
			if (isset($id) && is_numeric($id)) {
				// Get the DAO.
				$formDao = new FormDao();

				// Get the form.
				$form = $formDao->get($id);

				// Make sure the upload was successful.
				if ($filename != '' && $_FILES['file']['error'] != UPLOAD_ERR_OK) {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'Failed to upload the file: ' .
						$_FILES['file']['error'];
				} else if (isset($name) && isset($description)) {
					// Was an upload attempted?
					if ($filename == '') {
						// Create an array of the fields that represent
						// the updated form.
						$data = array(
							'name'         => $name,
							'description'  => $description
						);

						// Update the form.
						$formDao->save($id, $data);

						// Retrieve the new form.
						$form = $formDao->get($id);

						// Make sure the form was returned.
						if (isset($form)) {
							// Create the JSON object to return.
							$json = new stdClass();
							$json->success = true;
							$json->msg = 'The form was updated successfully.';
							$json->form = $form;
						} else {
							// Create the error JSON object to return.
							$json = new stdClass();
							$json->success = false;
							$json->msg = 'Failed to update the form.';
						}
					} else if (self::isValidFile($filename)) {
						// Determine the new file location.
						$file = "./forms/$filename";

						// Make sure the file does not already exist.
						if ($filename == $form->file_name || ! file_exists($file)) {
							// Delete the existing form file.
							unlink(dirname(__FILE__) . "/../../../../../forms/"
									. $form->file_name);

							// Attempt to move the uploaded file to the correct
							// place.
							if (move_uploaded_file($tmp, $file)) {
								// Create an array of the fields that represent
								// the updated form.
								$data = array(
									'name'         => $name,
									'file_name'    => $filename,
									'description'  => $description
								);

								// Update the form.
								$formDao->save($id, $data);

								// Retrieve the new form.
								$form = $formDao->get($id);

								// Make sure the form was returned.
								if (isset($form)) {
									// Create the JSON object to return.
									$json = new stdClass();
									$json->success = true;
									$json->msg = 'The form was updated successfully.';
									$json->form = $form;
								} else {
									// Create the error JSON object to return.
									$json = new stdClass();
									$json->success = false;
									$json->msg = 'Failed to update the form.';

									// Get rid of the uploaded file.
									if (isset($file))
										unlink($file);
								}
							} else {
								// Create the error JSON object to return.
								$json = new stdClass();
								$json->success = false;
								$json->msg = "Failed to upload the file.";

								// Get rid of the uploaded file.
								if (isset($tmp))
									unlink($tmp);
							}
						} else {
							// Create the error JSON object to return.
							$json = new stdClass();
							$json->success = false;
							$json->msg = "The specified file already exists, " .
								"and will not be overwritten. Please delete " .
								"or rename the existing file first.";

							// Get rid of the uploaded file.
							if (isset($tmp))
								unlink($tmp);
						}
					} else {
						// Create the error JSON object to return.
						$json = new stdClass();
						$json->success = false;
						$json->msg = 'The uploaded file has an invalid ' .
							'document extension. Only PDF, Microsoft DOC, ' .
							'and Microsoft XLS files can be uploaded.';

						// Get rid of the uploaded file.
						if (isset($tmp))
							unlink($tmp);
					}
				} else {
					// Create the error JSON object to return.
					$json = new stdClass();
					$json->success = false;
					$json->msg = 'A name and description must be specified when ' .
						'updating a form.';

					// Get rid of the uploaded file.
					if (isset($tmp))
						unlink($tmp);
				}
			} else {
				// Create the error JSON object to return.
				$json = new stdClass();
				$json->success = false;
				$json->msg = 'A valid id must be specified when ' .
					'updating a form.';

				// Get rid of the uploaded file.
				if (isset($tmp))
					unlink($tmp);
			}
		} catch (Zend_Exception $ex) {
			// Create the error JSON object to return.
			$json = new stdClass();
			$json->success = false;
			$json->msg = $ex->getMessage();
		}

		// Return the JSON response.
		$content = Zend_Json::encode($json);
		$this->getResponse()
			 ->setHeader('Content-Type', 'text/html')
			 ->setBody($content)
			 ->sendResponse();
		exit();
    }
}

