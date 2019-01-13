<?php

class Application_Model_DbTable_Spot extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_club';

    private function prepareResultSet($result){
	   	$entries   = array();
        foreach ($result as $row) {
            $spot = new Application_Model_Spot($row);
            $entries[] = $spot;
        }
        return $entries;
    }
    
    
    public function getAllSpots(){
		$select = $this->select();
		$select->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function getAllValidSpots(){
		 $select = $this->select();
		$select->where('valide = 1 AND (changeRequest IS NULL OR changeRequest = "")')
				->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function countAllValidSpots(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbSpots FROM hapta_club WHERE valide = 1 AND (changeRequest IS NULL OR changeRequest = '')";
	    return $db->fetchAll($sql)[0]['nbSpots'];
    }
    
    public function countInvalidSpots(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbSpots FROM hapta_club WHERE valide = 0";
	    return $db->fetchAll($sql)[0]['nbSpots'];
    }
    
    public function getLastAddedSpots(){
	    $db = $this->getAdapter();
	    $sql = "SELECT id, name, city, address FROM hapta_club WHERE valide = 1 AND (changeRequest IS NULL OR changeRequest = '') order by id DESC limit 10";
	    return $this->prepareResultSet($db->query($sql));
    }
    
    public function cacheAllValidSpots(){
	    $db = $this->getAdapter();
	    $sql = "SELECT id, name FROM hapta_club WHERE valide = 1 ORDER BY name ASC";
	    return $db->fetchAll($sql);  
    }
    
    public function getAllModifRequestSpots(){
		 $select = $this->select();
		$select->where('valide = 1 AND changeRequest IS NOT NULL AND changeRequest != ""')
				->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function getAllInvalidSpots(){
		 $select = $this->select();
		$select->where('valide = 0')
				->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }

    
    public function getSpotsOption(){
	    $db = $this->getAdapter();
	    $sql = "SELECT id,name,city FROM hapta_club where valide = 1 order by name";
	    return $db->fetchAll($sql);
    }
    
    
    public function getSpot($id){
	    $row = $this->fetchRow('id = ' . (int)$id);
	    if(!$row){
		    throw new Exception("Spot non trouvé : " . $id);
	    }
	    return $row->toArray();
    }
    
    
    public function updateSpot($id, $data){
	    try{
	    	$this->update($data, 'id = '.(int)$id);
	    } catch(Zend_Db_Statement_Exception $e){
	    	if ($e->getCode() == 23000) {
	    		return -1;
	    	} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	}
		}	
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'spots';
		
		$spots = $this->cacheAllValidSpots();
		$spotsJson = json_encode($spots);
		$cache->save($spotsJson, $cacheId);
    }
    
    
    public function addSpot($data){
	    try{
	    	$this->insert($data);
	    } catch(Zend_Db_Statement_Exception $e){
	    	if ($e->getCode() == 23000) {
	    		return -1;
	    	} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	}
		}
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'spots';
		
		$spots = $this->cacheAllValidSpots();
		$spotsJson = json_encode($spots);
		$cache->save($spotsJson, $cacheId);
    }
    
    
    public function deleteSpot($id){
	    $this->delete('id =' . (int)$id);
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'spots';
		
		$spots = $this->cacheAllValidSpots();
		$spotsJson = json_encode($spots);
		$cache->save($spotsJson, $cacheId);
    }
    
    public function getSpotIdFromName($spotName){
    	$db = $this->getAdapter();
    	$sql = "SELECT id FROM hapta_club WHERE name = :nameSpot";
	    return $db->fetchAll($sql, array(':nameSpot' => $spotName))[0]['id'];
    }
    
    public function getNameFromId($id){
	    $row = $this->fetchRow('id = ' . (int)$id);
	    if(!$row){
		    throw new Exception("Spot non trouvé : " . $id);
	    }
	    return $row->toArray()['name'];
    }
    
    
}

