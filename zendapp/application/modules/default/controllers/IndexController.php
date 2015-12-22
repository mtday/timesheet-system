<?php

class IndexController extends BaseController
{
	/**
	 * Go show the home page.
	 */
	function indexAction()
	{
		// Set the home layout for this action.
		$this->_helper->layout->setLayout('home');
	}

	/**
	 * Go show the contact-us information.
	 */
	function contactAction()
	{
		// Set the title for this action.
		$this->view->title = "Contact Us";
	}

	/**
	 * Go show the about-us information.
	 */
	function aboutAction()
	{
		// Set the title for this action.
		$this->view->title = "About Us";
	}

	/**
	 * Go show the services information.
	 */
	function servicesAction()
	{
		// Set the title for this action.
		$this->view->title = "Our Services";
	}

	/**
	 * Go show the partners information.
	 */
	function partnersAction()
	{
		// Set the title for this action.
		$this->view->title = "Our Partners";
	}

	/**
	 * Go show the careers information.
	 */
	function careersAction()
	{
		// Set the title for this action.
		$this->view->title = "Careers";
	}

	/**
	 * Go show the community information.
	 */
	function communityAction()
	{
		// Set the title for this action.
		$this->view->title = "Community";
	}

	/**
	 * Go show the leadership information.
	 */
	function leadershipAction()
	{
		// Set the title for this action.
		$this->view->title = "Leadership";
	}

	/**
	 * Go show the legal information.
	 */
	function legalAction()
	{
		// Set the title for this action.
		$this->view->title = "Privacy Policy and Terms of Service";
	}
}

