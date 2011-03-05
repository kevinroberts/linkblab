<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * getLinkIDFromCommentID helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetLinkIDFromCommentID extends Zend_View_Helper_Abstract {
	/**
	 *  Returns the link id associated with a given comment id 
	 */
	public function getLinkIDFromCommentID($commentID) {
      	$db = Zend_Db_Table::getDefaultAdapter();
      	
      	$select = $db->select();
		$select->from("comments", array('link_id'));
		$select->where("id = ?", $commentID);
		$select->limit(1);
		
		$result = $db->fetchRow($select);
		if(!empty($result)) {
		return $result['link_id'];	
		}
		else {
			return false;
		}
	}
	

}
