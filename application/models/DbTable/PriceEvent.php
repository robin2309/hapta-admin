<?php

class Application_Model_DbTable_PriceEvent extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_event_price';
    protected $_primary = array('idEvent','idType');
    
    public function getPriceForEvent($idEvent){
	    $select = $this->select()
	    	->from($this->_name)
	    	->where('idEvent = ?', $idEvent);
	    return $this->fetchAll($select);
    }
    
    public function updatePriceEvent($data){
	    $this->update();
    }
    
    public function addLine($data){
	    $this->insert($data);
    }
    
    public function getPrices($idEvent){
	    $select = $this->select()
	    			   ->from($this->_name)
	    			   ->where('idEvent = ?', $idEvent);
        return $this->fetchAll($select)->toArray();
    }
    
    public function deletePrices($idEvent){
	    $this->delete('idEvent ='. $idEvent);
    }
    
    public function addEventPrice($idEvent, $prices){
	    foreach($prices as $price){
	    	try{
		    	$this->addLine(array('idEvent'=> $idEvent, 'idType'=> $price['idType'], 'price'=> $price['price'], 'link'=> $price['link']));
	    	} catch(Zend_Db_Statement_Exception $e){
		    	if ($e->getCode() == 23000) {
		    		
		    	} else {
			    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		    	}
	    	}
		    
	    }
    }
    
    public function updateEventPrice($idEvent, $prices){
    	$this->deletePrices($idEvent);
    	$this->addEventPrice($idEvent, $prices);
    }
    
}

