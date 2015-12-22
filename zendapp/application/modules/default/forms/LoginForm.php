<?php

class LoginForm extends Zend_Form
{
	/**
	 * Create an instance of this class.
	 */
    public function __construct($options = null)
    {
		// Call the parent constructor.
        parent::__construct($options);

		// Set the form name.
		$this->setName('login');

		// Add the login element.
		$login = new Zend_Form_Element_Text('login');
		$login
			->setLabel('Login:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

		// Add the password element.
		$password = new Zend_Form_Element_Password('password');
		$password
			->setLabel('Password:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

		// Add the submit button.
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');

		// Add all the elements to the form.
		$this->addElements(array($login, $password, $submit));
    }
}

