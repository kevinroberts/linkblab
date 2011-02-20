<?php
/**
 *
 * @author sudoKevin
 * @version 1.0
 */

/**
 * BuildCommentPage helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BuildCommentPage {
	// standard variables
	public static $content = '', $loggedIn = false, $userID, $blabInfo, $numberComments;
	// object variables
	public static $utils, $link, $linkMapper, $linkBlabs, $howMany, $reportBtn, $displayName, $sort;
	
	public function __construct() {
		self::$utils = new Application_Model_Utils ( );
		self::$link = new Application_Model_Link ( );
		self::$linkMapper = new Application_Model_LinksMapper ( );
		$auth = Zend_Auth::getInstance ();
		if ($auth->hasIdentity ()) {
			self::$loggedIn = true;
			$this->userID = $auth->getIdentity()->id;
		}
		// Include other comment and link related view helpers
		include_once ("displayName.php");
		include_once ("displayBlab.php");
		include_once ("displayHowManyComments.php");
		include_once ("BuildReportButton.php");
		include_once ("linkBuilder.php");
		include_once ("BuildSortingOptions.php");
		self::$sort = new Zend_View_Helper_BuildSortingOptions();
		self::$linkBlabs = new Zend_View_Helper_displayBlab ( );
		self::$howMany = new Zend_View_Helper_displayHowManyComments ( );
		self::$reportBtn = new Zend_View_Helper_BuildReportButton ( );
		self::$displayName = new Zend_View_Helper_displayName ( );
	
	}
	
	private function buildLinkContent() {
		
		// If a link doesn't exist...
		if (is_null ( self::$link->title )) {
			return false;
		}
		
		// $linkBlab fills an array with [0] = blab title, [1] = anchor link to blab
		self::$blabInfo = self::$linkBlabs->displayBlab ( self::$link->blabID, 1 );
		$linkURL = (self::$link->isSelf == 1) ? '/b/' . self::$blabInfo [0] . '/comments/' . self::$link->id.'/'.self::$link->urlTitle : self::$link->linkurl;
		
		self::$numberComments = self::$howMany->displayHowManyComments ( self::$link->id );
		
		$this->content .= '<div class="link singleLink">';
		
		if (self::$loggedIn) {
			$linkbuilder = new Zend_View_Helper_linkBuilder ( );
			$this->content .= $linkbuilder->linkBuilder ( self::$link->id, self::$link->downvotes, self::$link->votes, self::$link->upvotes );
		} else {
			$this->content .= '<div style="width: 5ex;" class="midcol unvoted">' . '<a id="link.' . self::$link->id . '-up" class="ui-state-default ui-corner-all" title="vote this link up" onclick="voteAction($(this), 1, ' . self::$link->id . ')"><span class="ui-icon ui-icon-circle-arrow-n"></span></a>' . '<div class="score downVotes">' . self::$link->downvotes . '</div>' . '<div class="score unVoted">' . self::$link->votes . '</div>' . '<div class="score upVotes">' . self::$link->upvotes . '</div>' . '<a id="link' . self::$link->id . '-down" class="ui-state-default ui-corner-all" title="vote this link down" onclick="voteAction($(this), 2, ' . self::$link->id . ')"><span class="ui-icon ui-icon-circle-arrow-s"></span></a>' . '</div>';
		}
		
		if (! (is_null ( self::$link->thumbnail ))) {
			$this->content .= '<a href="' . $linkURL . '" class="thumbnail">&#8203;<img alt="" src="' . self::$link->thumbnail . '" /></a>';
		}
		$spanClass = (self::$link->isSelf == 1) ? ' style="display:none;"' : '';
		$this->content .= '<div class="entry">' . 

		'<p class="title"><a href="' . $linkURL . '" class="title loggedin">' . self::$link->title . '</a>' . '&nbsp;<span' . $spanClass . ' class="domain">(<a title="see more links from this domain" href="/domain/' . self::$link->domain . '/">' . self::$link->domain . '</a>)</span></p>' . 

		'<p class="tagline">submitted ' . self::$utils->TimeSince ( strtotime ( self::$link->dateCreated ) ) . ' ago by ' . self::$displayName->displayName ( self::$link->userID, "autho user-" . self::$link->userID ) . '<span class="userattrs"></span> to ' . self::$blabInfo [1] . '</p>';
		// If this is a self post, output the description here:
		if (self::$link->isSelf == 1) {
			$description = self::$utils->docodaOutput ( self::$link->description );
			$this->content .= "
				<div class=\"expando\">
					<div class=\"usertext\">
						<div class=\"md\">
							$description
						</div>
					</div>
				</div>";
		}
		$this->content .= '<ul class="flat-list buttons">';
		
		if (self::$link->isNsfw == 1) {
			$this->content .= '<li class="ui-corner-all nsfw-stamp stamp"> <acronym title="Adult content: Not Safe For Work">NSFW</acronym> </li>';
		}
		$this->content .= '<li class="first">' . '<a target="_parent" href="/b/' . self::$blabInfo [0] . '/comments/' . self::$link->id .'/'.self::$link->urlTitle. '" class="comments">' . self::$numberComments . ' comments</a>' . '</li>' . '<li>' . self::$reportBtn->buildReportButton ( self::$link->id ) . '</li>' . '</ul>' . '</div>';
		
		$this->content .= '</div><!-- end link --> <div class="clrLeft"></div>';
		return true;
	
	}
	
	public function getDefaultSort() {
		$frontController = Zend_Controller_Front::getInstance();
		$request = $frontController->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();
		$sort = 'hot DESC';
		switch ($params['sort']) {
			case 'hot':
				$sort = 'hot DESC';
				break;
			case 'controversial':
				$sort = 'controversy DESC';
				break;
			case 'new':
				$sort = 'date_added DESC';
				break;
			case 'old':
				$sort = 'date_added ASC';
				break;
			case 'top':
				$sort = 'votes DESC';
				break;
			default: 
				break;
		}
		
		return $sort;
	}
	
	/** 	
	 * 		buildCommentsPage is a view helper for
	 *  	building the content for a link's comment page
	 */
	public function buildCommentPage($linkID, $commentID = null) {
		// retrieve link for this comment page from the mapper
		self::$linkMapper->find ( $linkID, self::$link );
		
		// Build link html
		if ($this->buildLinkContent () == false) {
			// no link was found
			return '<div class="link" style="color: red;">There is nothing to see here...</div>';
		}
		
		$commentURL = "/b/".self::$blabInfo[0]."/comments/".self::$link->id.'/'.self::$link->urlTitle;
		
		$commentForm = '
			<a href="#" class="hideForm" onclick="return hideForm($(this))" title="collapse this form">[- Add Comment]</a>
			<a href="#" class="hideForm" onclick="return openRichEditor($(this))" title="open rich text editor"><span style="display: inline-block; position: relative; top: 2px;" class="ui-icon ui-icon-newwin"></span>Rich Text Editor</a>
			<form id="form-' . $linkID . '" name="newCommentForm" method="post" onsubmit="return post_comment($(this), \'parent\')" class="usertext cloneable" action="">
				<div style="" class="usertext-edit">
					<div>
						<textarea class="newCommentArea" name="text" cols="1" rows="1"></textarea>
					</div>
					<div class="bottom-area">
					<div style="display:none;" class="form_errors"></div>
						<div class="usertext-buttons">
						    <input type="hidden" value="' . $linkID . '" name="link_id">
							<button class="save" type="submit">submit</button>
							<span class="status" style="display: none;">submitting...</span>
						</div>
					</div>
				</div>
			</form>';
		// If there are comments associated with this link:	
		if (self::$numberComments > 0) {
			$sortingOptions = self::$sort->buildSortingOptions(true);
			$howManyTitle = (self::$numberComments > 1) ? 'All ' . self::$numberComments . ' Comments' : "All Comments";
			$cModel = new Application_Model_Comments ( self::$blabInfo [0] );
			// If this is a permalink to one comment:
			if (! is_null ( $commentID )) {
				// hide the new comment form by default for permalink pages:
				$hideDefault = true;
				$commentContent = $cModel->getAllComments ( $linkID, null, $commentID );
				
				$howManyTitle = "
				<div>
					<div style=\"padding: 0pt 0.7em;\" class=\"ui-state-highlight ui-corner-all\"> 
						<p><span style=\"float: left; margin-right: 0.3em;\" class=\"ui-icon ui-icon-info\"></span>
						You are viewing a single comment's thread. <a rel=\"nofollow\" href=\"".$commentURL."\">view the rest of the comments -></a></p>
					</div>
				</div>";
			} else {
				$commentContent = $cModel->getAllComments ( $linkID, $this->getDefaultSort() );
				$hideDefault = false;
			}
			
			// If User is not logged => do not Output New Comment Form:
			if (self::$loggedIn == false || $hideDefault !== false) {
			$commentForm = '';
			}
			
		 /*
		 *  Build Comment HTML
		 */
		$this->content .= "
		<div class=\"commentsContent\">
		<a id=\"commentsArea\" name=\"commentsArea\" style=\"\"></a> 
			<div class=\"commentsTitlebar\">
			$howManyTitle $sortingOptions
			</div>
			$commentForm
			$commentContent
		</div>";
		} else { // no comments have been submitted: output empty template
			$commentForm = (self::$loggedIn == false) ? '' : $commentForm;
			$this->content .= "
			<div class=\"commentsContent\">
				<div class=\"commentsTitlebar\">
					no comments (yet)
				</div>
				$commentForm
			<div id=\"noComments\" style=\"margin: 15px 5px 30px 10px; color: red;\">there doesn't seem to be anything here...</div>
			
			</div>";
		
		}
		return $this->content;
	}

}
