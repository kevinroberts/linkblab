<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * BuildCommentPage helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BuildCommentPage {
	
	/**
	 *  
	 */
	public function buildCommentPage($linkID) {
		$content = '';
		$utils = new Application_Model_Utils();
		$link = new Application_Model_Link();
		$linkMapper = new Application_Model_LinksMapper();
		$auth = Zend_Auth::getInstance(); $loggedIn = false;
		if ($auth->hasIdentity())
        {
        	$loggedIn = true;
            $userID = $auth->getIdentity()->id;
		}
		
		$linkMapper->find($linkID, $link);
		// If link doesn't exist...
		if (is_null($link->title)) 
		{
			 return '<div class="link" style="color: red;">There is nothing to see here...</div>'; 
		}
		
		// Build link html
		
		// view helpers
		include_once("displayName.php"); include_once("displayBlab.php"); include_once("displayHowManyComments.php");
		include_once("BuildReportButton.php"); include_once("linkBuilder.php");
		$linkBlabs = new Zend_View_Helper_displayBlab();
		$linkBlab = $linkBlabs->displayBlab($link->blabID, 1); // returns an array with [0] = blab title, [1] = anchor link to blab
		$linkURL = ($link->isSelf == 1) ? '/b/'.$linkBlab[0].'/comments/'.$link->id : $link->linkurl;
		
		
		$howMany = new Zend_View_Helper_displayHowManyComments();
		$reportBtn = new Zend_View_Helper_BuildReportButton();
		$displayName = new Zend_View_Helper_displayName();
		$numberComments = $howMany->displayHowManyComments($link->id);
		
		$content .= '<div class="link singleLink">';
		
		if ($loggedIn) {
		$linkbuilder = new Zend_View_Helper_linkBuilder();
		$content .= $linkbuilder->linkBuilder($link->id, $link->downvotes, $link->votes, $link->upvotes);
		}
		else {
			$content .= 
			'<div style="width: 5ex;" class="midcol unvoted">'.
			'<a id="link.'.$link->id.'-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="voteAction($(this), 1, '.$link->id.')"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>'.
			'<div class="score downVotes">'.$link->downvotes.'</div>'.
			'<div class="score unVoted">'.$link->votes.'</div>'.
			'<div class="score upVotes">'.$link->upvotes.'</div>'.
			'<a id="link'.$link->id.'-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="voteAction($(this), 2, '.$link->id.')"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>'.
			'</div>';
		}
		if (!(is_null($link->thumbnail))) {
				$content.= '<a href="'.$linkURL.'" class="thumbnail">&#8203;<img alt="" src="'.$link->thumbnail.'" /></a>';
			}
			$spanClass = ($link->isSelf == 1) ? ' style="display:none;"' : '';
			$content.= 
			'<div class="entry">'.
			
			'<p class="title"><a href="'.$linkURL.'" class="title loggedin">'.$link->title.'</a>'.
			'&nbsp;<span'.$spanClass.' class="domain">(<a title="see more links from this domain" href="/domain/'.$link->domain.'/">'.$link->domain.'</a>)</span></p>'.
			
			'<p class="tagline">submitted '.$utils->TimeSince(strtotime($link->dateCreated)).' ago by '.$displayName->displayName($link->userID, "autho user-".$link->userID).'<span class="userattrs"></span> to '.$linkBlab[1].'</p>';
			// If this is a self post, output the description here:
			if ($link->isSelf == 1) {
				$description = $utils->docodaOutput($link->description);
				$content .= <<<EOT
				<div class="expando">
					<div class="usertext">
						<div class="md">
							$description
						</div>
					</div>
				</div>
EOT;
			}
			$content .= '<ul class="flat-list buttons">';
			
			if ($link->isNsfw == 1) {
				$content .= '<li class="ui-corner-all nsfw-stamp stamp"> <acronym title="Adult content: Not Safe For Work">NSFW</acronym> </li>';
			}
			$content .= '<li class="first">'.
			'<a target="_parent" href="/b/'.$linkBlab[0].'/comments/'.$link->id.'" class="comments">'.$numberComments.' comments</a>'.
			'</li>'.
			'<li>'.
			$reportBtn->buildReportButton($link->id).
			'</li>'.
			'</ul>'.
			'</div>';
			
		
		$content .= '</div><!-- end link --> <div class="clrLeft"></div>';
		
		// If User is logged in Output New Comment Form:
		$commentForm = '';
		if ($loggedIn) {
			$commentForm = '
			<a href="#" class="hideForm" onclick="return hideForm($(this))" title="collapse this form">[- Add Comment]</a>
			<form id="form-'.$linkID.'" method="post" onsubmit="return post_comment($(this), \'parent\')" class="usertext cloneable" action="">
				<div style="" class="usertext-edit">
					<div>
						<textarea name="text" cols="1" rows="1"></textarea>
					</div>
					<div class="bottom-area">
					<div style="display:none;" class="form_errors"></div>
						<div class="usertext-buttons">
						    <input type="hidden" value="'.$linkID.'" name="link_id">
							<button class="save" type="submit">submit</button>
							<span class="status" style="display: none;">submitting...</span>
						</div>
					</div>
				</div>
			</form>
			';
			
		}
		// If there are comments associated with this link:	
		if ($numberComments > 0) {
		$howManyTitle = ($numberComments > 1) ? 'All '.$numberComments.' Comments' : "All Comments";
		$cModel = new Application_Model_Comments($linkBlab[0]);
		$commentContent = $cModel->getAllComments($linkID);

		/*
		 *  Build Comment HTML
		 */
		$content .= <<<EOT
<div class="commentsContent">
	<div class="commentsTitlebar">
		$howManyTitle
	</div>
	$commentForm
	$commentContent
	
</div>
EOT;
				}
		else { // no comments have been submitted: output empty template
$content .= <<<EOT
<div class="commentsContent">
	<div class="commentsTitlebar">
		no comments (yet)
	</div>
	$commentForm
<div style="margin: 15px 5px 30px 10px; color: red;">there doesn't seem to be anything here...</div>

</div>
EOT;
		
			
		}
		return $content;
	}
	

}
