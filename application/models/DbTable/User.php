<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_user';
    
    public function getUsersOption(){
	    $db = $this->getAdapter();
	    $sql = 'SELECT id,username from hapta_user where username != "admin"';
	    return $db->fetchAll($sql);
    }
    
    public function cacheAllUsers(){
	    $db = $this->getAdapter();
	    $sql = "SELECT id, username as name FROM hapta_user WHERE role = 'user' ORDER BY username ASC";
	    return $db->fetchAll($sql);
    }
    
    public function getUsers(){
    	$select = $this->select()
    				   ->from($this->_name);
	    return $this->fetchAll($select)->toArray();
    }

    public function insertUser($data){
	    $this->insert($data);
    }
    
    public function getUser($id){
	    $row = $this->fetchRow('id = ' . (int)$id);
	    if(!$row){
		    throw new Exception("Utilisateur non trouvé : " . $id);
	    }
	    return $row->toArray();
    }
    
    public function editUser($id, $data){
	    $this->update($data, 'id = '.(int)$id);
    }
    
    public function deleteUser($id){
	    $this->delete('id =' . (int)$id);
    }

}

