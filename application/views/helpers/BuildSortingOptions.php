<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * BuildSortingOptions helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BuildSortingOptions {
	

	/**
	 *  
	 */
	public function buildSortingOptions($commentSort = false) {
		if ($commentSort) {
			return $this->buildCommentSortingOptions();
		}
		$content = null;
		$tmpContent = null;
		$frontController = Zend_Controller_Front::getInstance();
		$request = $frontController->getRequest();
		
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();
		$sortOps = array(
		"hot" => '<option value="index|hot">what\'s hot</option>',
		"controversial" => '<option value="index|controversial">controversial</option>',
		"top" => '<option value="index|top">top scoring</option>',
		"saved" => '<option value="index|saved">saved links</option>'
		);
		

		// If this is the home page
		if ($controller == 'index' && $action == 'index') {
			if (isset($params['sort']) && $params['sort'] !== 'index') {
				
				$tmpContent .= $sortOps[$params['sort']];
				unset($sortOps[$params['sort']]);
								
				foreach ($sortOps as $key => $value) {
					$tmpContent .= $value;
				}
			}
			else
			foreach ($sortOps as $key => $value) {
					$tmpContent .= $value;
				}
			
		$content .= <<<EOT
		<div class="linkSortOptions">
			sorting by <span class="selected"></span>
	
			<select id="sortOptionsDropdown" class="linkselect">
			$tmpContent
			</select>
		</div>
EOT;
		}
	 // If this is a Blab Specific page and not the comments page
	 if ($controller == 'blabs' && $action == 'display' && !isset($params['comments']) && !isset($params['comment'])) {
	 	$cat = $params["category"];
	 	// REPLACE index with the blab category	 	
	 	foreach ($sortOps as $key => $value) {
			$sortOps[$key] = str_replace('index|',$cat.'|', $value);
		}
		
		if (isset($params['sort']) && $params['sort'] !== 'index') {
				
				$tmpContent .= $sortOps[$params['sort']];
				unset($sortOps[$params['sort']]);
								
				foreach ($sortOps as $key => $value) {
					$tmpContent .= $value;
				}
			}
			else
			foreach ($sortOps as $key => $value) {
					$tmpContent .= $value;
				}
		
		$content .= <<<EOT
		<div class="linkSortOptions">
			sorting by <span class="selected"></span>
	
			<select id="sortOptionsDropdown" class="linkselect">
				$tmpContent
			</select>
		</div>
EOT;
		}
		
		return $content;
	}
	
	private function buildCommentSortingOptions() {
		$utils = new Application_Model_Utils();
		$content = null; $tmpContent = null;
		$frontController = Zend_Controller_Front::getInstance();
		$request = $frontController->getRequest();
		
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();
		$sortOps = array(
		"hot" => '<option value="'.urlencode($utils->curPageURL()).'||hot">best</option>',
		"controversial" => '<option value="'.urlencode($utils->curPageURL()).'|controversial">controversial</option>',
		"new" => '<option value="'.urlencode($utils->curPageURL()).'||new">newest comments</option>',
		"old" => '<option value="'.urlencode($utils->curPageURL()).'||old">oldest comments</option>',
		"top" => '<option value="'.urlencode($utils->curPageURL()).'||top">top rated</option>'
		);
		
		// If this is a comments page
		if ($controller == 'blabs' && $action == 'display' && isset($params['comments']) && !isset($params['comment'])) {
			if (isset($params['sort']) && $params['sort'] !== 'index') {
				
				$tmpContent .= $sortOps[$params['sort']];
				unset($sortOps[$params['sort']]);
								
				foreach ($sortOps as $key => $value) {
					$tmpContent .= $value;
				}
			}
			else
			foreach ($sortOps as $key => $value) {
					$tmpContent .= $value;
				}
			
		$content .= <<<EOT
		<div class="commentSortOptions">
			sorting by <span class="selected"></span>
	
			<select id="commentSortOptionsDropdown" class="linkselect">
			$tmpContent
			</select>
		</div>
EOT;
			
		}
		// else if this is a permalink comment page
		else if ($controller == 'blabs' && $action == 'display' && !isset($params['comments']) && isset($params['comment'])) {
			
			
		}
		
		return $content;
		
	}
	
}
