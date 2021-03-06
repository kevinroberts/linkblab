<?php
class BlabsController extends Zend_Controller_Action
{
    public static $utils;
    
    protected function moveBlabsDown($count, $firstItem)
    {
        $db     = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from("subscriptions");
        $select->where("user_id = ?", $firstItem['user_id']);
        $select->order("display_order ASC");
        $results = $db->fetchAll($select);
        
        $end     = $count + 1;
        $shifter = array();
        
        foreach ($results as $row) {
            if ($row["display_order"] == 2) {
                $shifter["id"]            = $row["id"];
                $shifter["user_id"]       = $row["user_id"];
                $shifter["blab_id"]       = $row["blab_id"];
                $shifter["display_order"] = $row["display_order"];
                $data                     = array(
                    'user_id' => $firstItem['user_id'],
                    'blab_id' => $firstItem['blab_id']
                );
                $update                   = $db->update("subscriptions", $data, "id = " . $row["id"]);
            }
            if ($row["display_order"] > 2) {
                $data   = array(
                    'user_id' => $shifter['user_id'],
                    'blab_id' => $shifter['blab_id']
                );
                $update = $db->update("subscriptions", $data, "id = " . $row["id"]);
                
                $shifter["id"]            = $row["id"];
                $shifter["user_id"]       = $row["user_id"];
                $shifter["blab_id"]       = $row["blab_id"];
                $shifter["display_order"] = $row["display_order"];
            }
        }
        
        // Now insert the old last subscription item as a new row
        $data     = array(
            "user_id" => $shifter['user_id'],
            "blab_id" => $shifter['blab_id'],
            "display_order" => $end
        );
        $inserter = $db->insert("subscriptions", $data);
        
        return $inserter;
    }
    
