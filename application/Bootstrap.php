<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	
	/*
	 *  Define custom URL routes for Linkblab
	 */   
	public function _initRoutes()
    {

        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

        $route = new Zend_Controller_Router_Route_Static (
            'login',
            array('controller' => 'auth', 
                  'action' => 'login')
        );

        $router->addRoute('login', $route);
        
        $route = new Zend_Controller_Router_Route (
       'b/:category',
        array('controller' => 'blabs',
          'action'     => 'display', 
          'category'   => 'pics' )
		);
		
		 $router->addRoute('b', $route);
		 
	   $route = new Zend_Controller_Router_Route (
       'b/:category/comments/:comments',
        array('controller' => 'blabs',
          'action'     => 'display', 
          'category'   => 'pics',
          'comments' => 8 )
		);
		
		 $router->addRoute('bComments', $route);
		 
	   $route = new Zend_Controller_Router_Route (
       'b/:category/:sort',
        array('controller' => 'blabs',
          'action'     => 'display', 
          'category'   => 'pics',
          'sort' => 'hot' )
		);
		
		 $router->addRoute('bsort', $route);
		 
	   $route = new Zend_Controller_Router_Route (
       'user/:username',
        array('controller' => 'user',
          'action'     => 'account', 
          'username'   => 'kevin' )
		);
		
		 $router->addRoute('user', $route);
		 
	   $route = new Zend_Controller_Router_Route (
       'submit/:to',
        array('controller' => 'blabs',
          'action'     => 'submit', 
          'to'   => 'linkblab.com' )
		);
		
		$router->addRoute('submit', $route);
		
		
		$route = new Zend_Controller_Router_Route (
       'index/:sort',
        array('controller' => 'index',
          'action'     => 'index', 
          'sort'   => 'hot' )
		);
		
		$router->addRoute('sort', $route);
		
	 $route = new Zend_Controller_Router_Route (
       'index/:sort/page/:page',
        array('controller' => 'index',
          'action'     => 'index', 
          'sort'   => 'hot',
          'page' => 1 )
		);
		
		$router->addRoute('sortPage', $route);
		
		$route = new Zend_Controller_Router_Route_Static (
            'index/notfound',
            array('controller' => 'index', 
                  'action' => 'notfound')
        );

        $router->addRoute('notfound', $route);
		
         /*
         * Rich Editor ROUTE 
         */
        $route = new Zend_Controller_Router_Route_Static (
            'index/rich-editor',
            array('controller' => 'index', 
                  'action' => 'rich-edit')
        );

        $router->addRoute('richEdit', $route);  
        
        /*
         * TESTING ROUTE _REMOVE_ on LIVE SITE
         */
        $route = new Zend_Controller_Router_Route_Static (
            'index/test',
            array('controller' => 'index', 
                  'action' => 'test')
        );

        $router->addRoute('test', $route);  
		 
    }
	
	
	protected function _initResources()
    {
    	//require_once ('/models/BigOrNot_CookieManager.php');
    	require_once('../library/decoda/decoda.php');
    	require_once('../library/htmlpurifier/HTMLPurifier.standalone.php');
    	
    	Zend_Registry::set("rememberMeSeconds",  $this->getOption('remember_me_seconds')); // set default time a session should be persistant
    	// Set allowed markup constants
    	define("DECODAPOST",     "b,i,u,align,color,sub,sup,code,url,quote,list,olist,email,li,decode,spoiler");
    	define("DECODACOMMENT",     "b,i,u,color,sub,sup,code,url,quote,list,olist,email,li,decode,spoiler");

        $IE = '.content { margin-right: -1px; } /* this 1px negative margin can be placed on any of the columns in this layout with the same corrective effect. */'.PHP_EOL;
    	$IE.= 'ul.nav a { zoom: 1; }  /* the zoom property gives IE the hasLayout trigger it needs to correct extra whiltespace between the links */';
    	$IE2 = <<<EOT
.thumbnail {
width:inherit;
}
EOT;
    	$this->bootstrap('view');
    	$view = $this->getResource('view');
    	$view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');
        $view->headMeta()->appendHttpEquiv('Cache-Control', 'public, no-transform, must-revalidate');
		$view->headMeta()->appendHttpEquiv('expires', date('r', time()+(86400*30)));
        $view->doctype('XHTML1_STRICT');
       // $utils = new Application_Model_Utils();
  	//	$view->token = $utils->form_token(true);	
        
        $view->headTitle('LinkBlab');
 		$view->headStyle()->appendStyle($IE, array('conditional' => 'lt IE 7'));
 		$view->headStyle()->appendStyle($IE2, array('conditional' => 'lte IE 9'));
 		$view->headScript()->appendFile("/js/site.js");
        //$view->headScript()->appendScript("var age = 0;", $type = 'text/javascript', $attrs = array());
 		
 		// Get the requested URL to determine if controller specific scripts are needed:
 		$reqURL = $_SERVER["REQUEST_URI"];
 		// Extract Contoller
 		$reqURL = ltrim($reqURL, "/");
 		
 		// If trailing slashes still exist --> extract controller name
 		if (strpos($reqURL, "/")) {
 		 	$reqURL = explode("/", $reqURL);
 			$controller = $reqURL[0];
 			$action = $reqURL[1];
 			// If we are at the auth controller or at the blabs controller (action create) add the following
 			if ($controller == 'auth' || ($controller == 'blabs' && ($action == "create" || $action == 'submit'))) {
 	    	$script = <<<EOT
$(document).ready(function(){
	$(document).uiforms();
	$('#searchForm').removeClass('uiforms-form ui-widget ui-corner-all');
	$('#searchForm input').removeClass('uiforms-input ui-state-default ui-corner-all uiforms-text uiforms-submit');
	$('#searchForm input').focus(function() {
		$('#searchForm input').removeClass('ui-state-focus');
		});
	$('#searchForm input').hover(function() {
		$('#searchForm input').removeClass('ui-state-hover');
		});
	
});
EOT;
 				$view->headScript()->appendScript($script, $type = 'text/javascript', $attrs = array());
 				}

 		}

    }
    

}

