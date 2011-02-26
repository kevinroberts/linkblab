<?php

class AuthController extends Zend_Controller_Action
{
	protected $mailConfig, $mailConn;
	private $disabled = FALSE;

    public function init()
    {
        
    	//$this->formToken = hash("sha256", $_SERVER['REMOTE_ADDR']."2on23_BilBoBAgginS!".date("l",time()));
    	
		$this->config = array(
    	'ssl' => 'tls',
    	'port' => 587,
    	'auth' => 'login',
    	'username' => 'kevon202@gmail.com',
    	'password' => 'kevroB7943!'
		);
		$this->mailConn = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $this->config);
		
    }
    
        protected function _sql_date_format($value) {

        // Returns the date in a format for input into the database.  You can pass
        // this function a timestamp value such as time() or a string value
        // such as '04/14/2010 8:20 AM'.
     
        if (gettype($value) == 'string') $value = strtotime($value);
        return date('Y-m-d H:i:s', $value);
        }
        

    public function indexAction()
    {
        $this->_helper->redirector('login', 'auth');
    }

    protected function _process($values, $rememberMe = true)
    {
        // Get our authentication adapter and check credentials

                                        $adapter = $this->_getAuthAdapter();
                                        $adapter->setIdentity($values['username']); 
                                        $adapter->setCredential($values['password']);
                                
                                        $auth = Zend_Auth::getInstance();

                                        $result = $auth->authenticate($adapter);
                                        if ($result->isValid()) {
                                            $user = $adapter->getResultRowObject(array(
                                        		'id',
                                        		'username',
                                            	'email',
                                            	'role'
                                    		));
                                    		
                                            $auth->getStorage()->write($user);
                                            if ($rememberMe) {
                                            Zend_Session::rememberMe(Zend_Registry::get("rememberMeSeconds"));
                                            $namespace = new Zend_Session_Namespace('Zend_Auth');
											$namespace->setExpirationSeconds(Zend_Registry::get("rememberMeSeconds"));
                                            }
                                            
                                            $db = Zend_Db_Table::getDefaultAdapter();
                                  			$data = array(
                                		    'last_login'      => new Zend_Db_Expr('NOW()')
                                		    );
                                			$n = $db->update('users', $data, 'username = \''.$values['username'].'\'');
                                            if ($user->role == 'disabled') {
                                            	$this->disabled = true;
                                            	return false;
                                            }
                                			return true;
                                        }
                                        return false;
    }

    protected function _getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
                                        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                                        
                                        $authAdapter->setTableName('users')
                                            ->setIdentityColumn('username')
                                            ->setCredentialColumn('password')
                                            ->setCredentialTreatment('SHA1(CONCAT(?,salt))');
                                            
                                        
                                        return $authAdapter;
    }

    public function logoutAction()
    {
    	$auth = Zend_Auth::getInstance();
    	if (!$auth->hasIdentity())
        	{
            	$this->_redirect('/');
            	die();
            }
    	$returnTo = '';
    	if (isset($_GET['r'])) {
    		$returnTo = $_GET['r'];
    	}
        $auth->clearIdentity();
        Zend_Session::expireSessionCookie();
                		//$this->_helper->redirector('index?msg=1'); // back to login page
                		if (empty($returnTo))
        				$this->_redirect('/login?msg=1');
        				else
        				$this->_redirect('/login?msg=1&r='.$returnTo);
                		die();
    }

    public function loginAction()
    {
        // If user is already logged in -> redirect them back to the homepage
                            	$auth = Zend_Auth::getInstance();
                            	$utils = new Application_Model_Utils();
                            	if ($auth->hasIdentity())
                        		{
                        			 $this->_helper->redirector('index', 'index');
                        		}
                        		if($this->_request->isXmlHttpRequest()) 
                        		{
			            			// if this was an ajax login request
            						$this->_helper->layout->disableLayout();
  	    							$this->_helper->viewRenderer->setNoRender();
									$data = $this->_request->getPost();
							        if (!($data['token'] == $utils->form_token()))
									{ // token did not match
										$myArray = array(
							             'isvalid' => array(
							                             false
							                           ),
							              'message' => array( 'Sorry your request could not be completed. Please try again. (Token error: HTTP400) ')
							              //'message' => array( 't1:'.$data['token'].'  t2:'.$utils->form_token())
							           ); $jsonData = Zend_Json::encode($myArray);
										return $this->_response->appendBody($jsonData);
									}
									if($data["pl"] == '1') {
										$remember = true;
									}
									else {
										$remember = false;
									}
									
									if ($this->_process($data, $remember)) {
										$myArray = array(
							             'isvalid' => array(
							                             true
							                           ),
							              'message' => array( 'Succesful login!')
							           ); $jsonData = Zend_Json::encode($myArray);
										return $this->_response->appendBody($jsonData);
									}
									else {
										$myArray = array(
							             'isvalid' => array(
							                             false
							                           ),
							              'message' => array( ($this->disabled) ? "Your account has been disabled" : "Invalid username or password")
							           ); $jsonData = Zend_Json::encode($myArray);
										return $this->_response->appendBody($jsonData);
									}
            					}
            					else {
            		
                                $form = new Application_Form_Login();
                                $request = $this->getRequest();
                                if ($request->isPost()) {
                                	$data = $this->_request->getPost();
                					if ($form->isValid($data))
                					{
                						// Check if the user wants a persistant login
                						$rememberMe = isset($data['rememberMe']) ? $data['rememberMe'] : 0; 
                						$rememberMe = ($rememberMe == 1) ? true : false;
                						if ($this->_process($form->getValues(), $rememberMe)) {
                					// We're authenticated! Redirect them to their previously requested page
                						 if (isset($_GET["r"])) {
 										$returnTo = $utils->urlsafe_b64decode($_GET["r"]);
 										// check if this link is within the linkblab server domain
 										$urlParts = parse_url($returnTo);
 										if (strpos($urlParts['host'], $_SERVER['SERVER_NAME']) === false) {
 												$returnTo = '/'; // this link is not within the domain
 											}
 										}
 										else // no requested url was supplied -> send them to the homepage
 										$returnTo = "/";
                						return $this->_redirect($returnTo);
                					//	return $this->redirect('index', 'index');
                						}
                						else
                						{
                							if ($this->disabled) // if this account has been disabled notify the user
                							{
                								$form->setErrors(array('Your account has been disabled by a site administrator. Please email admin@linkblab.com if you want to reactivate.'));
                								Zend_Auth::getInstance()->clearIdentity();
                							}
                							else
                							$form->setErrors(array('Invalid username or password'));
                						}
                					}
                					$form->setDefaults($data);  
                                        }
                                        
                                        $this->view->form = $form;
									    /* Create a per-user unique form token for CSRF protection */
										$token = $utils->form_token(true);
                                       	// Pass CSRF token to view
                                        $this->view->token = $token;
            				}
    }

    public function signupAction()
    {
        // If user is already logged in -> redirect them back to the homepage
                            	$auth = Zend_Auth::getInstance();
                            	if ($auth->hasIdentity())
                        		{
                        			 $this->_helper->redirector('index', 'index');
                        		}
                            	
                                $form = new Application_Form_Signup();
                                $request = $this->getRequest();
                                if ($request->isPost()) {
                                	$data = $this->_request->getPost();
                					if ($form->isValid($data))
                					{
                					    // Create the user in the database...
                						$newSalt = sha1(time().'ntHGaxVr5zeOKmjvZFQleSQCs7DWtuh'.uniqid(mt_rand(),true));
                						$newPass = sha1($data['password'].$newSalt);
        								$db = Zend_Db_Table::getDefaultAdapter();
        								$data = array(
        								'username'      => $data['username'],
        								'password' =>  $newPass,
        								'email' => $data['email'],
        								'salt' => $newSalt,
        								'date_created'      => new Zend_Db_Expr('NOW()'),
        								'user_ip_address' => $_SERVER['REMOTE_ADDR']
        								);
        								$db->insert('users',$data);
        								$lastID = $db->lastInsertId("users");
        								
        								$select = $db->select();
										$select->from("subscriptions");
										$select->where("user_id = ?", 9);
										$results = $db->fetchAll($select);
										// Set user with a default set of subscriptions from user categoryModel
										$i = 1;
                						foreach ($results as $row) {
									   		$data = array(
        										'user_id'      => $lastID,
        										'blab_id' =>  $row['blab_id'],
									   			'display_order' => $row['display_order']
        										);
        									$inserter = $db->insert("subscriptions", $data);
        									$i++;
											}
																				
                						/*$stmt = $db->query(
           			                    'INSERT INTO users (username, password, email, salt ) VALUES (?, ?, ?, ? )',
           			                     array($data['username'], $newPass, $data['email'], $newSalt )
           					             );
           					             $update = array(
                                		    'date_created'      => new Zend_Db_Expr('NOW()')
                                		    );
                                			$n = $db->update('users', $update, 'username = \''.$data['username'].'\'');*/
                						// END User Creation
                						
                						if ($this->_process($form->getValues())) {
                					 // We're authenticated! Redirect them to their previously requested page
                                     //return $this->_redirect($this->_request->getPost('return'));
                						return $this->_helper->redirector('index', 'index');
                						}
                						else
                						{
                							$form->setErrors(array('Uh oh we could not log you in'));
                						}
                						
                					}
                					$form->setDefaults($data);  
                                        }
                                        $this->view->form = $form;
    }

    public function resetAction()
    {
    	$form = new Application_Form_Reset();
    	$utils = new Application_Model_Utils();
    	
        if($this->_request->isXmlHttpRequest())
	{
  	//The request was made with JS XmlHttpRequest -> get rid of Zend layout
  	    $this->_helper->layout->disableLayout();
  	    $this->_helper->viewRenderer->setNoRender();
		$data = $this->_request->getPost();
		
		// Check if a valid token exists
		if (!($data['token'] == $utils->form_token())) //sha1(date("l", time()).'o2S4hWw!!Cks')
		{ // token did not match
			$myArray = array(
             'isvalid' => array(
                             false
                           ),
              'message' => array( 'Sorry your request could not be completed. Please try again. (Token error: HTTP400) ')
           ); $jsonData = Zend_Json::encode($myArray);
			return $this->_response->appendBody($jsonData);
		}
		// check if requested username and email are valid and exist
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from("users");
		$select->where("username = ?", htmlentities($data['user']));
		$select->where("email = ?", $data['mail']);
		$results = $db->fetchAll($select);
		// were results returned?
		if (empty($results[0]['username']))
		{
			$myArray = array(
             'isvalid' => array(
                             false
                           ),
              'message' => array( 'That username or email does not exist in our records' )
           ); $jsonData = Zend_Json::encode($myArray);
			return $this->_response->appendBody($jsonData);
			//die();
		}
		else {
			// Username is valid --> let's send them a password reset
			$myArray = array(
             'isvalid' => array(
                             true
                           ),
              'message' => array( 'Check your email for a link to reset your password' )
           	);
           	$resetToken = hash("sha256", uniqid(mt_rand(),true));
           	$sendTo = $results[0]['email'];
           	$expireBy = $this->_sql_date_format(time()+7200); // two hours from now
			
           	$updateData = array(
                                		    'password_reset_token'      => $resetToken,
											'password_reset_expires'  => $expireBy
                                		    );
            $n = $db->update('users', $updateData, 'id = '.$results[0]['id'].'');               		    
           	
            // Send the email
            $mail = new Zend_Mail();
            $mail->setBodyText('As you requested, here is the link to reset your password:'.PHP_EOL.' http://linkblab.com/auth/reset?tk='.$resetToken.'');
			$mail->setFrom('kevon202@gmail.com', 'Kevin Roberts');
			$mail->addTo($sendTo, $results[0]['username']);
			$mail->setSubject('LinkBlab Password Reset');
			$mail->send($this->mailConn);
			
			// All is well so lets kill the CSRF unique token so a new one has to be regenerated
			$utils->kill_token();
			
			$jsonData = Zend_Json::encode($myArray);
			$this->_response->appendBody($jsonData);
  	    
		}

		
	}
	else if (isset($_GET['tk'])) {
		// User has entered a reset token => Create the password reset form view
		$token = $utils->XssCleaner($_GET['tk']);
		
		// Look up token in database
                	$db = Zend_Db_Table::getDefaultAdapter();
                	$select = $db->select();
					$select->from("users", array("id", "password_reset_token", "password_reset_expires"));
					$select->where("password_reset_token = ?", $token);
                	$results = $db->fetchAll($select);
	if (empty($results[0]['id']))
					{
						$form->setErrors(array('Your reset token is not valid. Please request a new one from the login page.'));
					}
		$expiration =  strtotime($results[0]['password_reset_expires']);
		$now = time();
	if (($expiration-$now) < 0) // if expiration minus current time is less than 0 then token is expired
						{
							$form->setErrors(array('Your reset token has expired. (it has been longer than 2 hours since you requested it).'));
						}
		$request = $this->getRequest();
         if ($request->isPost()) {
         	$data = $this->_request->getPost();
            if ($form->isValid($data))
                {
                	
                	
                	// Check if a token exists
                	if (empty($results[0]['id']))
					{
						$form->setErrors(array('Your reset token is not valid or has expired. Please request a new one from the login page.'));
					}
					else // Token was valid. Check if it has expired
					{
						
						
						if (($expiration-$now) < 0) // if expiration minus current time is less than 0 then token is expired
						{
							$form->setErrors(array('Your reset token has expired. Please request a new one from the login page.'));
						}
						else
						{
							// Token is valid and not expired => proceed with password reset
							$newSalt = sha1(time().'ntHGaxVr5zeOKmjvZFQleSQCs7DWtuh'.uniqid(mt_rand(),true));
                			$newPass = sha1($data['password'].$newSalt);
							$updateData = array(
											'password' => $newPass,
											'salt' => $newSalt,
                                		    'password_reset_token'      => new Zend_Db_Expr('NULL'),
											'password_reset_expires'  =>  new Zend_Db_Expr('NULL')
                                		    );
            				$n = $db->update('users', $updateData, 'id = '.$results[0]['id'].'');
            				  
            				$this->_redirect('/login?msg=3');
						}
					}
                						
                }
         	
         	$form->setDefaults($data);  
         }
		$this->view->form = $form;
		
	}
	else {
		echo "Not a valid request";
		die();
	}
    	
    }


}