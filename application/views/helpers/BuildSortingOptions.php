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
		$content = null; $tmpContent = null;
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
			if (isset($params['sort'])) {
				
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
		
	  if (isset($params['sort'])) {
				
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
	
}
