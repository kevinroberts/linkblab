<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * AccountEdit helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_AccountEdit extends Zend_View_Helper_Abstract {
	

	/**
	 * 
	 */
	public function accountEdit($case, $userID = null) {
		$content = '';
		$db = Zend_Db_Table::getDefaultAdapter();
		
		switch ($case) {
			case 'frontpage':
			$select = $db->select();
			$select->from( array('b' => 'blabs'),
						array('id', "title","user_id" , "head_title", "description", "date_created"));
			$select->join(array('s' => 'subscriptions'),
                    'b.id = s.blab_id', array("user_id", "display_order"));
			$select->where("s.user_id = ?", $userID);
			$select->order(array("s.display_order ASC"));
			$resultSet = $db->fetchAssoc($select);
			 foreach ($resultSet as $key => $row) {
			 	$content .= '<li id="frontpage_'.$row['id'].'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$row['display_order'].'. '.$row['head_title'].' <em class="blabinfo">(/b/'.$row['title'].')</em> </li>';
			 }
			break;
			case 'blabs': 
				$select = $db->select();
				$select->from("blabs");
				$select->where("user_id = ?", $userID);
				$results = $db->fetchAll($select);
				if (count($results) < 1) {
					return false;
				}
				// Build data grid table
				$content .= '<table cellpadding="0" cellspacing="0" border="0" class="display" id="blabsGrid">'.PHP_EOL;
				$content .= '<thead>'.PHP_EOL.'<tr>'.PHP_EOL;
				$content .= '<th>Name</th>'.PHP_EOL;
				$content .= '<th>Title</th>'.PHP_EOL;
				$content .= '<th>Date Created</th>'.PHP_EOL;
				$content .= '<th><img src="/images/grid/delete.png" /> Read Only?</th>'.PHP_EOL;
				$content .= '</tr>'.PHP_EOL.'</thead>'.PHP_EOL.'<tbody>'.PHP_EOL;
				foreach ($results as $row) {
					
					$content .= '<tr>'.PHP_EOL;
					
					$content .= '<td>'.$row['title'].' (<a href="/b/'.$row['title'].'">/b/'.$row['title'].'</a>)</td>'.PHP_EOL;
					$content .= '<td>'.$row['head_title'].'</td>'.PHP_EOL;
					$content .= '<td>'.$row['date_created'].'</td>'.PHP_EOL;
					$isChecked = ($row['read_only'] == 1) ? 'checked="checked"' : '';
					if (empty($isChecked))
					$content .= '<td style="text-align:center;"><input type="checkbox" name="check'.$row['id'].'" value="'.$row['id'].'"></td>'.PHP_EOL;
					else // is read only
					$content .= '<td style="text-align:center;"><input onChange="addToOpen(this)" type="checkbox" name="readOnly~'.$row['id'].'" value="readOnly~'.$row['id'].'"'.$isChecked.'></td>'.PHP_EOL;
					
					$content .= '</tr>'.PHP_EOL;
					
				}
				
				$content .= '</tbody>'.PHP_EOL.'<tfoot>'.PHP_EOL.'<tr>'.PHP_EOL;
				$content .= '<th>Name</th>'.PHP_EOL;
				$content .= '<th>Title</th>'.PHP_EOL;
				$content .= '<th>Date Created</th>'.PHP_EOL;
				$content .= '<th><img src="/images/grid/delete.png" /> Read Only?</th>'.PHP_EOL;
				
				$content .= '</tr>'.PHP_EOL.'</tfoot>'.PHP_EOL.'</table>'.PHP_EOL;
				
				break;
			default:
				return '';
			break;
		}
		
		return $content;
	}
	

}
