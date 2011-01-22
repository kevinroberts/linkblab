<?php
/*
 * Model Containing Common Utility Methods
 */
class Application_Model_Utils
{
	public $isReadOnly = false, $userID = 0;

	public function __construct()
	{
		/*if (is_array($options)) {
			$this->setOptions($options);
		}*/
	}

	/*
	 * Alternate date comparison function :: with flexible levels
	 * 
	 * This is an easily extendable and pretty way to output human-readable date differences
	 * such as "1 day 2 hours ago", "6 months ago", "3 years 7 months 14 days 1 hour 4 minutes 16 seconds" etc etc. 
	 * Change "$levels = 2;" to whatever you want. A value of 1 will limit to only one number in the result 
	 * ("3 days ago"). A value of 3 would result in up to three ("3 days 1 hour 2 minutes ago") 
	 */
 public function compare_dates($date1, $date2 = null, $levels = 1 ) {
    $blocks = array(
        array('name'=>'year','amount'    =>    60*60*24*365    ),
        array('name'=>'month','amount'    =>    60*60*24*31    ),
        array('name'=>'week','amount'    =>    60*60*24*7    ),
        array('name'=>'day','amount'    =>    60*60*24    ),
        array('name'=>'hour','amount'    =>    60*60        ),
        array('name'=>'minute','amount'    =>    60        ),
        array('name'=>'second','amount'    =>    1        )
        );
   
    if (is_null($date2)){
    	$date2 = time();
    }
    $diff = abs($date1-$date2);
       if ($diff <= 60) { // if this is under a minute
		if ($diff <= 1) {
			return '1 second ago';
		}
		else
    	return $diff.' seconds ago';
    }
    
    $current_level = 1;
    $result = array();
    foreach($blocks as $block)
        {
        if ($current_level > $levels) {break;}
        if ($diff/$block['amount'] >= 1)
            {
            $amount = floor($diff/$block['amount']);
            if ($amount>1) {$plural='s';} else {$plural='';}
            $result[] = $amount.' '.$block['name'].$plural;
            $diff -= $amount*$block['amount'];
            $current_level++;
            }
        }
    return implode(' ',$result).' ago';
    } 
	
	public function TimeSince($original) // $original should be the original date and time in Unix format
{
    //**** use other function ************
    return $this->compare_dates($original);
	// ************************************
	
	// Common time periods as an array of arrays
    $periods = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
    );
  
    $today = time();
    $since = $today - $original; // Find the difference of time between now and the past
    if ($since <= 60) { // if this is under a minute
		if ($since <= 1) {
			return 'just moments';
		}
		else
    	return $since.' seconds';
    }
    
    // Loop around the periods, starting with the biggest
    for ($i = 0, $j = count($periods); $i < $j; $i++)
        {    
        $seconds = $periods[$i][0];
        $name = $periods[$i][1];
       
        // Find the biggest whole period
        if (($count = floor($since / $seconds)) != 0)
                {
            break;
        }
    }

    $output = ($count == 1) ? '1 '.$name : "$count {$name}s";
   
    if ($i + 1 < $j)
        {
        // Retrieving the second relevant period
        $seconds2 = $periods[$i + 1][0];
        $name2 = $periods[$i + 1][1];
       
        // Only show it if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0)
                {
            $output .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
    }

    return $output;

}

	
	public function curPageURL() 
{
	 $pageURL = 'http';
	if (isset($_SERVER["HTTPS"]))
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

	public function XssCleaner($string, $length = NULL)
	{
		// Remove dead space
		$string = trim($string);
		
		// Prevent potential Unicode codec problems
		$string = utf8_decode($string);
		
		//Htmlize HTML-specific characters
		$string = htmlentities($string, ENT_NOQUOTES);
		$string = str_replace("#", "&#35;", $string);
		$string = str_replace("%", "&#37;", $string);
		
		$length = intval($length);
		if ($length > 0) {
			$string = substr($string,0, $length);
		}
		 
		return $string;
	}

	public function markBlabReadOnly($userID, $blabID, $check = 1) {
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$data = array(
             'read_only'      => $check
             );
         
        $update = $db->update('blabs', $data, 'id = '.$blabID.' AND user_id = '.$userID);
		if ($update > 0)
		return true;
		else
		return FALSE;
	}
	
	
	public function isValidUrl($url) {
	$validator = new Zend_Validate_Callback(array('Zend_Uri', 'check'));
	if ($validator->isValid($url)) {
		return true;//Url appears to be valid
		} else {
		return false;//Url is not valid
		} 
		
		//return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);
	}

	public function getBlabId($title) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select();
		$select->from("blabs", array("id", "title", "user_id", "read_only"));
		$select->where("title = ?", $title);
		
		$result = $db->fetchRow($select);
		$this->isReadOnly = ($result['read_only'] == 0) ? false : true;
		$this->userID = $result['user_id'];
		return $result['id'];
	}
	
	public function getFrontPageLinks($page = 0, $offset = 0) {
		
		return null;
	}
	
        public function form_token($reset = false) {        		
        	// if session variable does not exist -> create a new unique token
     				if (!isset($_SESSION['csrfToken'])) {
						$_SESSION['csrfToken'] = sha1(time().'ntHGaxVr5zeOKmjvZFQleSQCs7DWtuh'.uniqid(mt_rand(),true));
						return $_SESSION['csrfToken'];
						}
						else if ($reset) {
							if (isset($_SESSION['csrfToken'])) {
								unset($_SESSION['csrfToken']);
								return $this->form_token();
							}
							else { 
							return $this->form_token();
							}
						}
						else
						{
							// else retrieve the previously generated token
							return $_SESSION['csrfToken'];
						}
					
        }
      public function kill_token() {
        		// if session variable does not exist -> create a new unique token
     				if (isset($_SESSION['csrfToken'])) {
						unset($_SESSION['csrfToken']);
						return true;
						}
						else
						{
							
							return false;
						}
        }
        
