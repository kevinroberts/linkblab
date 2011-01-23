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
	public function buildSortingOptions() {
		$content = null;
		$frontController = Zend_Controller_Front::getInstance();
		$request = $frontController->getRequest();
		
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();

		// If this is the home page
		if ($controller == 'index' && $action == 'index') {
		$content .= <<<EOT
		<div class="linkSortOptions">
			sorting by <span class="selected"></span>
	
			<select id="sortOptionsDropdown" class="linkselect">
				<option value="index|hot">what&rsquo;s hot</option>
				<option value="index|controversial">controversial</option>
				<option value="index|top">top scoring</option>
				<option value="index|saved">saved links</option>
			</select>
		</div>
EOT;
		}
	 // If this is a Blab Specific page and not the comments page
	 if ($controller == 'blabs' && $action == 'display' && !isset($params['comments']) && !isset($params['comment'])) {
	 	$cat = $params["category"];
		$content .= <<<EOT
		<div class="linkSortOptions">
			sorting by <span class="selected"></span>
	
			<select id="sortOptionsDropdown" class="linkselect">
				<option value="$cat|hot">what&rsquo;s hot</option>
				<option value="$cat|controversial">controversial</option>
				<option value="$cat|top">top scoring</option>
			</select>
		</div>
EOT;
		}
		
		return $content;
	}
	
}
