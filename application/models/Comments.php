<?php

class Application_Model_Comments
{
	public $blabName, $content, $count, $loggedIn, $user;
	public static $utils, $submitterUID;
	
	public function __construct($currentBlab = 'linkblab.com')
	{
		self::$utils = new Application_Model_Utils ();
		$this->blabName = $currentBlab;
		$this->content = '';
		/*if (is_array($options)) {
			$this->setOptions($options);
		}*/
		$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
        	$this->loggedIn = true;
        	$this->user = $auth->getIdentity();
        }
        else
        {
        	$this->loggedIn = false;
        }
	}
	
	private function displayName($userID) {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from('users');
		$select->where("id = ?", $userID);
		$select->limit(1);
		$result = $db->fetchRow($select);
		$username = $result['username'];
		$CSSclass = 'author';
		$userAttrib = '';
		
		if (self::$submitterUID == $userID) {
			$CSSclass .= " submitter";
			$userAttrib .= "<a href=\"/user/$username\" title=\"submitter\" class=\"submitter\">S</a>";
		}
		
		if (!empty($userAttrib)) {
			$userAttribs = "<span class=\"userattrs\"> [$userAttrib] </span>";
		}

		$userLink = '<a class="'.$CSSclass.'" href="/user/'.$username.'">'.$username.'</a>'.PHP_EOL;
		return array( 
		"link" => $userLink,
		"attrib" => (isset($userAttribs)) ? $userAttribs : ''
		);
		
	}
	
	/**
	 * @param $com unformatted comment string
	 * @return $comment in html format
	 */
	public function formatComment($com) {
		$comm = self::$utils->docodaOutput($com, preg_split("/[\s,]+/", DECODACOMMENT));
		$comm = str_replace("<br /><br />", "<br />", $comm);
		return $comm;
	}
	
	public function getSubmitterUserID($linkID) {
		$db = Zend_Db_Table::getDefaultAdapter();
		// Get submitter user's id:
		$select = $db->select();
		$select->from('links');
		$select->where("id = ?", $linkID);
		$select->limit(1);
		$result = $db->fetchRow($select);
		self::$submitterUID = $result['user_id'];
	}
	
	/**
	 * function getAllComments returns all comments for a particular
	 * link ID or optionally all comments under a comment ID
	 * returns all comments in HTML
	 * 	@param link id integer|$linkID
	 *  @param comment id integer|$commentID
	 *  @return string|$comments
	 *  
	 */
	public function getAllComments($linkID, $orderby = null, $commentID = null) {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		// Get submitter user's id:
		$this->getSubmitterUserID($linkID);
		
		$select = $db->select();
		$select->from('links');
		$select->where("id = ?", $linkID);
		$select->limit(1);
		$result = $db->fetchRow($select);
		self::$submitterUID = $result['user_id'];
		
		$orderby = (is_null($orderby)) ? 'hot DESC' : $orderby;
		$content = '';
		
		$select = $db->select();
		$select->from("comments", array('id', 'comment', 'up_votes', 'down_votes', 'votes', 'date_added', 'user_id', 'link_id', 'parent_id', 'controversy', 'hot'));
		if (!is_null($commentID)) {
		$select->where("id = ?", $commentID);
		}
		else {
		$select->where("link_id = ?", $linkID);
		$select->where("parent_id IS NULL");
		$select->order($orderby);
		$select->order("date_added DESC"); // fall back to ordering by date added
		}
		
		$results = $db->fetchAll($select);
		if (empty($results)) { return false; }
		
		foreach ($results as $comment) {
			$comment = array(
			"id" => $comment["id"],
			"comment" => $comment["comment"],
			"up_votes" => $comment["up_votes"],
			"down_votes" => $comment["down_votes"],
			"votes" => $comment["votes"],
			"user_id" => $comment["user_id"],
			"date_added" =>$comment["date_added"]			
			);
		
		 $this->getChildComments($comment["id"], $comment, $orderby);
		 
		}
		
		return $this->content;
	}
	
	public function displaySingleComment($comment) {
		if ($this->loggedIn) {
			$this->loggedInComment($comment);			
		}
		else
		{
			$this->loggedOutComment($comment);
		}
	
	}
	
	
	public function getChildComments($commentID, $initialComment = null, $orderby = null) {
		$this->content .= (is_null($initialComment)) ? '' : $this->displaySingleComment($initialComment);
		
		$orderby = (is_null($orderby)) ? 'hot DESC' : $orderby;
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from("comments", array('id', 'comment', 'up_votes', 'down_votes', 'votes', 'date_added', 'user_id', 'link_id', 'parent_id', 'controversy', 'hot'));
		$select->where("parent_id = ?", $commentID);
		$select->order($orderby);
		$results = $db->fetchAll($select);
		
		if (!empty($results[0]["id"])) {
			$this->content .= '<div class="child">'.PHP_EOL;
			
			foreach ($results as $comment) {
				$childComment = array(
				"id" => $comment["id"],
				"comment" => $comment["comment"],
				"up_votes" => $comment["up_votes"],
				"down_votes" => $comment["down_votes"],
				"votes" => $comment["votes"],
				"user_id" => $comment["user_id"],
				"date_added" =>$comment["date_added"]			
				);
				$this->displaySingleComment($childComment);
				// Recursively grab all children of this comment 
				$this->getChildComments($comment["id"], null, $orderby);
			}
			
			$this->content .= '</div> <!-- end child level -->';
		}
		
	}
	
	public function countChildCommments($commentID) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from("comments", array('id', 'parent_id'));
		$select->where("parent_id = ?", $commentID);
		$results = $db->fetchAll($select);
		$this->count = count($results);
		if ($this->count > 1) {
		foreach ($results as $comment) {
				$this->countCommentRecursion($comment["id"]);
			}
		}
		return $this->count;
	
	}
	
	private function countCommentRecursion($commentID) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from("comments", array('id', 'parent_id'));
		$select->where("parent_id = ?", $commentID);
		$results2 = $db->fetchAll($select);
		$n = count($results2);
		$this->count += $n;
		if ($n > 1) {
		foreach ($results2 as $comment) {
				$this->count += $this->countCommentRecursion($comment["id"]);
			}
		}
						
	}
	
	private function loggedInComment ($comment) {
		if (!is_array($comment))
			return false;
		$blab = $this->blabName;
		$id = $comment["id"]; 
		$com = $comment["comment"]; 
		$up_votes = $comment["up_votes"];
		$down_votes = $comment["down_votes"];
		$votes = $comment["votes"];
		$user_id = $comment["user_id"];
		// is this the user's own comment?
		$commentEdit = $this->build_own_comment_controls($com, $user_id, $id);
		$userName = $this->displayName($user_id);
		self::$utils = new Application_Model_Utils();
		$votes = ($votes == 1) ? $votes.' point' : $votes.' points'; 
		$numberOfChildren = $this->countChildCommments($comment["id"]);
		$numberOfChildren = ($numberOfChildren == 1) ? $numberOfChildren.' child' : $numberOfChildren.' children'; 
		
		$comm = $this->formatComment($com);
		
		// If this comment has more than 3 downvotes lets hide it
		$hideToggle = (($up_votes - $down_votes) < -3) ? 'style="display: none;"' : 'style="display: block;"';
		$showToggle = (($up_votes - $down_votes) < -3) ? 'style="display: block;"' : 'style="display: none;"';
		$timePast = self::$utils->TimeSince(strtotime($comment["date_added"]));
		$timeAgo = (($up_votes - $down_votes) < -3) ? ' (comment score below threshold) <span style="display:none;">'.$timePast.'</span>' : $timePast;
		$isEdit = ($this->is_modified($id) == true) ? ' <label title="this comment has been edited">*</label>' : ''; 
		
		$this->content .= "
		<div id=\"comment-$id\" class=\"comment\">		
				<div $hideToggle class=\"midcol\">
				<a onclick=\"commentVoteAction($(this), 1, $id)\" title=\"vote this comment up\" class=\"ui-state-default ui-corner-all\" id=\"com-$id-up\"><span class=\"ui-icon ui-icon-circle-arrow-n\"></span></a>
				<a onclick=\"commentVoteAction($(this), 2, $id)\" title=\"vote this comment down\" class=\"ui-state-default ui-corner-all\" id=\"com-$id-down\"><span class=\"ui-icon ui-icon-circle-arrow-s\"></span></a>
				</div>
			<div class=\"entry\">
					<div $showToggle class=\"collapsed\">
					
					{$userName['link']}
					{$userName['attrib']}
								<span class=\"score dislikes\">$down_votes</span>
								<span class=\"score likes\">$up_votes</span>
								<span class=\"score total\">$votes</span>
								$timeAgo $isEdit
					
					<a title=\"expand\" onclick=\"return showComment($(this))\" class=\"expand\" href=\"#\">[+] ($numberOfChildren)</a>
						
					</div>
				<div $hideToggle class=\"noncollapsed\">
				<p class=\"tagline\">
					{$userName['link']}
					{$userName['attrib']}
					<span class=\"score dislikes\">$down_votes</span>
					<span class=\"score likes\">$up_votes</span>
					<span class=\"score total\">$votes</span>
					$timeAgo $isEdit
					<a title=\"collapse\" onclick=\"return hideComment($(this))\" class=\"expand\" href=\"#\">[-]</a>
				</p>
				<div class=\"md\">
					<div>
					$comm
					</div>
					
				</div>
					{$commentEdit['forms']}
				<ul class=\"flat-list buttons\">
				<li class=\"first\"><a rel=\"nofollow\" class=\"bylink\" href=\"/b/$blab/comment/$id\">permalink</a></li>
				{$commentEdit['buttons']}
				</ul>
			 </div>
			</div>
			
			</div>
		    <div class=\"clearleft\"></div>";
		
	}
	
	private function build_own_comment_controls($comment, $userID, $commentID) {

		$content = array("forms" => "", "buttons" => "");
		
		if ($this->user->id == $userID) {
		$content = array( "forms" =>  "
		<form style=\"display:none;\" action=\"\" class=\"closed cloneable\" onsubmit=\"return post_comment($(this), 'edit')\" method=\"post\" name=\"newCommentForm\" id=\"formEdit-$commentID\">
		<div class=\"usertext usertext-comment-edit\">
			
		<div><textarea name=\"text\">$comment</textarea></div>
			<div class=\"form_errors\" style=\"display: none;\"></div>
			<div class=\"bottom-area\">
			<div class=\"usertext-buttons\">
						    <input type=\"hidden\" name=\"commentID\" value=\"$commentID\">
							<button type=\"submit\" class=\"save\">save</button>
							<button class=\"cancel\" onclick=\"return toggle_edit($(this), $commentID)\" type=\"button\">cancel</button>
							<span style=\"display: none;\" class=\"status\">submitting...</span>
			</div>
			</div>
		</div>
		</form>
		
		",
		"buttons" => "<li><a class=\"edit-usertext\" onclick=\"return toggle_edit($(this), $commentID)\" href=\"#\">edit</a></li>
					  <li><a class=\"delete-usertext\" onclick=\"return toggle_delete($(this))\" href=\"javascript:void(0)\">delete</a></li>");
		}
		
		return $content;
	}
	
	private function loggedOutComment ($comment) {
		if (!is_array($comment))
			return false;
		
		$blab = $this->blabName;
		$id = $comment["id"]; 
		$com = $comment["comment"];
		$up_votes = $comment["up_votes"];
		$down_votes = $comment["down_votes"];
		$votes = $comment["votes"];
		$user_id = $comment["user_id"];
		
		$userName = $this->displayName($user_id);
		$votes = ($votes == 1) ? $votes.' point' : $votes.' points'; 
		$numberOfChildren = $this->countChildCommments($comment["id"]);
		$numberOfChildren = ($numberOfChildren == 1) ? $numberOfChildren.' child' : $numberOfChildren.' children'; 
		
		$comm = $this->formatComment($com);
		$isEdit = ($this->is_modified($id) == true) ? ' <label title="this comment has been edited">*</label>' : ''; 
		// If this comment has more than 3 downvotes lets hide it
		$hideToggle = (($up_votes - $down_votes) < -3) ? 'style="display: none;"' : 'style="display: block;"';
		$showToggle = (($up_votes - $down_votes) < -3) ? 'style="display: block;"' : 'style="display: none;"';
		$timePast = self::$utils->TimeSince(strtotime($comment["date_added"]));
		$timeAgo = (($up_votes - $down_votes) < -3) ? ' (comment score below threshold) <span style="display:none;">'.$timePast.'</span>' : $timePast;
		
		$this->content .= "
		<div id=\"comment-$id\" class=\"comment\">		
				<div $hideToggle class=\"midcol\">
				<a onclick=\"commentVoteAction($(this), 1, $id)\" title=\"vote this comment up\" class=\"ui-state-default ui-corner-all\" id=\"com-$id-up\"><span class=\"ui-icon ui-icon-circle-arrow-n\"></span></a>
				<a onclick=\"commentVoteAction($(this), 2, $id)\" title=\"vote this comment down\" class=\"ui-state-default ui-corner-all\" id=\"com-$id-up\"><span class=\"ui-icon ui-icon-circle-arrow-s\"></span></a>
				</div>
			<div class=\"entry\">
					<div $showToggle class=\"collapsed\">
					
					{$userName['link']}
					{$userName['attrib']}
								<span class=\"score dislikes\">$down_votes</span>
								<span class=\"score likes\">$up_votes</span>
								<span class=\"score total\">$votes</span>
								$timeAgo $isEdit
					
					<a title=\"expand\" onclick=\"return showComment($(this))\" class=\"expand\" href=\"#\">[+] ($numberOfChildren)</a>
						
					</div>
				<div $hideToggle class=\"noncollapsed\">
				<p class=\"tagline\">
					{$userName['link']}
					{$userName['attrib']}
					<span class=\"score dislikes\">$down_votes</span>
					<span class=\"score likes\">$up_votes</span>
					<span class=\"score total\">$votes</span>
					$timeAgo $isEdit
					<a title=\"collapse\" onclick=\"return hideComment($(this))\" class=\"expand\" href=\"#\">[-]</a>
				</p>
				<div class=\"md\">
					<div>
					$comm
					</div>
					
				</div>
				<ul class=\"flat-list buttons\">
				<li class=\"first\"><a rel=\"nofollow\" class=\"bylink\" href=\"/b/$blab/comment/$id\">permalink</a></li>
				
				</ul>
			 </div>
			</div>
			
			</div>
		    <div class=\"clearleft\"></div>";
    	
	}
	
	
	public function submitComment($type, $data) {
		if (is_null($data)) {
			return false;
		}
	 switch ($type) {
           case 'parent':
           		$comment = $data['comment'];
                $linkID = $data['link_id'];
                $userID = $data['user_id'];
				$result = $this->db_create_comment($comment, $linkID, $userID);
                $results = array(
                	'message' => 'Success - comment saved! ',
                	'success' =>  true,
                	'id' => $result         	
               	);
                                
				break;
           case 'edit' :
           		$comment = $data['comment'];
                $commentID = $data['commentID'];
                $userID = $data['user_id'];
				$result = $this->db_update_comment($comment, $commentID, $userID);
                $results = array(
                	'message' => 'Success - comment updated! ',
                	'success' =>  true,
                	'commentID' => $commentID         	
               	);
               	break;
           	
            default: break;
     }
		
		return $results;
	}
	
	public function is_modified ($commentID) {
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select();
		$select->from('comments', array("id", "date_modified"));
		$select->where("id = ?", $commentID);
		$select->limit(1);
		$result = $db->fetchRow($select);
		
		if (is_null($result['date_modified']) || empty($result['date_modified']) ) 
			return false;
		else
			return true;
	}
	
	private function db_update_comment ($comment, $commentID, $userID) {
			$db = Zend_Db_Table::getDefaultAdapter();
			$updateData = array(
				'comment'             => $comment,
				'date_modified'      => new Zend_Db_Expr('NOW()')
                );
            return $db->update('comments', $updateData, 'id = '.$commentID); 
	}
	
	private function db_create_comment($comment, $linkID, $userID, $parentID = null) {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$up_votes = 1;
		$down_votes = 0;
		$votes = 1;
		$data = array(
	        'comment'      => $comment,
	        'up_votes' =>  $up_votes,
			'down_votes' => $down_votes,
			'votes' => $votes,
			'date_added' => new Zend_Db_Expr('NOW()'),
	        'user_id' => $userID,
			'link_id' => $linkID,
			'parent_id' => new Zend_Db_Expr('NULL'),
			'controversy' => self::$utils->_controversy(1, 0),
			'hot' => self::$utils->confidence(1, 0)
	        );
		$db->insert('comments',$data);
		$returnID = $db->lastInsertId("comments", "id");
		// enter into comment history
		$data = array(
	        'vote_up'      => 1,
	        'comment_id' =>  $returnID,
	        'user_id' => $userID,
			'date_submitted' => new Zend_Db_Expr('NOW()')
	        );
	     $db->insert('comment_history',$data);
	     
	     return $returnID;
	}

}