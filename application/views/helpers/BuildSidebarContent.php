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

	public function buildSidebarContent() {
		$content = null;
		$utils = new Application_Model_Utils();
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
		
				$content .= <<<EOT
	    <ul class="nav">
	      <li><a href="/submit">Submit a Link</a></li>
	      <li><a href="/blabs/create">Create your own category</a></li>
<!--	      <li><a href="#">Link three</a></li>-->
<!--	      <li><a href="#">Link four</a></li>-->
	    </ul>
	<div id="sidebarContent">
EOT;

		if ($controller == "user" && $action == "account") {
				if (isset($params['username']))
				$user = $utils->XssCleaner($params['username']);
				$select = $db->select();
				$select->from("users");
				$select->where("username = ?", $user);
				$results = $db->fetchAll($select);
				$created = 'Not Created'; $lastLogin = 'Never';
				if (count($results) > 0)
				{
				$created = $utils->TimeSince(strtotime($results[0]["date_created"]));
				$lastLogin = $utils->TimeSince(strtotime($results[0]["last_login"]));
				}

				
			$content .= <<<EOT
<div class="titlebox">
<h1><a href="/b/$user">$user</a></h1>
<div class="bottom">
Member for <span class="age">$created</span><br />
Last login <span class="age">$lastLogin </span>
</div>
</div>
EOT;
		}

		if ($controller == 'blabs' && $action == 'display') {
			// Display Blab specific content
			// Change submit link to be blab specific (will pre-select this blab on the link creation form)
			$content = str_replace('"/submit"', '"/submit/'.$category.'"', $content);
			
			$select = $db->select();
			$select->from( array('b' => 'blabs'),
						array('id', 'user_id', "title", "description", "date_created"));
			$select->join(array('u' => 'users'),
                    'b.user_id = u.id', array("username"));
			$select->where("b.title = ?", $category);
			$results = $db->fetchRow($select);
			$description = $results['description'];
			$founder = $results['username'];
			$founded = $utils->TimeSince(strtotime($results['date_created']));
			
			
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
			
			$auth = Zend_Auth::getInstance();
			$LoggedInUsername = ''; $isMod = false;
      		  if ($auth->hasIdentity()) {
        		$LoggedInUsername = $auth->getIdentity()->username;
        	}
			
			
			
			$content.= <<<EOT
<div class="titlebox">
<h1><a href="/b/$category">$category</a></h1>
<div class="usertext-body">
$description
</div>
<div class="bottom">
created by <a href="/user/$founder">$founder</a><span class="userattrs"></span><span class="age">$founded</span>
</div>
</div>
<div class="sidecontentbox ">
<h2>MODERATORS</h2>
<div class="blabcontent">
EOT;
	foreach ($results as $row) {
		$content .= '<a class="author" href="/user/'.$row['username'].'">'.$row['username'].'</a><span class="userattrs"></span>';
		$isMod = ($row['username'] == $LoggedInUsername) ? true : false;
	}

$content .= <<<EOT
<a href="/message/compose?to=%23testingmang" id="mailLink" class="mailLink"><span class="ui-icon ui-icon-mail-closed"></span>send a message</a>
</div>
<div id="data_mailLink" style="display:none;">
send the moderators of &quot;$category&quot; a message
</div>
</div>
EOT;
// Check if user is a moderator 
        if ($isMod) {
       $content .= <<<EOT
<div class="ismod">you are a moderator of this blab. (<a onclick="alert('are you sure?')" href="#">remove me</a>)</div>
<div class="sidecontentbox ">
<h2>ADMIN BOX</h2>
<div class="blabcontent">
<ul class="flat-vert icon-menu hover">
<li><a href=
"/blabs/edit/category/$category">
<span class="blab-icons ui-icon ui-icon-pencil"></span>
Blab settings</a></li>
<li><a href=
"/blabs/edit/category/$category/change/moderators">
<span class="blab-icons ui-icon ui-icon-star"></span>
edit moderators</a></li>
</ul>
</div>

</div>
EOT;
        
        }

		}
		
		$content2 = <<<EOT
<p>
controller: $controller
<br />
action: $action
<br />
category: $category 
</p>
EOT;

		$content .= <<<EOT
<h3 class="centerHeader">Recently viewed links</h3>
	<table id="recentlyViewed" border="0" width="95%">
	<tr>
	<td class="viewedVote">
	<a id="recent-link328-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="recentVoteAction(this, 1, 328)"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>
	<a id="recent-link328-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="recentVoteAction(this, 2, 328)"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
	</td>
	<td><a href="#">Calgary Police lay criminal charges against Calgary website operator for criticizing Police</a>
	<br />
	<label title="851" id="recent-link328-points">851 points</label> | <a href="#">320 comments</a>
	</td>
	</tr>
	<tr>
	<td class="viewedVote">
	<a id="recent-link330-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="recentVoteAction(this, 1, 330)"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>
	<a id="recent-link330-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="recentVoteAction(this, 2, 330)"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
	</td>
	<td><a href="#">How do they keep doing these in one continuous shot?</a>
	<br />
	<label title="451" id="recent-link330-points">451 points</label> | <a href="#">320 comments</a>
	</td>
	</tr>
		<tr>
	<td class="viewedVote">
	<a id="recent-link332-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="recentVoteAction(this, 1, 332)"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>
	<a id="recent-link332-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="recentVoteAction(this, 2, 332)"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>
	</td>
	<td><a href="#">Jon Stewart's 'Rally to Restore Sanity' already outpacing Glenn Beck's rally</a>
	<br />
	<label title="851" id="recent-link332-points">851 points</label> | <a href="#">320 comments</a>
	</td>
	</tr>
	</table>
EOT;
		
		return $content."</div><!-- end sidebar content -->";
		
	}
	
	
}
