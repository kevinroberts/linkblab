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
class Zend_View_Helper_displayBlab {
	

	/**
	 * 
	 */
		public function displayBlab($blabID, $type = 0) {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from('blabs', array("id", "title", "head_title"));
		$select->where("id = ?", $blabID);	
		$select->limit(1);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		$title = $result[0]['title'];
		if ($type == 0) {
		return '<a href="/b/'.$title.'">'.$result[0]['head_title'].'</a> <span style="font-size:small; color: gray">(/b/'.$title.')</span>';	
		}
		else
		{
		return array($title, '<a class="blab" href="/b/'.$title.'">'.$title.'</a>');
		}
		
	}
	

}

?>