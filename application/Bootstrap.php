<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
    
    /**
     * @var Zend_Log
     */
    protected $_logger;
        
    /**
     * @var Zend_Controller_Front
     */
    public $frontController;
        
	
	    /**
             * Setup the logging
             */
            protected function _initLogging()
            {
                $this->bootstrap('frontController');

                $logger = new Zend_Log();
                // Possiblity to have different logger per environment
                $writer = 'production' == $this->getEnvironment() ?
        			new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../docs/logs/app.log') :
        			new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../docs/logs/app.log');
                $logger->addWriter($writer);

                if ('production' == $this->getEnvironment()) {
                        $filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
                        $logger->addFilter($filter);
                }

                $this->_logger = $logger;
                Zend_Registry::set('log', $logger);
            }
            
            /**
               * Configure the resource autoloader
               */
              protected function _initConfigureResourceAutoloader()
              {
                  $this->_logger->info('Bootstrap ' . __METHOD__);

                
              }
    
              /**
                  * Add the config to the registry
                  */
                 protected function _initConfig()
                 {
                     $this->_logger->info('Bootstrap ' . __METHOD__);
                     Zend_Registry::set('config', $this->getOptions());
                    
                 }
	
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
          'comments' => 5 )
		);
		
		$router->addRoute('bComments', $route);
	/*
	 * Add custom route for comments with a title
	 */
	   $route = new Zend_Controller_Router_Route (
       'b/:category/comments/:comments/:title/:sort',
        array('controller' => 'blabs',
          'action'     => 'display', 
          'category'   => 'pics',
          'comments' => 8,
        	'title' => 'london-1949-pic',
        'sort' => 'hot' )
		);
		
		$router->addRoute('bCommentsTitle', $route);
		
	   
	    $route = new Zend_Controller_Router_Route (
       'b/:category/comment/:comment',
        array('controller' => 'blabs',
          'action'     => 'display', 
          'category'   => 'pics',
          'comment' => 8 )
		);
		
		 $router->addRoute('bCommentsPermalink', $route);
		 
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
         * Search route
         */
        $route = new Zend_Controller_Router_Route_Static (
            'search',
            array('controller' => 'index', 
                  'action' => 'search')
        );

        $router->addRoute('search', $route);
        
         /*
         * Administrator Area ROUTE 
         */
        $route = new Zend_Controller_Router_Route_Static (
            'auth/admin',
            array('controller' => 'auth', 
                  'action' => 'admin')
        );

        $router->addRoute('admin', $route);
        
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
    	date_default_timezone_set('America/Chicago');
    	require_once('decoda/decoda.php');
    	require_once('htmlpurifier/HTMLPurifier.standalone.php');
    	require_once('Browser.php');
    	
    	Zend_Registry::set("rememberMeSeconds",  $this->getOption('remember_me_seconds')); // set default time a session should be persistant
    	// Set allowed markup constants
    	define("DECODAPOST",     "b,i,u,align,color,sub,sup,code,url,quote,list,olist,email,li,decode,spoiler");
    	define("DECODACOMMENT",     "b,i,u,color,sub,sup,code,url,quote,list,olist,email,li,decode,spoiler");
		define("RECAPTCHAPRIVATE",  $this->getOption("recaptcha_private_key"));
		define("RECAPTCHAPUBLIC", $this->getOption("recaptcha_public_key"));
        $IE = '.content { margin-right: -1px; } /* this 1px negative margin can be placed on any of the columns in this layout with the same corrective effect. */'.PHP_EOL;
    	$IE.= 'ul.nav a { zoom: 1; }  /* the zoom property gives IE the hasLayout trigger it needs to correct extra whiltespace between the links */';
    	$IE2 = ".thumbnail {
					width:inherit;
					}
				#loginMenu {
				clear: none;
				margin-top: 45px;
				margin-right: -65px;
				}";
    	$this->bootstrap('view');
    	$view = $this->getResource('view');
    	$view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');
        $view->headMeta()->appendHttpEquiv('Cache-Control', 'public, no-transform, must-revalidate');
		$view->headMeta()->appendHttpEquiv('expires', date('r', time()+(86400*30)));
        $view->doctype('XHTML1_STRICT');
        
        $view->headTitle('LinkBlab');
        $browser = new Browser();
        if ($browser->getBrowser() == Browser::BROWSER_IE) {
        $view->headStyle()->appendStyle($IE, array('conditional' => 'lt IE 7'));
 		$view->headStyle()->appendStyle($IE2, array('conditional' => 'lte IE 9'));
        $view->headScript()->appendFile("/js/jquery-1.4.4.min.js");
        $view->headScript()->appendFile("/js/jquery-ui-latest.min.js");
        }
        else {
        // This is not IE
        	$view->headScript()->appendFile("/js/jquery-1.4.4.min.js");
        	$view->headScript()->appendFile("/js/jquery-ui-latest.min.js");
        }
        //$view->headScript()->appendFile("/js/json2.js"); // for encoding json client-side
 		$view->headScript()->appendFile("/js/jquery.cookie.js");
 		$view->headScript()->appendFile("/js/site.js");
        //$view->headScript()->appendScript("var age = 0;", $type = 'text/javascript', $attrs = array());
 		
 		// Get the requested URL to determine if controller specific scripts are needed:
 		$reqURL = parse_url($_SERVER["REQUEST_URI"]);
 		// Extract Contoller
 		$reqURL = ltrim($reqURL['path'], "/");
 		$reqURL = ($reqURL == 'login') ? $reqURL.'/' : $reqURL;
 		// If trailing slashes still exist --> extract controller name
 		if (strpos($reqURL, "/")) {
 		 	$reqURL = explode("/", $reqURL);
 			$controller = $reqURL[0];
 			$action = $reqURL[1];
 			// If we are at the auth controller or at the blabs controller (action create) add the following
 			if ($controller == 'auth' || $controller == 'login' || ($controller == 'blabs' && ($action == "create" || $action == 'submit'))) {
 	    	$script = <<<EOT
$(document).ready(function(){
	$(document).uiforms();
	$('#searchForm').removeClass('uiforms-form ui-widget ui-corner-all');
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