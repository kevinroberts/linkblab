<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * LoggedInAs helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract {
	

	/**
	 * 
	 */
	public function loggedInAs() {
		$auth = Zend_Auth::getInstance();
		$utils = new Application_Model_Utils();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->username;
            $role = $auth->getIdentity()->role;
            /*$logoutUrl = $this->view->url(array('controller'=>'auth',
                'action'=>'logout'), null, true);*/
            $logoutUrl = "/auth/logout";
            $prefUrl = '/user/edit/username/'.$username;
            if ($role == "administrator")
            {
            return 'Welcome <a id="loginLink" name="logged_in" title="go to your account activity" href="/user/'.$username.'">' . $username .  '</a> | <a href="'.$prefUrl.'">preferences</a> | <a href="'.$logoutUrl.'">Logout</a> | <a href="/auth/admin">Site Admin</a>';
            }
            else {
            return 'Welcome <a id="loginLink" name="logged_in" title="go to your account activity" href="/user/'.$username.'">' . $username .  '</a> | <a title="go to your account preferences" href="'.$prefUrl.'">preferences</a>  | <a href="'.$logoutUrl.'">Logout</a> ';
            }
           } 

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if(($controller == 'auth' && ($action == 'login' || $action == 'signUp')) || $controller == 'index') {
            $loginUrl = "/auth/login";
        	return 'Welcome <a id="loginLink" name="logged_out" title="register for an account" href="/auth/signup">Guest</a> | <a href="'.$loginUrl.'">Login</a>  | <a href="/auth/signup">Register</a> ';
        }
        $loginUrl = "/auth/login?r=".$utils->urlsafe_b64encode($utils->curPageURL());
        return 'Welcome <a id="loginLink" name="logged_out" title="register for an account" href="/auth/signup">Guest</a> | <a href="'.$loginUrl.'">Login</a>  | <a href="/auth/signup">Register</a> ';
		
	}
	

}