    public function init()
    {
        self::$utils = new Application_Model_Utils();
        
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
        $page                 = $this->_getParam('page', 1);
        $auth                 = Zend_Auth::getInstance();
        $this->view->loggedIn = false;
        if ($auth->hasIdentity()) {
            $this->view->loggedIn = true;
            $userID               = $auth->getIdentity()->id;
        }
        $sortNumber = (isset($_GET['list'])) ? self::$utils->XssCleaner($_GET['list']) : '';
        $sortType   = (isset($_GET['sort'])) ? self::$utils->XssCleaner($_GET['sort']) : '';
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
            $blabs  = new Application_Model_BlabMapper();
            $result = $blabs->fetchAll();
            
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage($sortNumber);
            $paginator->setCurrentPageNumber($page);
            $this->view->paginator  = $paginator;
            $this->view->sortNumber = $sortNumber;
        } else {
            // Sort by sortType
            if ($sortType != 'frontpage') {
                $blabs  = new Application_Model_BlabMapper();
                $result = $blabs->fetchAll(null, $sortType);
                
                $paginator = Zend_Paginator::factory($result);
                $paginator->setItemCountPerPage($sortNumber);
                $paginator->setCurrentPageNumber($page);
            } else {
                // sort by front page status
                $blabs   = new Application_Model_BlabMapper();
                $user_ID = (isset($userID)) ? $userID : 6;
                $result  = $blabs->fetchAll(null, $sortType, null, $user_ID);
                
                $paginator = Zend_Paginator::factory($result);
                $paginator->setItemCountPerPage($sortNumber);
                $paginator->setCurrentPageNumber($page);
            }
        }
        
        
        $this->view->paginator  = $paginator;
        $this->view->sortNumber = $sortNumber;
        $this->view->sortType   = $sortType;
    }
    
    public function displayAction()
    {
        $commentPage       = $this->_request->getParam('comments');
        $commentPagePerm   = $this->_request->getParam('comment');
        // Check if this is a valid comment page request (it is not empty and has a numeric value)
        $isCommentPage     = (!empty($commentPage)) ? ((is_numeric($commentPage)) ? true : false) : false;
        $isCommentPagePerm = (!empty($commentPagePerm)) ? ((is_numeric($commentPagePerm)) ? true : false) : false;
        if ($isCommentPage || $isCommentPagePerm) {
            $this->view->token       = self::$utils->form_token(true);
            $this->view->commentPage = ($isCommentPage) ? $commentPage : $commentPagePerm;
            if ($isCommentPagePerm)
                $this->view->permLink = true;
        }
        // ELSE: This is a normal blab page with links
        else {
            
            $sort                 = $this->_request->getParam('sort');
            $sortNumber           = 25;
            $page                 = $this->_getParam('page', 1);
            $this->view->loggedIn = false;
            $auth                 = Zend_Auth::getInstance();
            $category             = $this->_request->getParam('category');
            // If no category was specified redirect them : 
            // you can change the default route for /b/:category in bootstrap  
            if (empty($category)) {
                return $this->_redirect("/index/notfound");
            }
            if ($auth->hasIdentity()) {
                $this->view->loggedIn = true;
                $userID               = $auth->getIdentity()->id;
            }
            //$this->view->token = self::$utils->form_token(true); // create unique token for dynamic login form
            // Check if this the requested blab exists:
            $db      = Zend_Db_Table::getDefaultAdapter();
            $select  = $db->select()->from("blabs", array(
                "id",
                "title"
            ))->where("title = ?", $category);
            $results = $db->fetchRow($select);
            if (empty($results)) {
                return $this->_redirect("/index/notfound?t=blab404");
                //return $this->_redirect("/index/notfound?t=blab404&m=".urlencode($category));
            }
            $blabID = $results['id'];
            
            // If random category --> select a random one:
            if ($category == "random") {
                $select = $db->select()->from("blabs", array(
                    'id',
                    'title'
                ))->where("id != ?", 24);
                $select->order(new Zend_Db_Expr('rand()'));
                $select->limit(1);
                $results = $db->fetchAll($select);
                return $this->_redirect("/b/" . $results[0]["title"]);
            }
            
            $this->view->category = $category;
            // Get blab category specific links... 
            $links                = new Application_Model_LinksMapper();
            
            if (empty($sort)) {
                $results = $links->fetchAll(500, $blabID, null, 'hot DESC');
            } else {
                switch ($sort) {
                    case 'hot':
                        $results = $links->fetchAll(500, $blabID, null, 'hot DESC');
                        break;
                    case 'controversial':
                        //$this->view->addJS = '$("#sortOptionsDropdown_link").text("controversial");'.PHP_EOL;
                        $results = $links->fetchAll(500, $blabID, null, 'controversy DESC');
                        break;
                    case 'top':
                        //$this->view->addJS = '$("#sortOptionsDropdown_link").text("top scoring");'.PHP_EOL;
                        $results = $links->fetchAll(500, $blabID, null, 'votes DESC');
                        break;
                    default:
                        $results = $links->fetchAll(500, $blabID, null, 'hot DESC');
                        break;
                }
            }
            if (!empty($results)) {
                $paginator = Zend_Paginator::factory($results);
                $paginator->setItemCountPerPage($sortNumber);
                $paginator->setCurrentPageNumber($page);
                
                $this->view->paginator  = $paginator;
                $this->view->pagenumber = $page;
                $this->view->sortNumber = $sortNumber;
            } else {
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
        if (!$auth->hasIdentity()) {
            return $this->_redirect("/login?msg=2&r=" . self::$utils->urlsafe_b64encode(self::$utils->curPageURL()));
        }
        $user    = $auth->getIdentity();
        $request = $this->getRequest();
        $form    = new Application_Form_Blab();
        
        
        if ($this->getRequest()->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
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
                
                $blab   = new Application_Model_Blab($insertCols);
                $mapper = new Application_Model_BlabMapper();
                $mapper->save($blab);
                // Add the user as a moderator of the new blab
                $db    = Zend_Db_Table::getDefaultAdapter();
                $data2 = array(
                    'user_id' => $user->id,
                    'lookup_id' => $mapper->lastInsertID,
                    'key' => "moderator",
                    'value' => 1
                );
                $db->insert('user_meta', $data2);
                
                // Direct them to their newly created blab!
                // for debug: $this->view->formData = $insertCols;
                return $this->_redirect('/b/' . strtolower($insertCols["title"]));
                
            } else {
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
        } else {
            return $this->_redirect("/index/notfound");
        }
        
        $userID = self::$utils->XssCleaner($this->_request->getParam('user'));
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
            $blabId = self::$utils->XssCleaner($this->_request->getParam('removeBlab'));
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
                
                
                
                // Remove the blab from their subscriptions
                foreach ($results as $row) {
                    if ($row["blab_id"] == $blabId) {
                        $delete = $db->delete("subscriptions", "id = " . $row["id"]);
                    }
                }
                
                // Re-number their blabs
                $results = $db->fetchAll($select);
                $count   = count($results);
                $i       = 1;
                foreach ($results as $row) {
                    $data   = array(
                        'display_order' => $i
                    );
                    $update = $db->update("subscriptions", $data, "id = " . $row["id"]);
                    $i++;
                }
                
                
            } else {
                return $this->_redirect("/index/notfound");
            }
            $this->view->message = "You've succesfully removed the following from your front page";
            $this->view->action  = $blabId;
        } else {
            // action is addBlab
            $blabId = self::$utils->XssCleaner($this->_request->getParam('addBlab'));
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
                $count   = count($results);
                
                $oldFirstItem = array();
                $i            = 0;
                
                if (isset($results[0]["id"])) {
                    
                    $oldFirstItem["id"]            = $results[0]["id"];
                    $oldFirstItem["user_id"]       = $results[0]["user_id"];
                    $oldFirstItem["blab_id"]       = $results[0]["blab_id"];
                    $oldFirstItem["display_order"] = $results[0]["display_order"];
                    // Update first row with new blab id
                    $data                          = array(
                        'blab_id' => $blabId,
                    );
                    $update                        = $db->update("subscriptions", $data, "id = " . $oldFirstItem["id"]);
                    
                    // shift all the other subscriptions down one row
                    $shifter = $this->moveBlabsDown($count, $oldFirstItem);
                }
                
				$this->view->message = "You've succesfully added the following to your front page";
                
            } else {
                return $this->_redirect("/index/notfound");
            }
            
        }
        
        $this->view->action  = $blabId;
    }
    
    public function submitAction()
    {
        /*	required login redirector    	
         *    reason: User must be logged in to submit new links
         */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            return $this->_redirect("/login?msg=2&r=" . self::$utils->urlsafe_b64encode(self::$utils->curPageURL()));
        }
        
        $isAdmin = false;
        if ($auth->getIdentity()->role == "administrator") {
            // dont display captcha for admin
            $isAdmin = true;
        }
        $isSelf = false; // is this a self post?
        $user   = $auth->getIdentity();
        $toBlab = $this->_request->getParam('to');
        $toBlab = (empty($toBlab)) ? '' : $toBlab;
        
        $form = new Application_Form_Link();
        
        if (!$isAdmin) {
            // dont display captcha for admin
            $recaptcha = new Zend_Service_ReCaptcha(RECAPTCHAPUBLIC, RECAPTCHAPRIVATE);
            $element   = $form->createElement('Captcha', 'ReCaptcha', array(
                'captcha' => array(
                    'captcha' => 'ReCaptcha',
                    'service' => $recaptcha
                )
            ));
            $element->setLabel("Enter CAPTCHA");
            $form->addElement($element);
            
        }
        
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
            $blabID = self::$utils->getBlabId($data['blab']);
            if (self::$utils->isReadOnly && $user->id !== self::$utils->userID) {
                $form->addError("Sorry, the Blab " . $data['blab'] . " has been set to read-only by its founder");
            }
            
            if ($form->isValid($data)) {
                if (!empty($data["description"])) {
                    $description = $data["description"];
                    // Strip any HTML from description
                    $description = self::$utils->strip_html($description);
                }
                // Form submission is valid - create the new link
                $insertCols = array(
                    "UserID" => $user->id,
                    "Title" => self::$utils->XssCleaner(trim($data["title"])),
                    "LinkUrl" => (!empty($data["link_url"])) ? $data["link_url"] : new Zend_Db_Expr('NULL'),
                    "Description" => (!empty($data["description"])) ? $description : new Zend_Db_Expr('NULL'),
                    "DateCreated" => date('Y-m-d H:i:s'),
                    "UpVotes" => 1,
                    "DownVotes" => 0,
                    "votes" => 1,
                    "BlabID" => $blabID,
                    "IsNsfw" => (strpos($data["title"], "NSFW") === false) ? 0 : 1, // if title contains nsfw then mark this link as such
                    "IsSelf" => ($isSelf) ? 1 : 0,
                    "Hot" => self::$utils->_hot(1, 0, time()),
                    "Controversy" => self::$utils->_controversy(1, 0),
                    "TimesReported" => 0,
                    "UrlTitle" => self::$utils->urlsafe_title($data['title'])
                );
                if (!empty($data["link_url"])) {
                    $insertCols["LinkUrl"] = $data["link_url"];
                    // Extract the domain of the url
                    $parts                 = parse_url($data["link_url"]);
                    $insertCols["Domain"]  = $parts['host'];
                }
                if (!empty($data["description"])) {
                    $insertCols["Description"] = $data["description"];
                }
                $link   = new Application_Model_Link($insertCols);
                $mapper = new Application_Model_LinksMapper();
                $mapper->save($link);
                return $this->_redirect('/b/' . strtolower($data['blab']) . '/comments/' . $mapper->lastInsertID . '/' . $link->urlTitle);
            } else {
                $toBlab = $data['blab'];
            }
        }
        $this->view->isSelf   = $isSelf;
        $this->view->form     = $form;
        $this->view->toBlab   = $toBlab;
        $this->view->isAdmin  = $isAdmin;
        $this->view->username = $user->username;
    }
    
    public function searchAction()
    {
        //The label property is displayed in the suggestion menu.
        // The value will be inserted into the input element after the user selected something from the menu.
        //the Autocomplete plugin expects that string to point to a URL resource that will return JSON data.
        //The request parameter "term" gets added to that URL
        
        $auth  = Zend_Auth::getInstance();
        //$token = self::$utils->XssCleaner($this->_request->getParam('token'));
        $token = $this->_request->getParam('token');
        
        if (!($this->_request->isXmlHttpRequest()) || !($auth->hasIdentity()) || empty($token)) {
            // form can only be accessed through ajax and with a user set and token
            return $this->_redirect("/index/notfound");
        }
        
        $user       = $auth->getIdentity();
        $validToken = sha1('7a8b6A894D4CBzaAEE0' . $user->username . date('D', time()));
        
        $term = (isset($_GET['term'])) ? self::$utils->XssCleaner($_GET['term']) : ''; // get posted content
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        // Check if user has a valid ajax token 
        if ($token != $validToken) {
            $myArray  = array(
                array(
                    'label' => 'Ajax token Error',
                    'value' => 'linkblab.com'
                ),
                array(
                    'label' => 'Try logging out/in',
                    'value' => 'linkblab.com'
                )
            );
            $jsonData = Zend_Json::encode($myArray);
            return $this->_response->appendBody($jsonData); // send them an error message
        }
        
        // Get autocomplete suggestions
        $db     = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from("blabs", array(
            "title",
            "head_title"
        ));
        $select->where("title LIKE '%" . $term . "%'");
        $select->limit(10);
        $results = $db->fetchAssoc($select);
        $myArray = array();
        foreach ($results as $row) {
            $myArray[] = array(
                'label' => $row['head_title'] . " (/b/" . $row['title'] . ')',
                'value' => $row['title']
            );
        }
        
        $jsonData = Zend_Json::encode($myArray);
        return $this->_response->appendBody($jsonData);
    }
    
    public function voteAction()
    {
        $auth = Zend_Auth::getInstance();
        if (!($this->_request->isXmlHttpRequest())) {
            // form can only be accessed through ajax 
            return $this->_redirect("/index/notfound");
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $voteResult = array(
            'message' => 'Uhroh',
            'success' => false,
            'error' => "login"
        );
        
        if (!($auth->hasIdentity())) {
            // User needs to be logged in to vote
            $voteResult = array(
                'message' => 'You need to be logged in to vote',
                'success' => false,
                'error' => "login"
            );
            $jsonData   = Zend_Json::encode($voteResult);
            return $this->_response->appendBody($jsonData);
        }
        // GET POSTED VOTE INFORMATION
        $data = $this->_request->getPost();
        
        $userID = $auth->getIdentity()->id;
        if (isset($data['link']) && isset($data['type'])) {
            if (is_numeric($data['link']) && is_numeric($data['type'])) {
                $voteType = ($data['type'] == 1) ? 'upVote' : 'downVote';
                switch ($voteType) {
                    case 'upVote':
                        $result = self::$utils->submitVote($userID, $data['link'], $voteType);
                        
                        if ($result['success']) {
                            $voteResult = array(
                                'message' => 'Success - upVote! ' . $result["message"],
                                'success' => true
                            );
                        } else {
                            $voteResult = array(
                                'message' => 'error! ' . $result["message"],
                                'success' => false
                            );
                            
                        }
                        
                        break;
                    case 'downVote':
                        $result = self::$utils->submitVote($userID, $data['link'], $voteType);
                        
                        if ($result['success']) {
                            $voteResult = array(
                                'message' => 'Success - downVote! ' . $result["message"],
                                'success' => true
                            );
                        } else {
                            $voteResult = array(
                                'message' => 'error! ' . $result["message"],
                                'success' => false
                            );
                            
                        }
                        
                        break;
                    default:
                        break;
                }
                
                
                
            } else {
                $voteResult = array(
                    'message' => 'You need to enter a valid link number and type',
                    'success' => false,
                    'error' => "param"
                );
                $jsonData   = Zend_Json::encode($voteResult);
                return $this->_response->appendBody($jsonData);
            }
            
            
            
        } // else is this a vote for a comment? (does it have the 'comment' variable POSTED)
        else if (isset($data['comment']) && isset($data['type'])) {
            if (is_numeric($data['comment']) && is_numeric($data['type'])) {
                $voteType = ($data['type'] == 1) ? 'upVote' : 'downVote';
                
                switch ($voteType) {
                    case 'upVote':
                        $result = self::$utils->submitCommentVote($userID, $data['comment'], $voteType);
                        
                        if ($result['success']) {
                            $voteResult = array(
                                'message' => 'Success - upVote! ' . $result["message"],
                                'success' => true
                            );
                        } else {
                            $voteResult = array(
                                'message' => 'error! ' . $result["message"],
                                'success' => false
                            );
                            
                        }
                        
                        break;
                    case 'downVote':
                        $result = self::$utils->submitCommentVote($userID, $data['comment'], $voteType);
                        
                        if ($result['success']) {
                            $voteResult = array(
                                'message' => 'Success - downVote! ' . $result["message"],
                                'success' => true
                            );
                        } else {
                            $voteResult = array(
                                'message' => 'error! ' . $result["message"],
                                'success' => false
                            );
                            
                        }
                        
                        break;
                    default:
                        break;
                }
                
                
                
            } else {
                $voteResult = array(
                    'message' => 'You need to enter a valid comment number and type',
                    'success' => false,
                    'error' => "param"
                );
                $jsonData   = Zend_Json::encode($voteResult);
                return $this->_response->appendBody($jsonData);
            }
        } else {
            // User needs to submit the correct parameters
            $voteResult = array(
                'message' => 'You need to enter a valid link number',
                'success' => false,
                'error' => "param"
            );
            $jsonData   = Zend_Json::encode($voteResult);
            return $this->_response->appendBody($jsonData);
        }
        
        $jsonData = Zend_Json::encode($voteResult);
        return $this->_response->appendBody($jsonData);
    }
    
    public function commentAction()
    {
        $db       = Zend_Db_Table::getDefaultAdapter();
        $auth     = Zend_Auth::getInstance();
        $comments = new Application_Model_Comments();
        if (!($this->_request->isXmlHttpRequest())) {
            // form can only be accessed through ajax 
            return $this->_redirect("/index/notfound");
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $Result = array(
            'message' => 'Uhroh',
            'success' => false,
            'error' => "login"
        );
        if (!($auth->hasIdentity())) {
            // User needs to be logged in to vote
            $Result   = array(
                'message' => 'You need to be logged in to vote',
                'success' => false,
                'error' => "login"
            );
            $jsonData = Zend_Json::encode($Result);
            return $this->_response->appendBody($jsonData);
        }
        
        // GET POSTED COMMENT INFORMATION
        $data = $this->_request->getPost();
        
        
        $userID = $auth->getIdentity()->id;
        
        if (isset($data['type']) && isset($data['commentID'])) {
            // check if this is the user's own comment
            $select  = $db->select()->from("comments", array(
                "user_id"
            ))->where("id = " . $data["commentID"])->limit(1);
            $results = $db->fetchRow($select);
            
            if ($data['type'] == 'delete' && is_numeric($data['commentID']) && $userID == $results['user_id']) {
                
                $updateVal = array(
                    'deleted' => 1
                );
                $update    = $db->update("comments", $updateVal, "id = " . $data["commentID"]);
                $Result    = array(
                    'success' => true
                );
                return $this->_response->appendBody(Zend_Json::encode($Result));
            }
        }
        if (isset($data['text']) && isset($data['type'])) {
            if (strlen($data['text']) > 10000) {
                // this comment is too long
                $Result   = array(
                    'message' => "this is too long (max: 10,000)",
                    'success' => false,
                    'error' => "tooLong"
                );
                $jsonData = Zend_Json::encode($Result);
                return $this->_response->appendBody($jsonData);
            }
            // Save new comment to DB and return insert ID (comment ID):
            $commentType = $data['type'];
            
            // Strip any HTML from comments
            $data['text'] = self::$utils->strip_html($data['text']);
            
            switch ($commentType) {
                case 'parent':
                    if (!isset($data['link_id'])) {
                        $Result = array(
                            'message' => 'error! there was a problem processing your request',
                            'success' => false
                        );
                        break;
                    }
                    $data['comment'] = $data['text'];
                    $data['link_id'] = $data['link_id'];
                    $data['user_id'] = $userID;
                    $result          = $comments->submitComment($commentType, $data);
                    
                    if ($result['success']) {
                        $Result = array(
                            'message' => $result["message"],
                            'comment' => $comments->formatComment($data['comment']), // return decoda result
                            'success' => true,
                            'commentID' => $result["id"]
                        );
                    } else {
                        $Result = array(
                            'message' => 'error! ' . $result["message"],
                            'success' => false
                        );
                        
                    }
                    
                    break;
                case 'reply':
                    if (!isset($data['link_id']) || !isset($data['comment_id'])) {
                        $Result = array(
                            'message' => 'error! there was a problem processing your request',
                            'success' => false
                        );
                        break;
                    }
                    $data['comment']    = $data['text'];
                    $data['link_id']    = $data['link_id'];
                    $data['comment_id'] = $data['comment_id'];
                    $data['user_id']    = $userID;
                    $result             = $comments->submitComment($commentType, $data);
                    
                    if ($result['success']) {
                        $Result = array(
                            'message' => $result["message"],
                            'comment' => $comments->formatComment($data['comment']), // return decoda result
                            'success' => true,
                            'commentID' => $result["id"]
                        );
                    } else {
                        $Result = array(
                            'message' => 'error! ' . $result["message"],
                            'success' => false
                        );
                        
                    }
                    break;
                case 'edit':
                    $data['comment']    = $data['text'];
                    $data['comment_id'] = $data['comment_id'];
                    $data['user_id']    = $userID;
                    $result             = $comments->submitComment($commentType, $data);
                    
                    if ($result['success']) {
                        $Result = array(
                            'message' => "saved!",
                            'comment' => $comments->formatComment($data['comment']), // return decoda result
                            'success' => true,
                            'commentID' => $data["commentID"]
                        );
                    } else {
                        $Result = array(
                            'message' => 'error! ' . $result["message"],
                            'success' => false
                        );
                    }
                    
                    break;
                default:
                    break;
            }
            
        } else {
            $Result   = array(
                'message' => 'You need to enter a valid link number and type',
                'success' => false,
                'error' => "param"
            );
            $jsonData = Zend_Json::encode($Result);
            return $this->_response->appendBody($jsonData);
            
            
        }
        
        $jsonData = Zend_Json::encode($Result);
        return $this->_response->appendBody($jsonData);
    }
    
    public function previewAction()
    {
        $comments = new Application_Model_Comments();
        if (!($this->_request->isXmlHttpRequest()) || $comments->loggedIn == false) {
            // form can only be accessed through ajax 
            return $this->_redirect("/index/notfound");
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        // GET POSTED INFORMATION
        $data = $this->_request->getPost();
        
        $data    = self::$utils->XssCleaner($data['data']);
        $data    = $comments->formatComment($data);
        $content = <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>preview</title>
<script type="text/javascript" src="/js/jquery-latest.min.js"></script>
<script src="/js/jquery-ui-latest.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="/css/cupertino/jquery-ui-lastest.custom.css" type="text/css" />
<link rel="stylesheet" href="/css/global.css" type="text/css" />
<style type="text/css">
html, body {
background: none repeat scroll 0pt 0pt rgb(255, 255, 255);
}
#comment-1 {
	margin-top: 10px;
}
ol, ul {
   padding: 0 15px 15px 40px;
}
</style>
</head>
<body>

<div class="comment" id="comment-0">		
		<div class="midcol" style="display: block;">
		<a id="recent-link328-up" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-arrow-n voted"></span></a>
		<a id="recent-link328-down" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
		</div>	
	<div class="entry">

		<div class="noncollapsed" style="display: block;">
		<p class="tagline">
			<a href="JavaScript:void(0);" class="author submitter">you</a>

			<span class="userattrs"> [<a class="submitter" title="submitter" href="JavaScript:void(0);">S</a>]</span>
			<span class="score downVotes">0</span>
			<span class="score upVotes">1</span>
			<span class="score total voted">1 point</span>
			just now
			<a href="JavaScript:void(0);" class="expand" title="collapse">[-]</a>
		</p>
		<div class="md">
			<div>
			$data
			</div>
			
		</div>
		<ul style="visibility:hidden;" class="flat-list buttons">
		<li class="first"><a href="" class="bylink" rel="nofollow">permalink</a></li>
		
		</ul>
	 </div>
	</div>
	
	</div>
</body>
</html>
EOT;
        
        return $this->_response->appendBody($content);
        
    }
    public function domainAction()
    {
        $utils                = new Application_Model_Utils();
        $page                 = $this->_getParam('page', 1);
        $auth                 = Zend_Auth::getInstance();
        $sortNumber           = 25;
        $this->view->loggedIn = false;
        if ($auth->hasIdentity()) {
            $this->view->loggedIn = true;
            $userID               = $auth->getIdentity()->id;
        }
        $sort   = $this->_request->getParam('sort');
        $domain = $utils->XssCleaner($this->_request->getParam('domainfqdn'));
        
        $links = new Application_Model_LinksMapper();
        
        $this->view->sort   = $sort;
        $this->view->domain = $domain;
        $this->view->page   = $page;
        
        switch ($sort) {
            case 'hot':
                $results = $links->fetchAll(500, null, null, 'hot DESC', "domain = '" . $domain . "'");
                break;
            case 'controversial':
                $this->view->addJS = '$("#sortOptionsDropdown_link").text("controversial");' . PHP_EOL;
                $results           = $links->fetchAll(500, null, null, 'controversy DESC', "domain = '" . $domain . "'");
                break;
            case 'top':
                $this->view->addJS = '$("#sortOptionsDropdown_link").text("top scoring");' . PHP_EOL;
                if (isset($_GET["t"]) && $_GET["t"] == "all") {
                    $results = $links->fetchAll(500, null, null, 'votes DESC', "CURDATE() >= date_created && domain = '" . $domain . "'", 180);
                } else {
                    $results = $links->fetchAll(500, null, null, 'votes DESC', "domain = '" . $domain . "'");
                }
                break;
            case 'saved':
                // Change to saved links only when implemented
                return $this->_redirect("/index/notfound?t=notimpl");
                break;
            default:
                $results = $links->fetchAll(500, null, null, 'hot DESC', "domain = '" . $domain . "'");
                break;
        }
        
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($sortNumber);
        $paginator->setCurrentPageNumber($page);
        
        $this->view->upcoming   = $upcoming;
        $this->view->paginator  = $paginator;
        $this->view->pagenumber = $page;
        $this->view->sortNumber = $sortNumber;
        
    }
    
}