<?php

class UploadForm extends Zend_Form
{
	/**
	 * Create an instance of this class.
	 */
    public function __construct($options = null)
    {
		parent::__construct($options);
		$this->setName('upload');
		$this->setAttrib('enctype', 'multipart/form-data');

		$file = new Zend_Form_Element_File('file');
		$file->setLabel('File')
			 ->setRequired(true)
			 ->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Upload');

		$this->addElements(array($file, $submit));
    }
}

