<?php
/**
 *
 * @author sudoKevin
 * @version 
 */


/**
 * timeAgo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_timeAgo extends Zend_View_Helper_Abstract{
	
     /**
     * Time Ago
     *
     * @param  $input - Value as timestamp
     * @throws Zend_Measure_Exception
     */
	
	public function timeAgo($input) {
		$utils = new Application_Model_Utils();
		return $utils->TimeSince($input);
		
	}
	
}
