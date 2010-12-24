<?php
/**
 *
 * @author sudoKevin
 * @version 
 */

/**
 * displayLink helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_displayLink {
	
	// http://www.talkincode.com/cut-a-string-to-a-specified-length-with-php-135.html
	private function substrwords($text,$maxchar,$end='...') {
 		if(strlen($text)>$maxchar)
 		{
			$words=explode(" ",$text);
			$output = '';
			$i=0;
			while(1)
			{
				$length = (strlen($output)+strlen($words[$i]));
				if($length>$maxchar) 
				{
					break;
				}
				else
				{
					$output = $output." ".$words[$i];
					++$i;
				};
			};
			}
			else
			{
				$output = $text;
			}
		return $output.$end;
}
// better implementation using PHP's Wordwrap function::
private function cutstr($str, $length, $ellipsis='...') {
   $cut = (array)explode('\n\n', wordwrap($str, $length, '\n\n'));
   return $cut[0].((strlen($cut[0])<strlen($str))?$ellipsis:'');
}

	/**
	 *  
	 */
	public function displayLink($linkID) {
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from('links', array("id", "title"));
		$select->where("id = ?", $linkID);	
		$result = $db->fetchRow($select);
		 
		return $this->cutstr($result['title'], 50);
	}
	
}
