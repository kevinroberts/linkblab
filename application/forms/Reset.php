<?php

class Application_Form_Reset extends Zend_Form
{

    public function init()
    {
        $this->setName("reset");
        $this->setMethod('post');
        
        $passwordValidator = new Zend_Validate_Regex(array('pattern' => '/^(?=.*\d).{4,30}$/')); //Password must be between 4 and 30 digits long and include at least one numeric digit.
        
        $element = new Zend_Form_Element_Password('password', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
                ->setRequired(true)
                ->setValidators(array($passwordValidator))
                ->addErrorMessage('Please enter a valid password between 4 and 30 characters with at least 1 numeric digit');	
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Password('confirm_password', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
                ->setRequired(true)
                ->setValidators(array($passwordValidator))
                ->addErrorMessage('The two passwords do not match');
			$this->addElement($element);

		$element = new Zend_Form_Element_Hash('___h', array('disableLoadDefaultDecorators' => true));
        $element->setSalt('unique')
        		->addDecorator('ViewHelper')
        		->addErrorMessage('Form must not be resubmitted');	
        $this->addElement($element);
        
        $this->clearDecorators();
		$this->addDecorator('FormElements')
	         ->addDecorator('Form');
    }


}

