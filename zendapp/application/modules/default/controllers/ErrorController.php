<?php

class ErrorController extends BaseController
{
	/**
	 * Handle an error.
	 */
	function errorAction()
	{
		// Get an instance of the error.
		$error = $this->getObj('error_handler');

		// Determine what to do based on the type of error.
		switch ($error->type) {
			// No controller or action found.
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				// Set the title for this action.
				$this->view->title = "Page Not Found";

				// Log the invalid request.
				Logger::getLogger()->warn("No Controller: " .
						$error->request->getRequestUri() . " - " .
						$error->request->getClientIp());

				// 404 error -- controller not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
				break;
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// Set the title for this action.
				$this->view->title = "Page Not Found";

				// Log what happened.
				Logger::getLogger()->warn("No Action: " .
						$error->request->getRequestUri() . " - " .
						$error->request->getClientIp());

				// 404 error -- action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
				break;
			default:
				// Set the title for this action.
				$this->view->title = "Application Error";

				// Application error, so display error page but don't
				// change the status code.

				// Save the exception into the view.
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->exception = $error->exception;
				break;
		}
	}
}

