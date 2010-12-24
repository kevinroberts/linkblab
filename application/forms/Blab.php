<?php

class Application_Form_Blab extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName("blabCreate");
        
        // Make custom validations
        $myValidator1 = new Zend_Validate_Regex(array('pattern' => '/^([a-zA-Z0-9]{2,30})$/'));
        $myValidator1 ->setMessage('Blab name was not valid: 2-30 Alpha-numeric values only');
        $myValidators[] = $myValidator1;
        
        $myValidator2 = new Zend_Validate_Db_NoRecordExists(
        array(
        'table'   => 'blabs',
        'field'   => 'title'
    	));
    	$myValidator2->setMessage('This Blab has already been created!');
        $myValidators[] = $myValidator2;
        
        $myValidator3 = new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9!?";\'.\s]{2,140}$/'));
        $myValidator3 ->setMessage('Your Blab Title was not valid: 2-140 Alpha-numeric values only');
        $myValidators2[] = $myValidator3;
        
        $myValidator4 = new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9!$@=+\][&#_\-%^*<>()?";\'.\s]{2,1400}$/'));
        $myValidator4 ->setMessage('Your description was not valid: 2-1400 Alpha-numeric values only');
        $myValidators3[] = $myValidator4;
        
        
        $element = new Zend_Form_Element_Text('title', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
        		 ->setRequired(true)
				 ->addValidators($myValidators);
				 //->addErrorMessage('Please provide a name for your Blab');
		$this->addElement($element);
		
		 $element = new Zend_Form_Element_Text('head_title', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
	            ->setRequired(true)
	            ->setFilters(array('StringTrim'))
	            ->addValidators($myValidators2)
	            ->addErrorMessage('A Blab title is required.');
		$this->addElement($element);
        
		$element = new Zend_Form_Element_Textarea('description', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
	            ->setRequired(false)
	            ->setFilters(array('StringTrim'))
	            ->addValidators($myValidators3);
	            //->addErrorMessage('A Blab title is required.');
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Hash('___h', array('disableLoadDefaultDecorators' => true));
        $element->setSalt('unique')
        		->addDecorator('ViewHelper')
        		->addErrorMessage('Form must not be resubmitted');	
        $this->addElement($element);
        
        $captcha_session = new Zend_Session_Namespace('captcha');
        
        if ($captcha_session->tries > 5)
        {
	        			
			$element = new Zend_Form_Element_Captcha('captcha', array(
				    'label' => "Enter security captcha code",
				    'captcha' => array(
				        'captcha' => 'Image',
				        'wordLen' => 6,
				        'timeout' => 300,
        				'font' => 'Arial'        				
				    ),
				));
 
			$element->addErrorMessage('Invalid security captcha code');
			$this->addElement($element);
        }     
        $this->clearDecorators();
		$this->addDecorator('FormElements')
	         ->addDecorator('Form');
        
    }
    
    public function isValid($data)
    {
    	if (parent::isValid($data))
    	{
    		Zend_Session::namespaceUnset('captcha'); // Remove captcha session variable
    		return true;
    		
    	}
		
    	$captcha_session = new Zend_Session_Namespace('captcha');
		if (empty($captcha_session->tries))
		{
			$captcha_session->tries = 0;
		}
		$captcha_session->tries = $captcha_session->tries + 1;
    	return FALSE;
    }


}

