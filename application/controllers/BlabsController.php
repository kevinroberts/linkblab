<?php

class BlabsController extends Zend_Controller_Action
{

    protected function moveBlabsDown($count, $firstItem)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
                        		$select = $db->select();
                        		$select->from("subscriptions");
                        		$select->where("user_id = ?", $firstItem['user_id']);
                        		$select->order("display_order ASC");
                        		$results = $db->fetchAll($select);
                        		
                        		$end = $count + 1;
                        		$shifter = array();
                        		
                        		foreach ($results as $row) {
                        			if ($row["display_order"] == 2) {
                        				$shifter["id"] = $row["id"];
                        				$shifter["user_id"] = $row["user_id"];
                        				$shifter["blab_id"] = $row["blab_id"];
                        				$shifter["display_order"] = $row["display_order"];
                        				$data = array(
                        					'user_id'      => $firstItem['user_id'],
                            				'blab_id'      => $firstItem['blab_id'],
                            				);
                            			$update = $db->update("subscriptions", $data, "id = ".$row["id"]);
                        			}
                        			if ($row["display_order"] > 2) {
                        				$data = array(
                        					'user_id'      => $shifter['user_id'],
                            				'blab_id'      => $shifter['blab_id'],
                            				);
                            			$update = $db->update("subscriptions", $data, "id = ".$row["id"]);
                        				
                            			$shifter["id"] = $row["id"];
                        				$shifter["user_id"] = $row["user_id"];
                        				$shifter["blab_id"] = $row["blab_id"];
                        				$shifter["display_order"] = $row["display_order"];
                        			}	
                        		}
                        		
                        		// Now insert the old last subscription item as a new row
                        		$data = array(
                        			"user_id" => $shifter['user_id'],
                        			"blab_id" => $shifter['blab_id'],
                        			"display_order" => $end
                        		);
                        		$inserter = $db->insert("subscriptions", $data);
                        
