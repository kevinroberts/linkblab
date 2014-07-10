<?php
/**
 *
 * @author kevin
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * UserList helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_UserList {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * 
	 */
	public function userList() {
			$content = '';
		$db = Zend_Db_Table::getDefaultAdapter();
	
				$select = $db->select();
				$select->from("users");
				$select->where("id > ?", 1);
				$results = $db->fetchAll($select);
				if (count($results) < 1) {
					return false;
				}
				// Build data grid table
				$content .= '<table cellpadding="0" cellspacing="0" border="0" class="display" id="blabsGrid">'.PHP_EOL;
				$content .= '<thead>'.PHP_EOL.'<tr>'.PHP_EOL;
				$content .= '<th>ID</th>'.PHP_EOL;
				$content .= '<th>Username</th>'.PHP_EOL;
				$content .= '<th>Email</th>'.PHP_EOL;
				$content .= '<th>Role</th>'.PHP_EOL;
				$content .= '<th>IP</th>'.PHP_EOL;
				$content .= '<th>Last Login</th>'.PHP_EOL;
				$content .= '</tr>'.PHP_EOL.'</thead>'.PHP_EOL.'<tbody>'.PHP_EOL;
				foreach ($results as $row) {
					
					$content .= '<tr>'.PHP_EOL;
					
					$content .= '<td>'.$row['id'].'</td>'.PHP_EOL;
					$content .= '<td>'.$row['username'].'</td>'.PHP_EOL;
					$content .= '<td>'.$row['email'].'</td>'.PHP_EOL;
					$content .= '<td>'.$row['role'].'</td>'.PHP_EOL;
					$content .= '<td>'.$row['user_ip_address'].'</td>'.PHP_EOL;
					$content .= '<td>'.$row['last_login'].'</td>'.PHP_EOL;
					
					$content .= '</tr>'.PHP_EOL;
					
				}
				
				$content .= '</tbody>'.PHP_EOL.'<tfoot>'.PHP_EOL.'<tr>'.PHP_EOL;
				$content .= '<th>ID</th>'.PHP_EOL;
				$content .= '<th>Username</th>'.PHP_EOL;
				$content .= '<th>Email</th>'.PHP_EOL;
				$content .= '<th>Role</th>'.PHP_EOL;
				$content .= '<th>IP</th>'.PHP_EOL;
				$content .= '<th>Last Login</th>'.PHP_EOL;
				
				$content .= '</tr>'.PHP_EOL.'</tfoot>'.PHP_EOL.'</table>'.PHP_EOL;
				
		
		return $content;
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
