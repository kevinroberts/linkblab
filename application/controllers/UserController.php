<?php

class UserController extends Zend_Controller_Action {
	
	public function init() {
		/* Initialize action controller here */
	}
	
	public function indexAction() {
	
	}
	
	public function accountAction() {
		//$utils = new Application_Model_Utils();
		$username = $this->_request->getParam ( 'username' );
		
		$auth = Zend_Auth::getInstance ();
		
		if ($auth->hasIdentity ()) {
			
			$user = $auth->getIdentity ();
			if ($username == $user->username) {
				$this->view->loggedIn = true;
				$this->view->username = $user->username;
				$this->view->userID = $user->id;
			} else {
				$this->view->loggedIn = false;
				$this->view->username = $username;
			}
		} else {
			$this->view->username = $username;
			$this->view->loggedIn = false;
		}
	}
	
	public function editAction() {
		$utils = new Application_Model_Utils ();
		$username = $this->_request->getParam ( 'username' );
		$auth = Zend_Auth::getInstance ();
		$this->view->postedResponse = false;
		if ($auth->hasIdentity ()) {
			
			$user = $auth->getIdentity ();
			
			if ($username == $user->username) {
				$this->view->loggedIn = true;
				$this->view->username = $user->username;
				$this->view->userID = $user->id;
				$this->view->email = $user->email;
				
				$request = $this->getRequest ();
				if ($request->isPost ()) {
					$data = $this->_request->getPost ();
					
					foreach ( $data as $key => $item ) {
						$pos = strpos ( $key, "check" );
						
						if ($pos !== false) {
							// Set all blabs with id = $item to read only
							if (is_int ( ( int ) $item )) {
								$success = $utils->markBlabReadOnly ( $user->id, $item );
							
								//echo ($success == true) ? 'successfully marked '.$item.' as read only' : 'failed to mark as read only';
							}
						
						}
						if ($key == 'open') { // else mark read-only items as not read only
							if (! empty ( $item )) {
								$list = preg_split ( '/[\s,]+/', $item );
								//print_r($list);
								$list = array_unique ( $list );
								foreach ( $list as $id ) {
									if (is_int ( ( int ) $id )) {
										$success = $utils->markBlabReadOnly ( $user->id, $id, 0 );
									}
								
								}
							}
						
						}
					}
					$this->view->postedResponse = true;
				
				}
			
			} else {
				$this->view->loggedIn = false;
				$this->view->username = $username;
			}
		
		} else {
			$this->view->username = $username;
			$this->view->loggedIn = false;
		}
	}
	
	public function ajaxhandlerAction() {
		$auth = Zend_Auth::getInstance ();
		$utils = new Application_Model_Utils ();
		$token = $utils->XssCleaner ( $this->_request->getParam ( 'token' ) );
		$action = $utils->XssCleaner ( $this->_request->getParam ( 'a' ) );
		
		if (! ($this->_request->isXmlHttpRequest ()) || ! ($auth->hasIdentity ()) || empty ( $token ) || empty ( $action )) {
			// form can only be accessed through ajax and with a user set and token
			return $this->_redirect ( "/index/notfound" );
		}
		
		$data = $this->_request->getPost (); // get posted content
		

		$user = $auth->getIdentity ();
		$validToken = hash ('sha256', $user->username . date ( 'D', time () ).'SaK0fSa1t' );
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ();
		
		switch ($action) {
			case 'order' :
				if ($token != $validToken) {
					$this->_response->appendBody ( "Token not valid" );
				} else {
					$db = Zend_Db_Table::getDefaultAdapter ();
					$frontpage = $data ['frontpage'];
					$rowsUpdated = 0;
					for($i = 0; $i < count ( $frontpage ); $i ++) {
						if (!is_numeric($frontpage[$i])) {
							break;
						}
						$data = array ('display_order' => $i + 1 );
						$update = $db->update ( "subscriptions", $data, "user_id = " . $user->id . " AND blab_id = " . $frontpage [$i] );
						$rowsUpdated += $update;
					}
					
					$this->_response->appendBody ( "Token valid: " . $rowsUpdated );
				}
				
				break;
			case 'changepassword':
			    if ($token != $validToken || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			            $this->_response->appendBody ( "Email address is not valid" );
			        }
			        else
					    $this->_response->appendBody ( "Token not valid" );
				}
				else {
				    $db = Zend_Db_Table::getDefaultAdapter ();
				    
				    //check if this is a valid user:
                    $select = $db->select();
    				$select->from("users", array("id", "password", "salt"));
    				$select->where("id = ?", $user->id);
                    $results = $db->fetchAll($select);
                    
                    $salt = $results[0]['salt'];
                    $combined = $data['curpass'].$salt;
                    $submittedPassword = hash('sha256', $combined);
                    if ($submittedPassword == $results[0]['password']) { //password matches recorded hash
				    
				    $config = Zend_Registry::get("config"); 
				    $newSalt = hash('sha256', $config['salted_string'].uniqid(mt_rand(),true));
				    $newPass = hash('sha256', $data['newpass'].$newSalt);
				    $updateData = array(
									'password' => $newPass,
									'salt' => $newSalt,
									'email' => $data['email'],
                        		    'password_reset_token'      => new Zend_Db_Expr('NULL'),
									'password_reset_expires'  =>  new Zend_Db_Expr('NULL')
                        		    );
    				$n = $db->update('users', $updateData, 'id = '.$user->id.'');
				    
				    $this->_response->appendBody ( "Password changed" );
				    }
				    else {
				        $this->_response->appendBody ( "Current Password does not match our records. Please re-enter and try again. ");
				    }
				}
			    break;
			default :
				$this->_response->appendBody ( "Invalid Action " . $action );
				break;
		}
	
	}

}