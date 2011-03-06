<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * FormToken helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_FormToken extends Zend_View_Helper_Abstract {
	

	/**
	 *  
	 */
	public function formToken() {
		// TODO Auto-generated Zend_View_Helper_formToken::formToken() helper
		$utils = new Application_Model_Utils(); 
		return $utils->form_token(true);
	}
	
}
