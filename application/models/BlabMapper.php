<?php

class Application_Model_BlabMapper
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
            $this->setDbTable('Application_Model_DbTable_Blab');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Blab $blab)
    {
        $data = array(
            'user_id'   => $blab->getUserID(),
            'title' => $blab->getTitle(),
        	'head_title' => $blab->getHeadTitle(),
        	'description' => $blab->getDescription(),
            'date_created' => date('Y-m-d H:i:s')
        );

 
        if (null === ($id = $blab->getId())) {
            unset($data['id']);
            $this->lastInsertID = $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }


    public function find($id, Application_Model_Blab $blab)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $blab->setId($row->id)
                  ->setUserID($row->user_id)
                  ->setTitle($row->title)
                  ->setHeadTitle($row->head_title)
                  ->setDescription($row->description)
                  ->setDateCreated($row->date_created);
    }

    /*
     *  Fetch all Blabs from a specified user_id: default is ID 6 for the founding Linkblab member
     *  customize for pagination if desired: specify a row start && items per page
     */
    public function fetchAll($start_number = 0, $sortBy = null, $items_per_page = null, $user_id = null )
    {
    	$resultSet = null;
    	if (!is_null($items_per_page) || $start_number != 0)
        $resultSet = $this->getDbTable()->fetchAll("user_id = ".$user_id, NULL, $start_number, $items_per_page);
        elseif (!is_null($sortBy))
        {
        	if ($sortBy != 'frontpage') 
        	{
        		$resultSet = $this->getDbTable()->fetchAll(null, array($sortBy));
        	}
        	else 
        	{
        		// sort by frontpage
        		
        		// FIRST SELECT ALL BLABS THIS USER IS ALREADY SUBSCRIBED TO
        		$db = Zend_Db_Table::getDefaultAdapter();
				$select = $db->select();
				$select->from( array('b' => 'blabs'),
						array('id', "title","user_id" , "head_title", "description", "date_created"));
				$select->join(array('s' => 'subscriptions'),
                    'b.id = s.blab_id', array("user_id", "display_order"));
				$select->where("s.user_id = ?", $user_id);
				$select->order(array("b.title ASC"));
				$resultSet = $db->fetchAssoc($select);
				
				//SECOND SELECT ALL BLABS THE USER IS NOT SUBSCRIBED TO				
				$select = $db->select()->from(
    			array(
        			'b' =>  'blabs'
    			)
				)->where('b.id NOT IN (SELECT blab_id FROM subscriptions WHERE user_id = '.$user_id.')')->where('b.title != \'random\'');
				//echo $select;	
				$results = $db->fetchAssoc($select);
				
				//THIRD COMBINE THESE TWO RESULTS
				$resultSet = array_merge($resultSet, $results);
				//print_r($resultSet);
        	}
        	
        }
        else
        {
        	$resultSet = $this->getDbTable()->fetchAll(null, array('title ASC'));
        }
        $entries   = array();
        if ($sortBy != 'frontpage') {
            foreach ($resultSet as $row) {
	            $entry = new Application_Model_Blab();
	            $entry->setId($row->id)
	                  ->setUserID($row->user_id)
	                  ->setTitle($row->title)
	                  ->setHeadTitle($row->head_title)
	                  ->setDescription($row->description)
	                  ->setDateCreated($row->date_created);
	            $entries[] = $entry;
        	}
        }
        else {
            foreach ($resultSet as $key => $row) {
	            $entry = new Application_Model_Blab();
	            $entry->setId($row["id"])
	                  ->setUserID($row["user_id"])
	                  ->setTitle($row["title"])
	                  ->setHeadTitle($row["head_title"])
	                  ->setDescription($row["description"])
	                  ->setDateCreated($row["date_created"]);
	            $entries[] = $entry;
       	 	}
        }

        return $entries;
    }
    
}
