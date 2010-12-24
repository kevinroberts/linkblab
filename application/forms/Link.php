<?php

class Application_Form_Link extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName("linkSubmit");
        // Make custom validations
        //$myValidator1 = new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9!$@=+\][&#_\-%^*<>()?":;\'.\s]{2,150}$/'));
        $myValidator1 = new Zend_Validate_StringLength();
        $myValidator1->setMin(2); $myValidator1->setMax(150);
        $myValidator1->setMessage('Link title was not valid: 2-150 Alpha-numeric values only');
        $myValidators[] = $myValidator1;
        
        $myValidator2 = new Zend_Validate_StringLength();
        $myValidator2->setMin(2); $myValidator2->setMax(20000);
        $myValidator2->setMessage('Your description was not valid: 2-20000 Alpha-numeric values only');
        $myValidators2[] = $myValidator2;
        
        $utils = new Application_Model_Utils();
        $myValidator3 = new Zend_Validate_Callback(array($utils, 'isValidUrl'));
        $myValidator3->setMessage('You must enter a valid URL for your link');
        $myValidators3[] = $myValidator3;
        
        $myValidator4 = new Zend_Validate_Db_RecordExists(
        array(
        'table'   => 'blabs',
        'field'   => 'title'
    	));
        $myValidator4->setMessage('The Blab you entered does not exist');
        $myValidators4[] = $myValidator4;
        
        $element = new Zend_Form_Element_Text('title', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
        		 ->setRequired(true)
				 ->addValidators($myValidators);
				 //->addErrorMessage('Please provide a name for your Link');
		$this->addElement($element);
		
		
		$element = new Zend_Form_Element_Text('link_url', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
        		 ->setRequired(false)
				 ->addValidators($myValidators3);
				 //->addErrorMessage('Please provide a name for your Link');
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Textarea('description', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
	            ->setRequired(false)
				->setFilters(array('StringTrim'))
	            ->addValidators($myValidators2);
	            //->addErrorMessage('A Blab title is required.');
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Text('blab', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
        		 ->setRequired(true)
				 ->addValidators($myValidators4);
				 //->addErrorMessage('Please provide a name for your Link');
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Hidden('isSelf', array('disableLoadDefaultDecorators' => true));
		$element->setValue('0')
				->addDecorator('ViewHelper');
		$this->addElement($element);
		
		/*
		 * Form security
		 */
		$element = new Zend_Form_Element_Hash('___h', array('disableLoadDefaultDecorators' => true));
        $element->setSalt('unique')
        		->addDecorator('ViewHelper')
        		->addErrorMessage('There was a problem processing your request. Please try again.');	
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Captcha('captcha', array(
				    'label' => "Enter the text below:",
				    'captcha' => array(
				        'captcha' => 'Image',
				        'wordLen' => 4,
				        'timeout' => 300,
        				'font' => 'Arial'        				
				    ),
				));
 			//$element->addErrorMessage('Invalid security captcha code');
			$this->addElement($element);
    
    }


}

