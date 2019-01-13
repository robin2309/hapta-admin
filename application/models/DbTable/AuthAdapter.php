<?php

class Application_Model_DbTable_AuthAdapter extends Zend_Db_Table_Abstract implements Zend_Auth_Adapter_Interface
{

    protected $_name = 'hapta_user';
    protected $_primary = 'idUser';
    
    // array containing authenticated user record
    protected $_resultArray;
    
    // constructor
    // accepts username and password    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    
    // main authentication method
    // queries database for match to authentication credentials
    // returns Zend_Auth_Result with success/failure code
    public function authenticate()
    {
    
        $query = $this->select($this)
              ->from('hapta_user u')
              ->where('u.login = ? AND u.pass = PASSWORD(?)', array($this->username, $this->password));
        $result = $this->fetchRow($query);
        /*$db = $this->getAdapter();
        //print_r($db);
        $sql = "SELECT * FROM hapta_user u WHERE u.login = :login AND u.pass = PASSWORD(:pass)";
        $params = array(':login'=>$this->username, ':pass'=>$this->password);
	    $result = $db->query($sql, $params);   
        */
        if (count($result) == 1) {
            $this->_resultArray = $result[0];
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->username, array());
        } else {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array('Authentication unsuccessful'));      
        }
    }
    
    // returns result array representing authenticated user record
    // excludes specified user record fields as needed
    public function getResultArray($excludeFields = null)
    {
        if (!$this->_resultArray) {
            return false;
        } 
        
        if ($excludeFields != null) {
            $excludeFields = (array)$excludeFields;
            foreach ($this->_resultArray as $key => $value)
            {
                if (!in_array($key, $excludeFields)) {  
                  $returnArray[$key] = $value;  
                }
            }
            return $returnArray;      
        } else {
            return $this->_resultArray;        
        }      
    }

}

