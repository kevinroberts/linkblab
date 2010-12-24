<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * BlabIsFrontPage helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BlabIsFrontPage {
	

	public function blabIsFrontPage($blabID) {
				$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
        	$db = Zend_Db_Table::getDefaultAdapter();
        	$userID = $auth->getIdentity()->id;
        	
        	$select = $db->select();
        	$select->from("subscriptions");
        	$select->where("user_id = ?", $userID);
        	$select->where("blab_id = ?", $blabID);

        	$results = $db->fetchCol($select);
			if ($blabID == 23 || $blabID == 24) { // do not allow user to add "Random" or "All" blabs
				return '';
			}
        	
        	if (!empty($results)) {
        		return '<a title="remove this blab from your front page bar" class="removebtn" href="/blabs/frontpage/removeBlab/'.$blabID.'/user/'.$userID.'"><span class="ui-icon ui-icon-minus"></span> remove from frontpage</a>';	
        	}
        	else {
        		return '<a title="add this blab to your front page bar" class="addbtn" href="/blabs/frontpage/addBlab/'.$blabID.'/user/'.$userID.'"><span class="ui-icon ui-icon-plus"></span> add to frontpage</a>';
        	}
        	
    		    	
        }
        else
        return '';
		
	}
	

}
