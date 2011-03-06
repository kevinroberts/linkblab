<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * currentURL helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_CurrentURL extends Zend_View_Helper_Abstract {
	
	private function curPageURL() 
{
	 $pageURL = 'http';
	if (isset($_SERVER["HTTPS"]))
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
	/**
	 * 
	 */
	public function currentURL() {
		return $this->curPageURL();
	}
	
	
}