                        		return $inserter;
    }

    public function init()
    {
        /* Initialize action controller here */
                                                		/* custom route --> see bootstrap file
                                                		 * 
                                                		 * Original URL Route
                                                		 * 	/controller/action/param1/value1 -->
                                                		 *
                                                		 *  /blabs/display/category/pics
                                                		 * 
                                                		 * Rewrite to
                                                		 * 	/b/pics
                                                		 *  	with b being short for 'blabs'
                                                		 *  
                                                		 */
    }

    public function indexAction()
    {
        $page = $this->_getParam('page',1);
                            	$utils = new Application_Model_Utils();
                            	$auth = Zend_Auth::getInstance();
                            	$this->view->loggedIn = false;
                            	if ($auth->hasIdentity())
                        		{
                        			$this->view->loggedIn = true;
                        			$userID = $auth->getIdentity()->id;
                        		}
                        		$sortNumber = (isset($_GET['list'])) ? $utils->XssCleaner($_GET['list']) : '';
                        		$sortType = 	(isset($_GET['sort'])) ? $utils->XssCleaner($_GET['sort']) : '';	
                        			switch ($sortNumber) {
                        				case '10':
                        				$sortNumber = 10;
                        				break;
                        				case '30':
                        				$sortNumber = 30;
                        				break;
                        				case 'all':
                        					$sortNumber = 100;
                        					break;
                        				default:
                        					$sortNumber = 10;
                        				break;
                        			}
                        		if (!empty($sortType)) {
                        			switch ($sortType) {
                        				case 'alpha':
                        				$sortType = 'title ASC';
                        				break;
                        				case 'date':
                        					$sortType = 'date_created DESC';
                        					break;
                        				case 'frontpage':
                        					$sortType = 'frontpage';
                        					break;
                        				default:
                        					$sortType = 'title ASC';;
                        				break;
                        			}
                        		}
                        
                        		
                        		
                        		if (empty($sortType)) {
                        			$blabs = new Application_Model_BlabMapper();
                        	        $result = $blabs->fetchAll();
                        	
                        	        $paginator = Zend_Paginator::factory($result);
                        	        $paginator->setItemCountPerPage($sortNumber);
                        	        $paginator->setCurrentPageNumber($page);
                        	        $this->view->paginator=$paginator;
                        	        $this->view->sortNumber = $sortNumber;
                        		}
                        		else
                        		{
                        			// Sort by sortType
                        			if ($sortType != 'frontpage') {
                        			$blabs = new Application_Model_BlabMapper();
                        	        $result = $blabs->fetchAll(null, $sortType);
                        	
                        	        $paginator = Zend_Paginator::factory($result);
                        	        $paginator->setItemCountPerPage($sortNumber);
                        	        $paginator->setCurrentPageNumber($page);
                        			}
                        			else {
                        				// sort by front page status
                        			$blabs = new Application_Model_BlabMapper();
                        			$user_ID = (isset($userID)) ? $userID : 6;
                        	        $result = $blabs->fetchAll(null, $sortType, null, $user_ID);
                        	
                        	        $paginator = Zend_Paginator::factory($result);
                        	        $paginator->setItemCountPerPage($sortNumber);
                        	        $paginator->setCurrentPageNumber($page);
                        			}
                        		}
                        
                        
                        	        $this->view->paginator=$paginator;
                        	        $this->view->sortNumber = $sortNumber;
                        	        $this->view->sortType = $sortType;
    }

    public function displayAction()
    {
    	$commentPage = $this->_request->getParam('comments');
    	// Check if this is a valid comment page request (it is not empty and has a numeric value)
    	$isCommentPage = (!empty($commentPage)) ? ((is_numeric($commentPage)) ? true : false) : false;
    	if ($isCommentPage) {
			$this->view->commentPage = $commentPage;
    	}
    	else // else render normal blab page with links
    	{
    	    	
        $utils = new Application_Model_Utils();
        $sort = $this->_request->getParam('sort'); $sortNumber = 25;
        $page = $this->_getParam('page',1);
        $this->view->loggedIn = false;
		$auth = Zend_Auth::getInstance();
        $category = $this->_request->getParam('category');
        // If no category was specified redirect them : 
        // you can change the default route for /b/:category in bootstrap  
        if (empty($category)) 
        {
        	return $this->_redirect("/index/notfound");
        }
        if ($auth->hasIdentity())
        {
        	$this->view->loggedIn = true;
            $userID = $auth->getIdentity()->id;
		}
		$this->view->token = $utils->form_token(true); // create unique token for dynamic login form
        // Check if this the requested blab exists:
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()->from("blabs", array("id","title"))->where("title = ?", $category);
        $results = $db->fetchRow($select);
        if (empty($results))
        {
        	return $this->_redirect("/index/notfound?t=blab404");
            //return $this->_redirect("/index/notfound?t=blab404&m=".urlencode($category));
        }
        $blabID = $results['id'];
        
		// If random category --> select a random one:
        if ($category == "random") 
        {
        	$select = $db->select()->from("blabs", array('id', 'title'))->where("id != ?", 24);
            $select->order(new Zend_Db_Expr('rand()'));
            $select->limit(1);
			$results = $db->fetchAll($select);
            return $this->_redirect("/b/".$results[0]["title"]);
        }
                                    
		$this->view->category = $category;
		// Get blab category specific links... 
		$links = new Application_Model_LinksMapper();
		
		if (empty($sort)) 
		{
        	$results = $links->fetchAll(500,$blabID, null, 'hot DESC');
        }
        else 
        {
        	switch ($sort) {
            	case 'hot':
	            	$results = $links->fetchAll(500,$blabID, null, 'hot DESC');
	                break;
                case 'controversial':
                	$this->view->addJS = '$("#sortOptionsDropdown_link").text("controversial");'.PHP_EOL;
	                $results = $links->fetchAll(500,$blabID, null, 'controversy DESC');
	                break;
                case 'top':
                	$this->view->addJS = '$("#sortOptionsDropdown_link").text("top scoring");'.PHP_EOL;
	                $results = $links->fetchAll(500,$blabID, null, 'votes DESC');
	                break;
                default:
	            	$results = $links->fetchAll(500,$blabID, null, 'hot DESC');
	                break;
				}	
		}
		if (!empty($results)) { 
		$paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($sortNumber);
        $paginator->setCurrentPageNumber($page);
                
        $this->view->paginator=$paginator;
        $this->view->pagenumber = $page;
        $this->view->sortNumber = $sortNumber;
		}
		else {
		// NO links have been added to this blab yet:
		$this->view->noLinks = true;			
		}
    }
		
    }

    public function createAction()
    {
        /*	required login redirector    	
                                         *    reason: User must be logged in to create new Blabs
                                         */
                                             $auth = Zend_Auth::getInstance();
                                             $utils = new Application_Model_Utils();
                                                                    	if (!$auth->hasIdentity())
                                                                		{
                                                                			return $this->_redirect("/auth/login?msg=2&r=".urlencode($utils->curPageURL()));
                                                                			//header('Location: http://'.$_SERVER['SERVER_NAME'].'/auth/login?msg=2&r='.urlencode($this->curPageURL()));
                                                                			 //$this->_helper->redirector('/auth/login?r='.urlencode($this->curPageURL()));
                                                                		}
                                             $user = $auth->getIdentity();
                                             $request = $this->getRequest();
                                             $form = new Application_Form_Blab();
                                             
                                             
                                             if ($this->getRequest()->isPost()) {
                                             	$data = $this->_request->getPost();
                                             		if ($form->isValid($data))
                                                		{
                                                		// Form submission is valid - create the new blab				
                                                		
                                                		$insertCols = array(
                                                			"userID" => $user->id,
                                                			"title" => $data["title"],
                                                			"headTitle" => $data["head_title"]
                                                		);
                                                		if (!empty($data["description"])) {
                                                			$insertCols["description"] = $data["description"];
                                                			//array_push($insertCols["description"], $data["description"]);
                                                		}
                                                		
                                                		$blab = new Application_Model_Blab($insertCols);
                                                		$mapper = new Application_Model_BlabMapper();
                                                		$mapper->save($blab);
                                                		// Add the user as a moderator of the new blab
                                                		$db = Zend_Db_Table::getDefaultAdapter();
                                                		$data2 = array(
                                        								'user_id'      => $user->id,
                                        								'lookup_id' =>  $mapper->lastInsertID,
                                        								'key' => "moderator",
                                        								'value' => 1
                                        								);
                                        				$db->insert('user_meta',$data2);
                                                		
                                                		// Direct them to their newly created blab!
                                                		   // for debug: $this->view->formData = $insertCols;
                                                		return $this->_redirect('/b/'.strtolower($insertCols["title"]));
                                                						
                                                		}
                                                	else 
                                                	{
                                                		// Give them their blab title if it was a duplicate
                                                		$this->view->blabTitle = $data["title"];
                                                	}
                                             }
                                             
                                             $this->view->form = $form;
    }

    public function frontpageAction()
    {
        /*  // ajaxify this function later?   
                         	* 	if(!($this->_request->isXmlHttpRequest())) {
                            		// form can only be accessed through ajax
                            		return $this->_redirect("/index/notfound");
                            	}*/
                            	
                            	// user must be logged in to change front page
                                 $auth = Zend_Auth::getInstance();
                                	 if ($auth->hasIdentity()) {
                                	 	$loggedInId = $auth->getIdentity()->id;
                                	 }
                                	 else {
                                	 	return $this->_redirect("/index/notfound");
                                	 }
                            	
                                $utils = new Application_Model_Utils();
                                $userID = $utils->XssCleaner($this->_request->getParam('user'));
                                $blabId = null;
                                
                                // redirect if no user was specified
                                if (empty($userID)) {
                                	return $this->_redirect("/index/notfound");
                                }
                                //removeBlab
                                $action = $this->_request->getParam('removeBlab');
                                $action = (empty($action)) ? "addBlab" : "removeBlab";
                                
                                if ($action == "removeBlab") {
                               	// action is to remove blab from user's homepage
                                	$blabId = $utils->XssCleaner($this->_request->getParam('removeBlab'));
                                	// see if user is the same one logged in
                        			if ($loggedInId == $userID) {
                        				// user is the same add the blab to front page	
                        				$db = Zend_Db_Table::getDefaultAdapter();
                        				
                        				// Check if blab already is on their frontpage
                        				$select = $db->select();
                        				$select->from("subscriptions");
                        				$select->where("user_id = ?", $loggedInId);
                        				$select->where("blab_id = ?", $blabId);
                        				$results = $db->fetchOne($select);
                        				
                        				if (empty($results)) {
                        					return $this->_redirect("/index/notfound?err=blabidIsNotAlreadyonFrontPage");
                        				}
                        				
                        				$select = $db->select();
                        				$select->from("subscriptions");
                        				$select->where("user_id = ?", $loggedInId);
                        				$select->order("display_order ASC");
                        
                        				$results = $db->fetchAll($select);
                        				
                        				if (count($results) < 4) {
                        					$msg = "You must maintain at least 3 items on your front page. Please add some more first.";
                        					return $this->_redirect("/index/notfound?m=".urlencode($msg));
                        				}
                        				
                        				// Remove the blab from their subscriptions
                        				foreach ($results as $row) {
                        					if ($row["blab_id"] == $blabId) {
                        						$delete = $db->delete("subscriptions", "id = ".$row["id"]);
                        					}
                        				}
                        				
                        				// Re-number their blabs
                        				$results = $db->fetchAll($select);
                        				$count = count($results);
                        				$i = 1;
                        				foreach ($results as $row) {
                        					$data = array(
                            				'display_order'      => $i
                            				);
                            			 $update = $db->update("subscriptions", $data, "id = ".$row["id"]);
                            			 $i++;
                        				}
                        				
                        				
                        			}
                        			else {
                        				return $this->_redirect("/index/notfound");
                        			}
                                	$this->view->message = "You've succesfully removed the following from your front page";
                                	$this->view->action = $blabId;
                                }
                                else {
                                // action is addBlab
                                $blabId = $utils->XssCleaner($this->_request->getParam('addBlab'));
                                	if ($loggedInId == $userID) {
                        			// user is the same add the blab to front page	
                                	$db = Zend_Db_Table::getDefaultAdapter();
                        				
                        				// Check if blab already is on their frontpage
                        				$select = $db->select();
                        				$select->from("subscriptions");
                        				$select->where("user_id = ?", $loggedInId);
                        				$select->where("blab_id = ?", $blabId);
                        				$results = $db->fetchOne($select);
                        				
                        				if (!empty($results)) {
                        					return $this->_redirect("/index/notfound?err=blabidIsAlreadyonFrontPage");
                        				}
                        				
                                		$select = $db->select();
                        				$select->from("subscriptions");
                        				$select->where("user_id = ?", $loggedInId);
                        				$select->order("display_order ASC");
                        
                        				$results = $db->fetchAll($select);
                        				$count = count($results);
                        				
                        				$oldFirstItem = array();
                        				$i = 0;
                        				
                        				if (isset($results[0]["id"])) {
                        						
                        					$oldFirstItem["id"] = $results[0]["id"];
                        					$oldFirstItem["user_id"] = $results[0]["user_id"];
                        					$oldFirstItem["blab_id"] = $results[0]["blab_id"];
                        					$oldFirstItem["display_order"] = $results[0]["display_order"];
                        					// Update first row with new blab id
                        					$data = array(
                            				'blab_id'      => $blabId
                            				);
                        					$update = $db->update("subscriptions", $data, "id = ".$oldFirstItem["id"]);
                        					
                        					// shift all the other subscriptions down one row
                        					$shifter = $this->moveBlabsDown($count, $oldFirstItem);
                        				}
                        				
                        			}
                        			else {
                        				return $this->_redirect("/index/notfound");
                        			}
                        			
                                }
                                
                                
                                    $this->view->message = "You've succesfully added the following to your front page";
                                	$this->view->action = $blabId;
    }

    public function submitAction()
    {
        /*	required login redirector    	
                        *    reason: User must be logged in to submit new links
                        */
                        $auth = Zend_Auth::getInstance();
                        $utils = new Application_Model_Utils();
                		if (!$auth->hasIdentity())
                        {
                        	return $this->_redirect("/auth/login?msg=2&r=".urlencode($utils->curPageURL()));
                        	//header('Location: http://'.$_SERVER['SERVER_NAME'].'/auth/login?msg=2&r='.urlencode($this->curPageURL()));
                        }
                        $isSelf = false; // is this a self post?
                        $user = $auth->getIdentity();
                        $toBlab = $this->_request->getParam('to');
                        $toBlab = (empty($toBlab)) ? '' : $toBlab;
                        
                        $form = new Application_Form_Link();
                        
                        if ($this->getRequest()->isPost()) {
                         	$data = $this->_request->getPost();
                         	if ($data['isSelf'] == 1) {
                         		$isSelf = true;
                         	}
                         	
                         	// Check if this submission is not text-based and user did not speficy a URL
                         	if ($isSelf == false && empty($data['link_url'])) {
                         		$form->addError("You must enter a URL for your submission");
                         	}
                         	// Check if the requested blab is read-only
                         	$blabID = $utils->getBlabId($data['blab']);
                         	if ($utils->isReadOnly && $user->id !== $utils->userID) 
                         	{
                         		$form->addError("Sorry, the Blab ".$data['blab']." has been set to read-only by its founder");
                         	}
                         	
                         	if ($form->isValid($data))
                			{
                				if (!empty($data["description"])) {
										$description = strip_tags($data["description"]);
                				}
                				// Form submission is valid - create the new link
                				$insertCols = array(
        							"UserID" => $user->id,
                                    "Title" => $utils->XssCleaner($data["title"]),
                                    "LinkUrl" => (!empty($data["link_url"])) ? $data["link_url"] : new Zend_Db_Expr('NULL'),
                                    "Description" => (!empty($data["description"])) ? $description : new Zend_Db_Expr('NULL'),
                					"DateCreated" => date('Y-m-d H:i:s'),
                					"UpVotes" => 1,
                					"DownVotes" => 0,
                					"votes" => 1,
                					"BlabID" => $blabID,
                					"IsNsfw" => (strpos($data["title"], "NSFW") === false) ? 0 : 1, // if title contains nsfw then mark this link as such
                					"IsSelf" => ($isSelf) ? 1 : 0,
                					"Hot" => $utils->_hot(1, 0, time()),
                					"Controversy" => $utils->_controversy(1, 0),
                					"TimesReported" => 0
                                    );
                                    if (!empty($data["link_url"])) {
                                    	$insertCols["LinkUrl"] = $data["link_url"];
                                    	// Extract the domain of the url
                                    	$parts = parse_url($data["link_url"]);
                                    	$insertCols["Domain"] = $parts['host'];
                                    }
                                    if (!empty($data["description"])) {
                                    	$insertCols["Description"] = $data["description"];
                                    }
                                 $link = new Application_Model_Link($insertCols);
                                 $mapper = new Application_Model_LinksMapper();
                                 $mapper->save($link);
                            	 return $this->_redirect('/b/'.strtolower($data['blab']));
                			}
                			else {
                				$toBlab = $data['blab'];
                			}
                         }
                        $this->view->isSelf = $isSelf;
                        $this->view->form = $form;
                        $this->view->toBlab = $toBlab;
                        $this->view->username = $user->username;
    }

    public function searchAction()
    {
        //The label property is displayed in the suggestion menu.
            	// The value will be inserted into the input element after the user selected something from the menu.
            	//the Autocomplete plugin expects that string to point to a URL resource that will return JSON data.
            	//The request parameter "term" gets added to that URL
            	
            	$auth = Zend_Auth::getInstance();
            	$utils = new Application_Model_Utils();
            	//$token = $utils->XssCleaner($this->_request->getParam('token'));
            	$token = $this->_request->getParam('token');
            	
             	 	if(!($this->_request->isXmlHttpRequest()) || !($auth->hasIdentity()) || empty($token)) {
            		// form can only be accessed through ajax and with a user set and token
            		return $this->_redirect("/index/notfound");
            		}
            		
            		$user = $auth->getIdentity();
            		$validToken = sha1('7a8b6A894D4CBzaAEE0'.$user->username.date('D', time() ));
            		
            		$term = (isset($_GET['term'])) ? $utils->XssCleaner($_GET['term']) : ''; // get posted content
            		
            		$this->_helper->layout->disableLayout();
          	    	$this->_helper->viewRenderer->setNoRender();
            		
          	    	// Check if user has a valid ajax token 
          	    	if ($token != $validToken) {
        				 $myArray = array(
                     	array(
          	    		'label' => 'Ajax token Error',
                      	'value' => 'linkblab.com'),
                  		 array(
                  		 'label' => 'Try logging out/in',
                      	 'value' => 'linkblab.com'
                  		 ));  $jsonData = Zend_Json::encode($myArray);
        			return $this->_response->appendBody($jsonData); // send them an error message
          	    	}
          	    	
          	    	// Get autocomplete suggestions
          	    	$db = Zend_Db_Table::getDefaultAdapter();
          	    	$select = $db->select();
          	    	$select->from("blabs", array("title","head_title"));
          	    	$select->where("title LIKE '%".$term."%'");
          	    	$select->limit(10);
          	    	$results = $db->fetchAssoc($select);
            		$myArray = array();
          	    	foreach ($results as $row) {
          	    		 $myArray[] = array(
          	    		 	'label' => $row['head_title']." (/b/".$row['title'].')',
                      		'value' => $row['title']
          	    		 	);
          	    		}
                  		 
                  		 $jsonData = Zend_Json::encode($myArray);
          	    	return $this->_response->appendBody($jsonData);
    }

    public function voteAction()
    {
        $auth = Zend_Auth::getInstance();
        $utils = new Application_Model_Utils();
        if(!($this->_request->isXmlHttpRequest())) {
        // form can only be accessed through ajax 
        return $this->_redirect("/index/notfound");
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
  
        $voteResult = array(
        	'message' => 'Uhroh',
        	'success' =>  false ,
        	'error' => "login"
        );
             
        if (!($auth->hasIdentity())) {
        // User needs to be logged in to vote
        $voteResult = array(
        	'message' => 'You need to be logged in to vote',
        	'success' =>  false ,
        	'error' => "login"
        );
        $jsonData = Zend_Json::encode($voteResult);
        return $this->_response->appendBody($jsonData);
        }
        // GET POSTED VOTE INFORMATION
        $data = $this->_request->getPost();
        /*$data = array(
        	'type' => $_GET['type'],
        	'link' => $_GET['link']
        );*/
        $userID = $auth->getIdentity()->id;
        if (isset($data['link']) && isset($data['type'])) {
        	if (is_numeric($data['link']) && is_numeric($data['type'])) {
        		$voteType = ($data['type'] == 1) ? 'upVote' : 'downVote';
        		
        		switch ($voteType) {
        			case 'upVote':
        				$result = $utils->submitVote($userID, $data['link'], $voteType);
 
        				if ($result['success']) {
        				$voteResult = array(
        				'message' => 'Success - upVote! ' . $result["message"],
        				'success' =>  true         	
       			 		);	
        				}
        				else {
        				 $voteResult = array(
        				'message' => 'error! ' . $result["message"],
        				'success' =>  false    	
       			 		);		
        					
        				}
			
        				break;
        			case 'downVote':
        				$result = $utils->submitVote($userID, $data['link'], $voteType);
        				
        				if ($result['success']) {
        				$voteResult = array(
        				'message' => 'Success - upVote! ' . $result["message"],
        				'success' =>  true         	
       			 		);	
        				}
        				else {
        				 $voteResult = array(
        				'message' => 'error! ' . $result["message"],
        				'success' =>  false    	
       			 		);		
        					
        				}
        				
        				break;
        			default: break;
        		}
        		

        		
        	}
        	else {
        		 $voteResult = array(
        	'message' => 'You need to enter a valid link number and type',
        	'success' =>  false ,
        	'error' => "param"
        		);
        	$jsonData = Zend_Json::encode($voteResult);
        	return $this->_response->appendBody($jsonData);
        	}
        	
        	

        }
        else {
        // User needs to submit the correct parameters
        $voteResult = array(
        	'message' => 'You need to enter a valid link number',
        	'success' => false ,
        	'error' => "param"
        );
        $jsonData = Zend_Json::encode($voteResult);
        return $this->_response->appendBody($jsonData);
        }
        
        $jsonData = Zend_Json::encode($voteResult);
        return $this->_response->appendBody($jsonData);
         
    }


}

