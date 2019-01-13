<?php

class Application_Model_DbTable_Genre extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_genre';
    protected $_primary = 'idGenre';

    private function prepareResultSet($result){
	   	$entries   = array();
        foreach ($result as $row) {
            $genre = new Application_Model_Genre(array('id'=>$row['idGenre'],'name'=>$row['nameGenre'], 'valide'=>$row['valide'], 'changeRequest'=>$row['changeRequest']));
            $entries[] = $genre;
        }
        return $entries;
        
    }
    
    public function getAllGenres(){
	    $result = $this->fetchAll()->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function getLastAddedGenres(){
	    $db = $this->getAdapter();
	    $sql = "SELECT idGenre, nameGenre, valide, changeRequest FROM hapta_genre WHERE valide = 1 AND changeRequest IS NULL OR changeRequest = '' order by idGenre DESC limit 10";
	    return $this->prepareResultSet($db->query($sql));
    }
    
    public function cacheAllValidGenres(){
		$db = $this->getAdapter();
		$sql = "SELECT idGenre, nameGenre FROM `hapta_genre` WHERE valide=1 ORDER BY nameGenre ASC";
	    return $db->fetchAll($sql);
    }
    
    public function getAllValidGenres(){
    	$select = $this->select()
    					->from($this->_name)
    					->where('valide = 1 AND (changeRequest IS NULL OR changeRequest = "")')
    					->order('nameGenre ASC');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function getAllModifRequestGenres(){
    	$select = $this->select()
    					->from($this->_name)
    					->where('valide = 1 AND changeRequest IS NOT NULL AND changeRequest != ""');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function getAllInvalidGenres(){
    	$select = $this->select()
    					->from($this->_name)
    					->where('valide = 0');
	    $result = $this->fetchAll($select)->toArray();
	    return $this->prepareResultSet($result);
    }
    
    public function countInvalidGenres(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbGenres FROM hapta_genre WHERE valide = 0";
	    return $db->fetchAll($sql)[0]['nbGenres'];
    }
    
    public function getGenresOption(){
	    $db = $this->getAdapter();
	    $sql = "SELECT idGenre,nameGenre FROM hapta_genre order by nameGenre";
	    return $db->fetchAll($sql);
    }    
    
    public function addGenre($data){
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
		$cacheId = 'genres';
		
		$genres = $this->cacheAllValidGenres();
		$genresJson = json_encode($genres);
		$cache->save($genresJson, $cacheId);
    }
    
    public function getGenre($id){
	    $row = $this->fetchRow('idGenre = ' . (int)$id);
	    if(!$row){
		    throw new Exception("Genre non trouvé : " . $id);
	    }
	    return $row->toArray();
    }
    
    
    public function updateGenre($id, $data){
	    try{
	    	$this->update($data, 'idGenre = '.(int)$id);
	    } catch(Zend_Db_Statement_Exception $e){
	    	if ($e->getCode() == 23000) {
	    		return -1;
	    	} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	}
		}
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'genres';
		
		$genres = $this->cacheAllValidGenres();
		$genresJson = json_encode($genres);
		$cache->save($genresJson, $cacheId);
    }
    
    public function deleteGenre($id){
	    $this->delete('idGenre =' . (int)$id);
	    $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'genres';
		
		$genres = $this->cacheAllValidGenres();
		$genresJson = json_encode($genres);
		$cache->save($genresJson, $cacheId);
    }

}

