<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * displayHowManyComments helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_DisplayHowManyComments extends Zend_View_Helper_Abstract {
	

	/**
	 *  
	 */
	public function displayHowManyComments($linkID) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from('comments', array("id", "link_id"));
		$select->where("link_id = ?", $linkID);	
		$result = $db->fetchAll($select);
		 
		return count($result);
	}
	

}
