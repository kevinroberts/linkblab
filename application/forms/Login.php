<?php

class Application_Form_Login extends Zend_Form
{

   public function init()
    {
        $this->setName("login");
        $this->setMethod('post');

        $element = new Zend_Form_Element_Text('username', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
	            ->setRequired(true)
	            ->setFilters(array('StringTrim', 'StringToLower'))
	            ->addErrorMessage('Username is required.');
		$this->addElement($element);
        

        $element = new Zend_Form_Element_Password('password', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
                ->setRequired(true)
                ->addErrorMessage('Password is required');	
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Checkbox('rememberMe', array('disableLoadDefaultDecorators' => true));
        $element->addDecorator('ViewHelper')
                ->setRequired(false)
                ->addErrorMessage('Login error - remember me');	
		$this->addElement($element);
		

		$element = new Zend_Form_Element_Hash('___h', array('disableLoadDefaultDecorators' => true));
        $element->setSalt('unique')
        		->addDecorator('ViewHelper')
        		->addErrorMessage('Form must not be resubmitted');	
        $this->addElement($element);
		
/*
        $this->addElement('hidden', 'return', array(
        'value' => Zend_Controller_Front::getInstance()->getRequest()->getRequestUri()                         
                ));
*/        
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

