<?php

class Application_Model_Blab
{
	protected $_userID;
	protected $_title;
	protected $_headTitle;
	protected $_description;
	protected $_dateCreated;
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
			throw new Exception('Invalid Blab property');
		}
		$this->$method($value);
	}
	
	public function __get($name)
	{
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Blab property');
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
	
	public function setUserID($userID) {
      $this->_userID = (int) $userID;
      return $this;
	}
	
	public function setTitle($title) {
      $this->_title = $title;
      return $this;
	}
	
	public function setHeadTitle($title) {
      $this->_headTitle = $title;
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
	
	public function getUserID()
	{
		return $this->_userID;
	}
	
	public function getTitle()
	{
		return strtolower($this->_title); // Always return in lowercase for consistancy 
	}
	
	public function getDescription()
	{
		return $this->_description;
	}
	
	public function getHeadTitle()
	{
		return $this->_headTitle;
	}
		
	public function getDateCreated()
	{
		return $this->_dateCreated;
	}
	
    public function getId()
    {
        return $this->_id;
    }
    

}
