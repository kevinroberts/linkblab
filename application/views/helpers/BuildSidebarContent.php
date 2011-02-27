<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * BuildSidebarContent helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BuildSidebarContent {
	// standard variables
	public static $loggedIn = false, $username, $userID, $blabInfo, $numberComments, $content;
	// object variables
	public static $utils, $linkBlabs, $howMany, $displayName;
	
	public function __construct() {
			$auth = Zend_Auth::getInstance ();
		if ($auth->hasIdentity ()) {
			self::$loggedIn = true;
			self::$username = $auth->getIdentity()->username;
			self::$userID = $auth->getIdentity()->id;
		}
		// Include other comment and link related view helpers
		include_once ("displayBlab.php");
		include_once ("displayHowManyComments.php");
		include_once ("linkBuilder.php");
		self::$content = '';
		self::$linkBlabs = new Zend_View_Helper_displayBlab ( );
		self::$howMany = new Zend_View_Helper_displayHowManyComments ( );
		self::$utils = new Application_Model_Utils();
	}
	

	private function buildRecentLinks() {
		// Handle Recently Viewed link output:
		$recentLinks = (isset($_COOKIE['rec_links'])) ? urldecode($_COOKIE['rec_links']) : '';
		if (!empty($recentLinks)) {
			$linkBuilder = new Zend_View_Helper_linkBuilder();
		
			if (strpos($recentLinks, ";") !== false)
				$recentLinks = explode(";", $recentLinks);
			else
				$recentLinks = array($recentLinks);
		
		$count = count($recentLinks);
		// Keep list under 5 links...
		while ($count > 5) {
			// remove oldest
			unset($recentLinks[$count-1]);
			$count--;
		}
		$links = new Application_Model_LinksMapper();
		$link = new Application_Model_Link();
		$recentLinksContent = '';
		$db = Zend_Db_Table::getDefaultAdapter();
		
		foreach($recentLinks as $_id) {
			$links->find($_id, $link);
			$linkBlab = self::$linkBlabs->displayBlab($link->blabID, 1); // returns an array with [0] = blab title, [1] = anchor link to blab
			$linkURL = ($link->isSelf == 1) ? '/b/'.$linkBlab[0].'/comments/'.$link->id.'/'.$link->urlTitle : $link->linkurl;
			$recentLinksContent .= "<tr>
			<td class=\"viewedVote\">";
			
			if (self::$loggedIn) {
				$select = $db->select();
				$select->from("link_history");
				$select->where("user_id = ?", self::$userID);
				$select->where("link_id = ?", $link->id);
				$select->limit(1);
		
				$result = $db->fetchRow($select);
		
				if(!empty($result)) {
					// This link was already voted on by this user, output the ui	
		
					if ($result['vote_up'] == 1) {
						$recentLinksContent .= "
						<a id=\"recent-link".$link->id."-up\" class=\"ui-state-default ui-corner-all\" title=\"vote this link up\" onclick=\"recentVoteAction($(this), 1, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-n voted\"></span></a>
						<a id=\"recent-link".$link->id."-down\" class=\"ui-state-default ui-corner-all\" title=\"vote this link down\" onclick=\"recentVoteAction($(this), 2, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-s\"></span></a>";	
					} else {
						// User Down voted link
					$recentLinksContent .= "
						<a id=\"recent-link".$link->id."-up\" class=\"ui-state-default ui-corner-all\" title=\"vote this link up\" onclick=\"recentVoteAction($(this), 1, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-n\"></span></a>
						<a id=\"recent-link".$link->id."-down\" class=\"ui-state-default ui-corner-all\" title=\"vote this link down\" onclick=\"recentVoteAction($(this), 2, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-s downvoted\"></span></a>";	
						
					}
				}
				else {
					// User has not voted on this link yet
					$recentLinksContent .= "
					<a id=\"recent-link".$link->id."-up\" class=\"ui-state-default ui-corner-all\" title=\"vote this link up\" onclick=\"recentVoteAction($(this), 1, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-n\"></span></a>
					<a id=\"recent-link".$link->id."-down\" class=\"ui-state-default ui-corner-all\" title=\"vote this link down\" onclick=\"recentVoteAction($(this), 2, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-s\"></span></a>
					";
				}
				
			}
			else {
			$recentLinksContent .= "
			<a id=\"recent-link".$link->id."-up\" class=\"ui-state-default ui-corner-all\" title=\"vote this link up\" onclick=\"recentVoteAction($(this), 1, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-n\"></span></a>
			<a id=\"recent-link".$link->id."-down\" class=\"ui-state-default ui-corner-all\" title=\"vote this link down\" onclick=\"recentVoteAction($(this), 2, ".$link->id.")\"><span class=\"ui-icon ui-icon-circle-arrow-s\"></span></a>
			";
			}
			
			$recentLinksContent .= "
			</td>
			<td><a href=\"$linkURL\">".$link->title."</a>
			<br />
			<label title=\"".$link->votes."\" id=\"recent-link".$link->id."-points\">".$link->votes." points</label> | <a href=\"#\">".self::$howMany->displayHowManyComments($link->id)." comments</a>
			</td>
			</tr>";
		}
	    
		self::$content .= "
			<h3 class=\"centerHeader\">Recently viewed links</h3>
			<table id=\"recentlyViewed\" border=\"0\" width=\"95%\">
				$recentLinksContent
			<tr>
			<td> </td>
			<td class=\"clearbtn\"><a title=\"clear recently viewed link history\" onclick=\"return clearHistory()\" href=\"#\">clear</a></td>
			</tr>
			</table>";
		return true;
		}
		else
		return false;
		
		
	}
	
	public function buildSidebarContent() {
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$frontController = Zend_Controller_Front::getInstance();
		$request = $frontController->getRequest();
		
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();
		
		// Check if this is a blab category page
		$category = '';
		if (isset($params['category']))
		{
			$category = $params['category'];
		}
		
		self::$content .= "
	    <ul class=\"nav\">
	      <li><a href=\"/submit\">Submit a Link</a></li>
	      <li><a href=\"/blabs/create\">Create your own category</a></li>
	    </ul>
		<div id=\"sidebarContent\">";

		if ($controller == "user" && $action == "account") {
				if (isset($params['username']))
				$user = self::$utils->XssCleaner($params['username']);
				$select = $db->select();
				$select->from("users");
				$select->where("username = ?", $user);
				$results = $db->fetchAll($select);
				$created = 'Not Created'; $lastLogin = 'Never';
				if (count($results) > 0)
				{
				$created = self::$utils->TimeSince(strtotime($results[0]["date_created"]));
				$lastLogin = self::$utils->TimeSince(strtotime($results[0]["last_login"]));
				}

				
			self::$content .= "
			<div class=\"titlebox\">
			<h1><a href=\"/b/$user\">$user</a></h1>
			<div class=\"bottom\">
			Member for <span class=\"age\">$created</span><br />
			Last login <span class=\"age\">$lastLogin </span>
			</div>
			</div>";
		}

		if ($controller == 'blabs' && $action == 'display') {
			// Display Blab specific content
			// Change submit link to be blab specific (will pre-select this blab on the link creation form)
			self::$content = str_replace('"/submit"', '"/submit/'.$category.'"', self::$content);
			
			$select = $db->select();
			$select->from( array('b' => 'blabs'),
						array('id', 'user_id', "title", "description", "date_created"));
			$select->join(array('u' => 'users'),
                    'b.user_id = u.id', array("username"));
			$select->where("b.title = ?", $category);
			$results = $db->fetchRow($select);
			$description = $results['description'];
			$founder = $results['username'];
			$founded = self::$utils->TimeSince(strtotime($results['date_created']));
			
			
			$blabID = $results['id'];
			
			$select = $db->select();
			$select->from( array('u' => 'users'),
						array('id', 'username', "email"));
			$select->join(array('m' => 'user_meta'),
                    'u.id = m.user_id', array("lookup_id", "key", "value"));
			$select->where("m.lookup_id = ?", $blabID);
			$select->where("m.key = ?", "moderator");
			$select->where("m.value = ?", 1);
			$results = $db->fetchAll($select);
			
			$LoggedInUsername = ''; $isMod = false;
      		  if (self::$loggedIn) {
        		$LoggedInUsername = self::$username;
        	}
			
			
			self::$content.= "
			<div class=\"titlebox\">
			<h1><a href=\"/b/$category\">$category</a></h1>
			<div class=\"usertext-body\">
			$description
			</div>
			<div class=\"bottom\">
			created by <a href=\"/user/$founder\">$founder</a><span class=\"userattrs\"></span><span class=\"age\">$founded</span>
			</div>
			</div>
			<div class=\"sidecontentbox \">
			<h2>MODERATORS</h2>
			<div class=\"blabcontent\">";
	foreach ($results as $row) {
		self::$content .= '<a class="author" href="/user/'.$row['username'].'">'.$row['username'].'</a><span class="userattrs"></span>';
		$isMod = ($row['username'] == $LoggedInUsername) ? true : false;
	}

	self::$content .= "
	<a href=\"/index/notfound?t=notimpl\" id=\"mailLink\" class=\"mailLink\"><span class=\"ui-icon ui-icon-mail-closed\"></span>send a message</a>
	</div>
	<div id=\"data_mailLink\" style=\"display:none;\">
	send the moderators of &quot;$category&quot; a message
	</div>
	</div>";
	// Check if user is a moderator 
        if ($isMod) {
      	 self::$content .= "
			<div class=\"ismod\">you are a moderator of this blab. (<a onclick=\"alert('are you sure?')\" href=\"#\">remove me</a>)</div>
			<div class=\"sidecontentbox \">
			<h2>ADMIN BOX</h2>
			<div class=\"blabcontent\">
			<ul class=\"flat-vert icon-menu hover\">
			<li><a href=
			\"/blabs/edit/category/$category\">
			<span class=\"blab-icons ui-icon ui-icon-pencil\"></span>
			Blab settings</a></li>
			<li><a href=
			\"/blabs/edit/category/$category/change/moderators\">
			<span class=\"blab-icons ui-icon ui-icon-star\"></span>
			edit moderators</a></li>
			</ul>
			</div>
			
			</div>";
        }

	}
	
	$this->buildRecentLinks();
		
		
		return self::$content."</div><!-- end sidebar content -->";
		
	}
	
	
	
}
