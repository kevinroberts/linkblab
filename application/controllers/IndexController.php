<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $sort = $this->_request->getParam('sort');
            	
                $page = $this->_getParam('page',1);
                        $this->view->loggedIn = false;
                        $sortNumber = 25;
                		$auth = Zend_Auth::getInstance();
                		$utils = new Application_Model_Utils();
                		
                        if ($auth->hasIdentity())
                        {
                        	$this->view->loggedIn = true;
                            $userID = $auth->getIdentity()->id;
                        }
                        
                        $links = new Application_Model_LinksMapper();
                         
                        if (empty($sort)) {
                       
                        $results = $links->fetchAll(500,null, null, 'hot DESC');
                        }
                        else 
                        {
                        switch ($sort) {
                                case 'hot':
        	                        $results = $links->fetchAll(500,null, null, 'hot DESC');
        	                        break;
                                case 'controversial':
                                	$this->view->addJS = '$("#sortOptionsDropdown_link").text("controversial");'.PHP_EOL.
                                	'$("#promotedAndUpcoming").hide().after("<br />");';
        	                        $results = $links->fetchAll(500,null, null, 'controversy DESC');
        	                        break;
                                case 'top':
                                	$this->view->addJS = '$("#sortOptionsDropdown_link").text("top scoring");'.PHP_EOL.
                                	'$("#promotedAndUpcoming").hide().after("<br />");';
                                	if (isset($_GET["t"]) && $_GET["t"] == "all") {
                                	    $results = $links->fetchAll(500,null, null, 'votes DESC', 'CURDATE() >= date_created', 180);
                            	    }
                            	    else {
                            	       $results = $links->fetchAll(500,null, null, 'votes DESC'); 
                            	    }
        	                        break;
                                case 'saved':
                                	// Change to saved links only when implemented
        	                        return $this->_redirect("/index/notfound?t=notimpl");
        	                        break;
                                default:
        	                        $results = $links->fetchAll(500,null, null, 'hot DESC');
        	                        break;
                                }
                        	
                        	
                        	
                        }
                        
                        
                        $upcoming = $links->fetchAll(1,null, null, 'rand()', 'DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= date_created');
                        if (empty($upcoming)) { // fall back on older entries if nothing recent is found
                        	$upcoming = $links->fetchAll(1,null, null, 'rand()', "votes BETWEEN 0 AND 5");
                        }
                        $paginator = Zend_Paginator::factory($results);
                        $paginator->setItemCountPerPage($sortNumber);
                        $paginator->setCurrentPageNumber($page);
                        
                        $this->view->upcoming = $upcoming;
                        $this->view->paginator=$paginator;
                        $this->view->pagenumber = $page;
                        $this->view->sortNumber = $sortNumber;
    }

    public function notfoundAction()
    {
        $this->view->errorMsg = '';
                    	
                    	$msg = 'Sorry, your requested Blab could not be found	
                <br /><br />
                Please check the name to see if you\'ve misstyped something.';
                       $msg2 = 'Sorry, your requested destination has not been implemented yet.	
                <br /><br />
                Please check back again later';
                        if (isset($_GET['t'])) {
                        	
                        	if ($_GET['t'] == 'blab404') 
                        	$this->view->errorMsg = $msg;
                        	else if ($_GET['t'] == 'notimpl') 
                        	$this->view->errorMsg = $msg2;
                        	
                        }
                        if (isset($_GET['m'])) {
                        	$utils = new Application_Model_Utils();
                        	$this->view->errorMsg = $utils->XssCleaner(urldecode($_GET['m']));
            			}
    }
    
    public function searchAction() {
        $this->view->loggedIn = false;
        $page = $this->_getParam('page',1);
        $sortNumber = 25;
        $utils = new Application_Model_Utils();
        $config = Zend_Registry::get("config");
        $auth = Zend_Auth::getInstance();
		
        if ($auth->hasIdentity())
        {
        	$this->view->loggedIn = true;
            $userID = $auth->getIdentity()->id;
        }
        
        if(!isset($_GET['q']) || empty($_GET['q']) ) {  
          $q = $config['default_search'];  
        } 
        else {
            $q = $_GET['q'];
        }
        
        $searchString = strip_tags(trim(urldecode($q)));
        
        $wordsInSearch = str_word_count($searchString, 1);
        $numberWords = count($wordsInSearch);
        
        
        $this->view->defaultTerm = $config['default_search'];
        $this->view->searchTerm = $searchString;
        

            $searchString = $utils->XssCleaner($searchString);
            $db = Zend_Db_Table::getDefaultAdapter();
            $linksMapper = new Application_Model_LinksMapper();
            $links;
            
            if ($numberWords > 1) {
                $stmt = $db->query("SELECT * FROM search_index WHERE MATCH (link_text) AGAINST ('".$searchString."')");
            
                $rows = $stmt->fetchAll();
                if (empty($rows)) {
                    $this->view->searchEmpty = $config['empty_search_message'];
                }
                else {
                    foreach($rows as $row) {
                        $links[] = $linksMapper->findOne($row['link_id']);
                    }
                    $paginator = Zend_Paginator::factory($links);
                    $paginator->setItemCountPerPage($sortNumber);
                    $paginator->setCurrentPageNumber($page);
                    
                    $this->view->paginator=$paginator;
                    $this->view->pagenumber = $page;
                    $this->view->sortNumber = $sortNumber;
                    $this->view->searchCount = count($links);
                    
                }
            }
            else {
                $stmt = $db->query("SELECT * FROM search_index WHERE MATCH (link_text) AGAINST ('".$searchString."' WITH QUERY EXPANSION)");

                $rows = $stmt->fetchAll();
                
                if (empty($rows)) {
                    $this->view->searchEmpty = $config['empty_search_message'];
                }
                else {
                     foreach($rows as $row) {
                            $links[] = $linksMapper->findOne($row['link_id']);
                        }
                        $paginator = Zend_Paginator::factory($links);
                        $paginator->setItemCountPerPage($sortNumber);
                        $paginator->setCurrentPageNumber($page);

                        $this->view->paginator=$paginator;
                        $this->view->pagenumber = $page;
                        $this->view->sortNumber = $sortNumber;
                        $this->view->searchCount = count($links);
                }
            }
            
    }

    public function testAction()
    {
    	$msg = "";
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$utils = new Application_Model_Utils();
//		$select = $db->select()->from("links")->where("title IS NOT NULL");
//		$results = $db->fetchAll($select);
//		
//		foreach($results as $row) {
//			$i = $utils->urlsafe_title($row['title']);
//		$data = array(
//			'url_safe_title'      => $i
//        );
//        $update = $db->update("links", $data, "id = ".$row["id"]);
//        $msg .= $i.PHP_EOL;
//			
//		}

    	
    	
       
       
    	$this->view->msg = $msg;

    }
    

    public function richEditAction()
    {
    	$this->_helper->layout->disableLayout();
    	
                $utils = new Application_Model_Utils();
                $msg = <<<EOT
        tempus. Pellentesque sed enim. Sed a lorem dapibus imperdiet faucibus orci dui, dictum ut, sagittis eleifend. Nulla massa. Vivamus laoreet fermentum. Morbi eleifend. Sed lobortis velit. Pellentesque habitant morbi tristique dapibus, libero a auctor auctor neque scelerisque sed, tempus ultrices, gravida justo, hendrerit wisi. Morbi sodales arcu, dapibus nisl. Donec suscipit vitae, ligula. Fusce ullamcorper varius laoreet. Nam ut leo at eros. Donec nec nisl. Nam dictum quis, egestas sit amet neque. Suspendisse rutrum vel, pellentesque adipiscing laoreet, tortor turpis, et malesuada aliquet, arcu sed aliquet elit. Mauris lobortis tincidunt enim. Mauris nunc odio et lacus scelerisque fermentum erat. Sed
        
        Phasellus vestibulum et, commodo est pretium bibendum dui. In pharetra. Donec tortor ante eu tortor eget metus sed lorem pretium pellentesque. Proin at magna arcu, eget dolor sit amet leo lacus, faucibus orci luctus orci, in faucibus orci dictum accumsan. Quisque eu lectus. Morbi laoreet, lacus nulla vitae tellus felis augue turpis, accumsan nunc. Quisque in ligula nulla, at ligula. Curabitur ornare vel, dignissim id, libero. Cras ut sem. Aenean ut quam. Nullam suscipit in, commodo pede convallis tellus. Integer faucibus quis, luctus arcu elementum eleifend, metus et ultrices posuere quis, orci. Suspendisse potenti. Vivamus magna. Donec nunc. Donec non
        
        aptent taciti sociosqu ad litora torquent per inceptos hymenaeos. Nunc a elit laoreet viverra. Mauris luctus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque sem eget orci luctus tellus sodales dignissim turpis. Proin dui vel nulla. Maecenas pharetra sit amet augue. Morbi laoreet, purus consectetuer adipiscing wisi. Morbi urna elit, sit amet, consectetuer lobortis dapibus, felis blandit vestibulum ipsum. Curabitur ut vehicula libero a lectus. Sed et lacus vehicula enim nec eros. Suspendisse fringilla odio. Ut sodales dignissim eu, ornare risus. Fusce suscipit lectus. Vestibulum id ipsum. Fusce imperdiet sed, urna. Phasellus tellus felis non mi non magna. Mauris        
                
EOT;
                $this->view->msg = nl2br($msg);
    }


}