      protected function _sign($number) {
      	if ($number > 0) {
      		return 1;
        }
        else if ($number < 0)
        	return -1;
        else {
        	return 0;
        }
      }
      
      protected function _epoch_seconds ($date) {
      	$epoch = mktime(0, 0, 0, 1, 1, 1970);
      	$td = $date - $epoch;
      	return $td;
      	
      }

      public function _hot($ups, $downs, $date) {
      	 $s = $ups - $downs;
      	 $order = log(max(abs($s), 1), 10);
      	 $sign = $this->_sign($s);
      	 $seconds = $this->_epoch_seconds($date) - 1134028003;
      	 return round($order + $sign * $seconds / 45000, 7);
     }
     
     public function _controversy($upvotes, $downvotes) {
     	return ((float)($upvotes + $downvotes)) / (abs($upvotes - $downvotes) + 1);
     }
        
      public function vote($userID, $linkID, $vote) {
      	$db = Zend_Db_Table::getDefaultAdapter();
        // Get current number of upvotes / downvotes
      	$select = $db->select();
		$select->from("links", array("id","up_votes", "down_votes", "votes"));
		$select->where("id = ?", $linkID);
		$select->limit(1);
      	$result = $db->fetchRow($select);
      	if (empty($result)) {
      		return false; // no link exists with that id
      	}
      	
      	$upvotes = $result['up_votes'];
      	$downvotes = $result['down_votes'];
		$newVote = $result['votes'];
      	
      	if ($vote == true) {
      		$newVote++; $upvotes++; //$downvotes--;
      	}
      	else {
      		$newVote--; $downvotes++; //$upvotes--;
      	}
      	
      	$newHot = $this->_hot($upvotes, $downvotes, time());
      	      	
      	$newControversy = $this->_controversy($upvotes, $downvotes);	
      	
      	// update the link
				$data = array(
           		 'up_votes'      => $upvotes,
				 'down_votes'      => $downvotes,
				 'votes'      => $newVote,
				 'controversy'      => $newControversy,
				 'hot'      => $newHot
             	);
         $update = $db->update('links', $data, 'id = '.$linkID);
      	 if ($update) {
      	 	return true;
      	 }
      	 else 
      	 	return false;
      }
        
      public function submitVote($userID, $linkID, $type) {
      	
      	$db = Zend_Db_Table::getDefaultAdapter();
      	
      	$voteType = ($type == 'upVote') ? true : false;
      	
      	// Check if user has already voted on this link
      	$select = $db->select();
		$select->from("link_history");
		$select->where("user_id = ?", $userID);
		$select->where("link_id = ?", $linkID);
		$select->limit(1);
		
		$result = $db->fetchRow($select);
		
		if(!empty($result)) {
			// This link was already voted on by this user, see if we need to update their vote
			if ($result['vote_up'] == $voteType) {
 	 			// do not update; user is trying to use the same vote twice
				return array(
					"success" => false,
					"message" => "You can not vote twice"
				);
			}
			else {				
				// update
				$data = array(
           		 'vote_up'      => ($voteType == true) ? 1 : 0
             	);
             	 $update = $db->update('link_history', $data, 'id = '.$result['id']);
        		 $results = $this->vote($userID, $linkID, $voteType);
        		 return array(
					"success" => true,
					"message" => "vote success"
				);
			}
		}
		else {
			// This is a new vote

			$data = array(
	        'vote_up'      => ($voteType == true) ? 1 : 0,
	        'link_id' =>  $linkID,
	        'user_id' => $userID,
			'date_submitted' => new Zend_Db_Expr('NOW()')
	        );
	        $db->insert('link_history',$data);
			
	        $results = $this->vote($userID, $linkID, $voteType);
			 
	        return array(
					"success" => true,
					"message" => "New vote submited"
				);
		}
      }
      
    public function confidence ($ups, $downs) {
    	$n = $ups + $downs;
    	
    	if ($n == 0) { return 0;  }
    	
    	$z = 1.0;
    	$phat = (float)($ups) / $n;
    	return sqrt($phat+$z*$z/(2*$n) - $z * (($phat *(1-$phat)+$z*$z/(4*$n))/$n)) / (1 + $z * $z/$n);
    }
    
     // This function will change new lines (line breaks) 
    // to <br/> and it allows you to limit the amount of brs allowed at any point in time.
	public function nl2br_limit($string, $num = 10){
		$dirty = preg_replace('/\r/', '', $string);
		$clean = preg_replace('/\n{4,}/', str_repeat('<br/>', $num), preg_replace('/\r/', '', $dirty));
		   
		return nl2br($clean);
	}
	
	public function docodaOutput ($input, $allowedTags = NULL, $purify = true) {
				if (is_null($allowedTags)) {
					$allowedTags = preg_split("/[\s,]+/", DECODAPOST);
				}
												
				$code = new Decoda($input, $allowedTags);
				$options = array(
					"clickable" => false,
					"censor" => false,
					"jquery" => true
				);
				$code->configure($options);
				if ($purify) {
				$purifier = new HTMLPurifier();
    			$clean_html = $purifier->purify($code->parse(true));
				}
				else {
					$clean_html = $code->parse(true);
				}
				
				return $clean_html;		
	}
	
public function urlsafe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return $data;
}

public function urlsafe_b64decode($string) {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}
	

	
}