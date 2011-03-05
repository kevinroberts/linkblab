<?php

class Application_Form_Signup extends Zend_Form
{

    public function init()
    {
        $this->setName("create_account");
        $this->setMethod('post');

        // Make custom username validations
        $userValidator1 = new Zend_Validate_Regex(array('pattern' => '/^([a-zA-Z0-9]{2,30})$/'));
        $userValidator1->setMessage('Username was not valid: 2-30 Alpha-numeric values only');
        $myValidators[] = $userValidator1;

        $userValidator2 = new Zend_Validate_Db_NoRecordExists(
        array(
        'table'   => 'users',
        'field'   => 'username'
    	));
    	$userValidator2->setMessage('This username has already been registered.');
    	$myValidators[] = $userValidator2;
        
        
        $emailValidator = new Zend_Validate_EmailAddress();
        $passwordValidator = new Zend_Validate_Regex(array('pattern' => '/^(?=.*\d).{4,30}$/')); //Password must be between 4 and 30 digits long and include at least one numeric digit.

        $element = new Zend_Form_Element_Text('username', array('disableLoadDefaultDecorators' => false));
        $element->addDecorator('ViewHelper')
        		 ->setRequired(true)
				 ->addValidators($myValidators);
				 //->addErrorMessage('Please provide a valid username');
		$this->addElement($element);

		$element = new Zend_Form_Element_Text('email', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
	            ->setRequired(true)
	            ->setValidators(array($emailValidator))
	            ->addErrorMessage('Please provide a valid email');
		$this->addElement($element);    

        $element = new Zend_Form_Element_Password('password', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
                ->setRequired(true)
                ->setValidators(array($passwordValidator))
                ->setAttrib('autocomplete', 'off')
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
        
        $captcha_session = new Zend_Session_Namespace('captcha');
        
        if ($captcha_session->tries > 3)
        {
	        			
        $recaptcha = new Zend_Service_ReCaptcha(RECAPTCHAPUBLIC,
                        RECAPTCHAPRIVATE);
        $element = $this->createElement('Captcha', 'ReCaptcha',
                array('captcha'=>array('captcha'=>'ReCaptcha',
                                        'service'=>$recaptcha))); 
        $element->addErrorMessage('Invalid security captcha code');
        $element->setLabel("Enter CAPTCHA");
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
    		
    		/*if ($this->_user_repository->authenticate($data['username'], $data['password']))
    		{
    			Zend_Session::namespaceUnset('captcha');
    			return TRUE;
    		}
    		else
    		{
    			$this->setErrors(array('Invalid username or password'));
    		}*/
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