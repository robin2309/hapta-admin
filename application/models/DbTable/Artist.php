<?php

class Application_Model_DbTable_Artist extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_artist';
    protected $_primary = 'idArtist';
    
    
    private function prepareResultSet($result){
	   	$entries   = array();
        foreach ($result as $row) {
        	$row['id'] = $row['idArtist'];
            $artist = new Application_Model_Artist($row);
            $entries[] = $artist;
        }
        return $entries;
    }
   
    public function getAllArtists(){
	    $result = $this->fetchAll()->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function getAllValidArtists(){
	    $select = $this->select();
		$select->where('valide = 1 AND (changeRequest IS NULL OR changeRequest = \'\')')
				->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function countAllValidArtists(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbArtists FROM hapta_artist WHERE valide = 1 AND (changeRequest IS NULL OR changeRequest = '')";
	    return $db->fetchAll($sql)[0]['nbArtists'];
    }
    
    public function getLastAddedArtists(){
	    $db = $this->getAdapter();
	    $sql = "SELECT idArtist, name, label, country, city FROM hapta_artist WHERE valide = 1 AND (changeRequest IS NULL OR changeRequest = '') order by idArtist DESC limit 10";
	    return $this->prepareResultSet($db->query($sql));
    }
    
    public function getAllModifRequestArtists(){
	    $select = $this->select();
		$select->where('valide = 1 AND changeRequest IS NOT NULL AND changeRequest != \'\'')
				->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function cacheAllValidArtists(){
	    $db = $this->getAdapter();
	    $sql = "SELECT idArtist, name FROM hapta_artist WHERE valide = 1 ORDER BY name ASC";
	    return $db->fetchAll($sql);  
    }
    
    public function getAllInvalidArtists(){
	    $select = $this->select();
		$select->where('valide = 0')
				->order('name ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function countInvalidArtists(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbArtists FROM hapta_artist WHERE valide = 0";
	    return $db->fetchAll($sql)[0]['nbArtists'];
    }
    
    public function getArtistsOption(){
	    $db = $this->getAdapter();
	    $sql = "SELECT * FROM hapta_artist order by name";
	    return $db->fetchAll($sql);
    }
    
    public function addArtist($artist){
	    try{
	    	$idInsert = $this->insert($artist);
	    } catch(Zend_Db_Statement_Exception $e){
	    	if ($e->getCode() == 23000) {
	    		return -1;
	    	} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	}
		}
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'artists';
		
		$artists = $this->cacheAllValidArtists();
		$artistsJson = json_encode($artists);
		$cache->save($artistsJson, $cacheId);

    }
    
    public function getArtist($id){
	    $row = $this->fetchRow('idArtist = ' . (int)$id);
	    if(!$row){
		    throw new Exception("Artiste non trouvé : " . $id);
	    }
	    return $row->toArray();
    }
    
    public function updateArtist($id, $data){
	    try{
	    	$this->update($data, 'idArtist = '.(int)$id);
	    } catch(Zend_Db_Statement_Exception $e){
	    	if ($e->getCode() == 23000) {
	    		return -1;
	    	} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	}
		}
	    
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'artists';
		
		$artists = $this->cacheAllValidArtists();
		$artistsJson = json_encode($artists);
		$cache->save($artistsJson, $cacheId);
    }
    
    public function deleteArtist($id){
	    $this->delete('idArtist =' . (int)$id);
	    
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'artists';
		
		$artists = $this->cacheAllValidArtists();
		$artistsJson = json_encode($artists);
		$cache->save($artistsJson, $cacheId);
		
    }



}

