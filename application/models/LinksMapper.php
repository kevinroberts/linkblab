<?php

class Application_Model_LinksMapper
{
  protected $_dbTable;
  public $lastInsertID;
  
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
    
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Links');
        }
        return $this->_dbTable;
    }
    
    public function save(Application_Model_Link $link)
    {
        $data = array(
            'link_url'   => $link->getLinkUrl(),
            'domain' => $link->getDomain(),
        	'thumbnail' => $link->getThumbnail(),
        	'up_votes' => $link->getUpVotes(),
        	'down_votes' => $link->getDownVotes(),
        	'votes' => $link->getVotes(),
        	'controversy' => $link->getControversy(),
        	'title' => $link->getTitle(),
        	'description' => $link->getDescription(),            
            'date_created' => $link->getDateCreated(),
        	'blab_id' => $link->getBlabID(),
        	'user_id' => $link->getUserID(),
        	'is_nsfw' => $link->getIsNsfw(), // is this link considered NSFW?
        	'is_self' => $link->getIsSelf(), // is this a self-post?
        	'hot' => $link->getHot(),
        	'times_reported' => $link->getTimesReported(),
        	'url_safe_title' => $link->getUrlTitle()
        );

 
        if (null === ($id = $link->getId())) {
            unset($data['id']);
            $this->lastInsertID = $this->getDbTable()->insert($data);
            // Add the users first vote to their link history
            $db = Zend_Db_Table::getDefaultAdapter();
            $data = array(
			'vote_up'      => 1,
        	'link_id' =>  $this->lastInsertID,
			'user_id' => $data['user_id'],
            'date_submitted' => new Zend_Db_Expr('NOW()')
        	);
        	$inserter = $db->insert("link_history", $data);
            
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    public function find($id, Application_Model_Link $link)
    {
    	$utils = new Application_Model_Utils();
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $link->setId($row->id)
                  ->setLinkUrl($row->link_url)
                  ->setDomain($row->domain)
                  ->setThumbnail($row->thumbnail)
                  ->setUpVotes($row->up_votes)
                  ->setDownVotes($row->down_votes)
                  ->setVotes($row->votes)
                  ->setControversy($row->controversy)
                  ->setTitle($row->title)
                  ->setDescription($row->description)
                  ->setDateCreated($row->date_created)
                  ->setBlabID($row->blab_id)
                  ->setUserID($row->user_id)
                  ->setIsNsfw($row->is_nsfw)
                  ->setIsSelf($row->is_self)
                  ->setHot($row->hot)
                  ->setUrlTitle($row->url_safe_title)
                  ->setTimesReported($row->times_reported);
    }
    
    public function findOne($id)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $link = new Application_Model_Link();
        $link->setId($row->id)
                  ->setLinkUrl($row->link_url)
                  ->setDomain($row->domain)
                  ->setThumbnail($row->thumbnail)
                  ->setUpVotes($row->up_votes)
                  ->setDownVotes($row->down_votes)
                  ->setVotes($row->votes)
                  ->setControversy($row->controversy)
                  ->setTitle($row->title)
                  ->setDescription($row->description)
                  ->setDateCreated($row->date_created)
                  ->setBlabID($row->blab_id)
                  ->setUserID($row->user_id)
                  ->setIsNsfw($row->is_nsfw)
                  ->setIsSelf($row->is_self)
                  ->setHot($row->hot)
                  ->setUrlTitle($row->url_safe_title)
                  ->setTimesReported($row->times_reported);
                  
        return $link;
    }
    
    public function fetchAll($limit = null, $blabID = null, $domain = null, $orderBy = null, $where = null, $dayAgo = 180) {
    	$defaultOrder = (is_null($orderBy)) ? array('hot DESC', 'date_created DESC') : array($orderBy, 'date_created DESC'); // set up user specified order criteria
    	$where = (is_null($where)) ? "DATE_SUB(CURDATE(),INTERVAL 180 DAY) <= date_created" : $where; // customize where condition or use default link < 180 days
    	if (!is_null($limit) && is_null($blabID) && is_null($domain)) { // all links with limit
    		$resultSet = $this->getDbTable()->fetchAll($where, $defaultOrder, $limit, null);
    	}
    	else if (!is_null($limit) && !is_null($blabID) && is_null($domain)) { // all links for specific blab with limit
    		$resultSet = $this->getDbTable()->fetchAll("blab_id = ".$blabID." AND ".$where, $defaultOrder, $limit, null);
    	}
    	else if (!is_null($limit) && is_null($blabID) && !is_null($domain)) { // all links for specific domain
    		$resultSet = $this->getDbTable()->fetchAll("domain = '".$domain."' AND ".$where, $defaultOrder, $limit, null);
    	}
    	else {
    	$resultSet = $this->getDbTable()->fetchAll();
    	}
    	$links   = array();
    	foreach ($resultSet as $row) {
    		 $link = new Application_Model_Link();
    		 $link->setId($row->id)
                  ->setLinkUrl($row->link_url)
                  ->setDomain($row->domain)
                  ->setThumbnail($row->thumbnail)
                  ->setUpVotes($row->up_votes)
                  ->setDownVotes($row->down_votes)
                  ->setControversy($row->controversy)
                  ->setVotes($row->votes)
                  ->setTitle($row->title)
                  ->setDescription($row->description)
                  ->setDateCreated($row->date_created)
                  ->setBlabID($row->blab_id)
                  ->setUserID($row->user_id)
                  ->setIsNsfw($row->is_nsfw)
                  ->setIsSelf($row->is_self)
                  ->setHot($row->hot)
                  ->setUrlTitle($row->url_safe_title)
                  ->setTimesReported($row->times_reported);
            $links[] = $link;
    		
    	}
    	return $links;
    	
    }

}

