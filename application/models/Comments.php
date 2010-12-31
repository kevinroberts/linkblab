<?php

class Application_Model_Comments
{
	public $blabName, $content, $count, $loggedIn, $user;
	
	public function __construct($currentBlab = 'linkblab.com')
	{
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
	
	private function displayName($userID, $CSSclass = '') {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from('users');
		$select->where("id = ?", $userID);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		$username = $result[0]['username'];
		if (empty($CSSclass)) {
			return '<a href="/user/'.$username.'">'.$username.'</a>'.PHP_EOL;
		}
		else {
			return '<a class="'.$CSSclass.'" href="/user/'.$username.'">'.$username.'</a>'.PHP_EOL;
		}
		
	}
	
	/**
	 * function getAllComments returns all comments for a particular
	 * link ID or optionally all comments under a comment ID
	 * returns an associative array 
	 * 	@param link id integer|$linkID
	 *  @param comment id integer|$commentID
	 *  @return mixed|$comments
	 *  
	 */
	public function getAllComments($linkID, $orderby = null, $commentID = null) {
		
		$orderby = (is_null($orderby)) ? 'hot DESC' : $orderby;
		$content = '';
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from("comments", array('id', 'comment', 'up_votes', 'down_votes', 'votes', 'date_added', 'user_id', 'link_id', 'parent_id', 'controversy', 'hot'));
		$select->where("link_id = ?", $linkID);
		$select->where("parent_id IS NULL");
		$select->order($orderby);
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
		if (!is_array($comment))
			return false;
		$blab = $this->blabName;
		$id = $comment["id"]; 
		$com = strip_tags($comment["comment"]); 
		$up_votes = $comment["up_votes"];
		$down_votes = $comment["down_votes"];
		$votes = $comment["votes"];
		$user_id = $comment["user_id"];
		
		$userName = $this->displayName($user_id, 'author submitter');
		$utils = new Application_Model_Utils();
		$votes = ($votes == 1) ? $votes.' point' : $votes.' points'; 
		$numberOfChildren = $this->countChildCommments($comment["id"]);
		$numberOfChildren = ($numberOfChildren == 1) ? $numberOfChildren.' child' : $numberOfChildren.' children'; 
		
		$comm = $utils->docodaOutput($com,false,array('b', 'i', 'u', 'align', 'color', 'font', 'sub', 'sup', 'url', 'quote', 'list', 'email' ));
		$comm = str_replace(PHP_EOL, "", $comm);
		$comm = str_replace("<br /><br />", "<br />", $comm);
		
		// If this comment has more than 3 downvotes lets hide it
		$hideToggle = (($up_votes - $down_votes) < -3) ? 'style="display: none;"' : 'style="display: block;"';
		$showToggle = (($up_votes - $down_votes) < -3) ? 'style="display: block;"' : 'style="display: none;"';
		$timePast = $utils->TimeSince(strtotime($comment["date_added"])). ' ago';
		$timeAgo = (($up_votes - $down_votes) < -3) ? ' (comment score below threshold) <span style="display:none;">'.$timePast.'</span>' : $timePast;
		
		$this->content .= <<<EOT
<div id="comment-$id" class="comment">		
		<div $hideToggle class="midcol">
		<a onclick="commentVoteAction($(this), 1, $id)" title="vote this comment up" class="ui-state-default ui-corner-all" id="recent-link328-up"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>
		<a onclick="commentVoteAction($(this), 2, $id)" title="vote this comment down" class="ui-state-default ui-corner-all" id="recent-link328-down"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
		</div>
	<div class="entry">
			<div $showToggle class="collapsed">
			
			$userName
						<span class="userattrs"> [<a href="/r/testingmang/comments/efqk3/great_jquery_linkselect_plugin/" title="submitter" class="submitter">S</a>,<a href="/r/testingmang/about/moderators" title="moderator of /r/testingmang, speaking officially" class="moderator">M</a>]</span>
						<span class="score dislikes">$down_votes</span>
						<span class="score likes">$up_votes</span>
						<span class="score total">$votes</span>
						$timeAgo
			
			<a title="expand" onclick="return showComment($(this))" class="expand" href="#">[+] ($numberOfChildren)</a>
				
			</div>
		<div $hideToggle class="noncollapsed">
		<p class="tagline">
			$userName
			<span class="userattrs"> [<a href="/r/testingmang/comments/efqk3/great_jquery_linkselect_plugin/" title="submitter" class="submitter">S</a>,<a href="/r/testingmang/about/moderators" title="moderator of /r/testingmang, speaking officially" class="moderator">M</a>]</span>
			<span class="score dislikes">$down_votes</span>
			<span class="score likes">$up_votes</span>
			<span class="score total">$votes</span>
			$timeAgo ago
			<a title="collapse" onclick="return hideComment($(this))" class="expand" href="#">[-]</a>
		</p>
		<div class="md">
			<div>
			$comm
			</div>
			
		</div>
		<div style="display: none;" class="usertext-edit"><div><textarea name="text">$com</textarea></div></div>
		<ul class="flat-list buttons">
		<li class="first"><a rel="nofollow" class="bylink" href="/b/$blab/commentLink/$id">permalink</a></li>
		
		</ul>
	 </div>
	</div>
	
	</div>
    <div class="clearleft"></div>
EOT;

	
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

}

