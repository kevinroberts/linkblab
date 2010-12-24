<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * linkBuilder helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_linkBuilder {
	

	/**
	 *  
	 */
	public function linkBuilder($id, $downvotes, $votes, $upvotes) {
		$output = '';
		$auth = Zend_Auth::getInstance();
		$userID = $auth->getIdentity()->id;
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select();
		$select->from("link_history");
		$select->where("user_id = ?", $userID);
		$select->where("link_id = ?", $id);
		$select->limit(1);
		
		$result = $db->fetchRow($select);
		
		if(!empty($result)) {
		// This link was already voted on by this user, output the ui	
		
			if ($result['vote_up'] == 1) {
					$output = <<<EOT
<div style="width: 5ex;" class="midcol">
			<a id="link$id-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="voteAction($(this), 1, $id)"><span class="ui-icon ui-icon-circle-arrow-n voted"></span></a>
			<div class="score downVotes">$downvotes</div>
			<div class="score voted">$votes</div>
			<div class="score upVotes">$upvotes</div>
			<a id="link$id-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="voteAction($(this), 2, $id)"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
		</div>
EOT;
			}
			else {
				// Downvoted
					$output = <<<EOT
<div style="width: 5ex;" class="midcol">
			<a id="link$id-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="voteAction($(this), 1, $id)"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>
			<div class="score downVotes">$downvotes</div>
			<div class="score downvoted">$votes</div>
			<div class="score upVotes">$upvotes</div>
			<a id="link$id-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="voteAction($(this), 2, $id)"><span class="ui-icon ui-icon-circle-arrow-s downvoted"></span></a>
		</div>
EOT;
							
			}

		}
		else 
		{
						$output = <<<EOT
<div style="width: 5ex;" class="midcol unVoted">
			<a id="link$id-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="voteAction($(this), 1, $id)"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>
			<div class="score downVotes">$downvotes</div>
			<div class="score unVoted">$votes</div>
			<div class="score upVotes">$upvotes</div>
			<a id="link$id-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="voteAction($(this), 2, $id)"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
		</div>
EOT;
		
		}
		
		
		return $output;
	}
	
}
