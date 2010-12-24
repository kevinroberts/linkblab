<?php

class Application_Model_Link
{
	protected $_linkurl;
	protected $_domain;
	protected $_thumbnail;
	protected $_upvotes;
	protected $_downvotes;
	protected $_votes;
	protected $_title;
	protected $_description;
	protected $_dateCreated;
	protected $_blabID;
	protected $_userID;
	protected $_isNsfw;
	protected $_isSelf;
	protected $_timesReported;
	protected $_controversy;
	protected $_hot;
	protected $_id;
	
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid Link property');
		}
		$this->$method($value);
	}
	
	public function __get($name)
	{
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Link property');
        }
        return $this->$method();
	}
	
	public function setOptions(array $options)
	{	
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
	}
	

	
	
	/*
	 * Property Retrieving (Get) Functions
	 */
	
	/**
	 * @return the $_linkurl
	 */
	public function getLinkUrl() {
		return $this->_linkurl;
	}

	/**
	 * @return the $_domain
	 */
	public function getDomain() {
		return $this->_domain;
	}

	/**
	 * @return the $_upvotes
	 */
	public function getUpVotes() {
		return $this->_upvotes;
	}

	/**
	 * @return the $_downvotes
	 */
	public function getDownVotes() {
		return $this->_downvotes;
	}

	/**
	 * @return the $_dateCreated
	 */
	public function getDateCreated() {
		return $this->_dateCreated;
	}

	/**
	 * @return the $_blabID
	 */
	public function getBlabID() {
		return $this->_blabID;
	}

	/**
	 * @return the $_userID
	 */
	public function getUserID() {
		return $this->_userID;
	}

	/**
	 * @return the $_isNsfw
	 */
	public function getIsNsfw() {
		return $this->_isNsfw;
	}

	/**
	 * @return the $_isSelf
	 */
	public function getIsSelf() {
		return $this->_isSelf;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	public function getDescription()
	{
		return $this->_description;
	}
	
	/*
	 * Property Setting Functions
	 */
	
	public function setUserID($userID) {
      $this->_userID = (int) $userID;
      return $this;
	}
	
	public function setTitle($title) {
      $this->_title = $title;
      return $this;
	}
		
	/**
	 * @param field_type $_linkurl
	 */
	public function setLinkUrl($_linkurl) {
		$this->_linkurl = $_linkurl;
		return $this;
	}

	/**
	 * @param domain $_domain
	 */
	public function setDomain($_domain) {
		$this->_domain = $_domain;
		return $this;
	}

	/**
	 * @param field_type $_upvotes
	 */
	public function setUpVotes($_upvotes) {
		$this->_upvotes = $_upvotes;
		return $this;
	}

	/**
	 * @param field_type $_downvotes
	 */
	public function setDownVotes($_downvotes) {
		$this->_downvotes = $_downvotes;
		return $this;
	}

	/**
	 * @param field_type $_blabID
	 */
	public function setBlabID($_blabID) {
		$this->_blabID = $_blabID;
		return $this;
	}

	/**
	 * @param field_type $_isNsfw
	 */
	public function setIsNsfw($_isNsfw) {
		$this->_isNsfw = $_isNsfw;
		return $this;
	}

	/**
	 * @param field_type $_isSelf
	 */
	public function setIsSelf($_isSelf) {
		$this->_isSelf = $_isSelf;
		return $this;
	}

	public function setDescription($description) {
      $this->_description = $description;
      return $this;
	}
	
	public function setDateCreated($created) {
      $this->_dateCreated = $created;
      return $this;
	}
	
	public function setId($id) {
      $this->_id = (int) $id;
      return $this;
	}
	/**
	 * @return the $_thumbnail
	 */
	public function getThumbnail() {
		return $this->_thumbnail;
	}

	/**
	 * @return the $_timesReported
	 */
	public function getTimesReported() {
		return $this->_timesReported;
	}

	/**
	 * @param field_type $_thumbnail
	 */
	public function setThumbnail($_thumbnail) {
		$this->_thumbnail = $_thumbnail;
		return $this;
	}

	/**
	 * @param field_type $_timesReported
	 */
	public function setTimesReported($_timesReported) {
		$this->_timesReported = $_timesReported;
		return $this;
	}
	
	/**
	 * @param field_type $_votes
	 */
	public function setVotes($_votes) {
		$this->_votes = $_votes;
		return $this;
	}
	
	/**
	 * @return the $_votes
	 */
	public function getVotes() {
		return $this->_votes;
	}
	/**
	 * @return the $_controversy
	 */
	public function getControversy() {
		return $this->_controversy;
	}

	/**
	 * @param field_type $_controversy
	 */
	public function setControversy($_controversy) {
		$this->_controversy = $_controversy;
		return $this;
	}
	
	public function getHot() {
		return $this->_hot;
	}
	
	public function setHot($_hot) {
		$this->_hot = $_hot;
		return $this;
	}

	
}

