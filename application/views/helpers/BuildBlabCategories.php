<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * BuildBlabCategories helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BuildBlabCategories {
	

	
	/**
	 * 
	 */
	public function buildBlabCategories($moreLink = null) {
		$auth = Zend_Auth::getInstance();
		if (!is_null($moreLink)) {
			return ($auth->hasIdentity()) ? '<span id="moreLink"><a title="click here to edit which linkblab categories appear here" href="/blabs">EDIT-&gt;</a></span>' : '<span id="moreLink"><a title="see all the linkblab categories" href="/blabs">MORE-&gt;</a></span>';
		}
		$blabs = NULL;
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
		
		// Build customized blab category for logged in users
		
        if ($auth->hasIdentity()) {
        	$user = $auth->getIdentity();
        	    		$select = $db->select();
			$select->from("blabs");
			$select->join("subscriptions", "blabs.id = subscriptions.blab_id", "*"); //array("id", "user_id", "blab_id", "front_page")
			$select->where("subscriptions.user_id = ".$user->id);
			//$select->where("subscriptions.display_order = 1");
			$select->order('subscriptions.display_order ASC');
			$select->limit(30);
			$results = $db->fetchAll($select);
						
			$i = 0;
			foreach ($results as $row) {		
				$class = ($row['title'] == $category) ? 'class="activeBlab"' : 'class=""';
				if ($i == 0) {
					$class2 = ($category == 'all') ? 'class="activeBlab"' : 'class=""';
					$blabs .= '<a '.$class2.' href="/b/all">all</a>';
					$blabs .= ' - ';
					$class2 = ($category == 'random') ? 'class="activeBlab"' : 'class=""';
					$blabs .= '<a '.$class2.' href="/b/random">random</a>';	
					$blabs .= ' | ';
					$blabs .= '<a '.$class.' href="/b/'.$row['title'].'">'.$row['title'].'</a>';
					$blabs .= " - ";
				}
				else {
				$blabs .= '<a '.$class.' href="/b/'.$row['title'].'">'.$row['title'].'</a>';
				$blabs .= " - ";
				}
			
				$i++;
			}
			$blabs = substr($blabs,0,-1);
			$blabs = substr($blabs,0,-1); 
        	
        }
        else
        {
        	   $select = $db->select();
			$select->from("blabs");
			//$select->where("user_id = ?", 6);
			$select->limit(30);
			$results = $db->fetchAll($select);
			
			
			$i = 0;
			foreach ($results as $row) {
				$class = ($row['title'] == $category) ? 'class="activeBlab"' : 'class=""';
				if ($i == 0) {
					$class2 = ($category == 'all') ? 'class="activeBlab"' : 'class=""';
					$blabs .= '<a '.$class2.' href="/b/all">all</a>';
					$blabs .= ' - ';
					$class2 = ($category == 'random') ? 'class="activeBlab"' : 'class=""';
					$blabs .= '<a '.$class2.' href="/b/random">random</a>';	
					$blabs .= ' | ';
					$blabs .= '<a '.$class.' href="/b/'.$row['title'].'">'.$row['title'].'</a>';
					$blabs .= " - ";
				}
				else {
				$blabs .= '<a '.$class.' href="/b/'.$row['title'].'">'.$row['title'].'</a>';
				$blabs .= " - ";
				}
				$i++;
			}
			$blabs = substr($blabs,0,-1);
			$blabs = substr($blabs,0,-1); 
        }
        
        
        //$blabs .= '<span id="moreLink"><a title="see all the linkblab categories" href="/blabs">more-&gt;</a></span>';
		return $blabs;
	}
	

}
