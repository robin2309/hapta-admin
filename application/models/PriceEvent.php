<?php

class Application_Model_PriceEvent
{
	protected $_idEvent;
	protected $_idType;
	protected $_price;
	protected $_link;

	public function __construct(array $options = null)
    {
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
    public function getIdEvent(){
	    return $this->_idEvent;
    }
    
    public function getIdType(){
	    return $this->_idType;
    }
    
    public function getPrice({
	    return $this->_price;
    }
    
    public function getLink(){
	    return $this->_link;
    }
    
    //SETTERS
    public function setIdEvent($idEvent){
	    $this->_id = $idEvent;
	    return $this;
    }
    
    public function setIdType($idType){
	    $this->_name = $idType;
	    return $this;
    }
    
    public function setPrice($price){
	    $this->_price = $price;
	    return $this;
    }
    
    public function setLink($link){
	    $this->_link = $link;
	    return $this;
    }

}

