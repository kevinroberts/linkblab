<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * displayName helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_DisplayName extends Zend_View_Helper_Abstract {
	

	/**
	 * 
	 */
	public function displayName($userID, $CSSclass = '') {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from('users');
		$select->where("id = ?", $userID);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		$username = $result[0]['username'];
		if (empty($CSSclass)) {
			return '<a href="/user/'.$username.'">'.$username.'</a>';
		}
		else {
			return '<a class="'.$CSSclass.'" href="/user/'.$username.'">'.$username.'</a>';
		}
		
		
	}
	

}
