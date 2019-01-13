<?php

class Application_Model_User
{

	protected $_id;
	protected $_username;
	protected $_password;
	protected $_role;
	protected $_email;
	protected $_dataTable;
	
	public function __construct(array $options = null)
    {
    	$this->_dataTable = new Application_Model_DbTable_User();
        if (is_array($options)) {
            $this->setOptions($options);
        }
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
	
	//GETTERS
	public function getId(){
		return $this->_id;
	}
	
	public function getUsername(){
		return $this->_username;
	}
	
	public function getPassword(){
		return $this->_password;
	}
	
	public function getRole(){
		return $this->_role;
	}
	
	public function getEmail(){
		return $this->_email;
	}
	
	
	//SETTERS
	public function setId($id){
		$this->_id = $id;
		return $this;
	}
	
	public function setUsername($name){
		$this->_username = $name;
		return $this;
	}
	
	public function setPassword($pass){
		$this->_password = $pass;
		return $this;
	}
	
	public function setRole($role){
		$this->_role = $role;
		return $this;
	}
	
	public function setEmail($email){
		$this->_email = $email;
		return $this;
	}
	
	
	private function prepareResultSet($result){
		$entries   = array();
		foreach ($result as $row) {
            $entry = new Application_Model_User($row);
            $entries[] = $entry;
        }
        return $entries;
	}
	
	public function getUser($id){
		return $this->_dataTable->getUser($id);
	}
	
	public function deleteUser($id){
		$this->_dataTable->deleteUser($id);
	}
	
	public function insertUser($data){
		$this->_dataTable->insertUser($data);
	}
	
	public function editUser($id, $data){
		$this->_dataTable->editUser($id, $data);
	}
	
	public function getAllUsers(){
		return $this->prepareResultSet($this->_dataTable->getUsers());
	}

}

