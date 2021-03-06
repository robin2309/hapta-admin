<?php

class Application_Model_Spot
{
	protected $_id;
	protected $_name;
	protected $_city;
	protected $_address;
	protected $_valide;
	protected $_changeRequest;
	protected $_dataTable;
	
	public function __construct(array $options = null)
    {	
    	$this->_dataTable = new Application_Model_DbTable_Spot();
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
    
    public function getName(){
	    return $this->_name;
    }
    
    public function getCity(){
	    return $this->_city;
    }
    
    public function getAddress(){
	    return $this->_address;
    }
    
    public function getValide(){
	    return $this->_valide;
    }
    
    public function getChangeRequest(){
	    return $this->_changeRequest;
    }
    
   
    //SETTERS
    public function setId($id){
	    $this->_id = $id;
	    return $this;
    }
    
	public function setName($name){
		$this->_name = $name;
		return $this;	
	}
    
    public function setCity($city){
	    $this->_city = $city;
	    return $this;
    }
    
    public function setAddress($address){
	    $this->_address = $address;
	    return $this;
    }
    
    public function setValide($valide){
	    $this->_valide = $valide;
	    return $this;
    }
    
    public function setChangeRequest($changeRequest){
	    $this->_changeRequest = $changeRequest;
	    return $this;
    }
    
    //DATA ACCESS
    
    public function getSpotIdFromName($spotName){
	    return $this->_dataTable->getSpotIdFromName($spotName);
    }
    
    public function getSpotNameFromId($id){
	    return $this->_dataTable->getNameFromId($id);
    }
    
    public function countInvalid(){
	    return $this->_dataTable->countInvalidSpots();
    }

}

