<?php

class Application_Model_DbTable_Concours extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_concours';
    protected $_primary = 'idConcours';
    
    public function getAllConcours(){
    	$db = $this->getAdapter();
	    $sql = "SELECT hc.idConcours, hc.dateDeb, hc.dateFin, hc.nbPlaces, hc.nbGagnants, he.name, hc.valide, hc.publie FROM hapta_concours hc, hapta_event he WHERE hc.idEvent = he.id";
	    $result = $db->fetchAll($sql);
	    $concours = array();
	    foreach($result as $attributes){
		    $concours[] = new Application_Model_Concours($attributes);
	    }
	    return $concours;
    }
    
    
    
    public function getAllConcoursFromUser($userId){
    	$db = $this->getAdapter();
	    $sql = "SELECT hc.idConcours, hc.dateDeb, hc.dateFin, hc.nbPlaces, hc.nbGagnants, he.name FROM hapta_concours hc, hapta_event he WHERE hc.idEvent = he.id AND he.poster = :userId AND hc.dateFin>=now()";
	    $result = $db->fetchAll($sql,array(':userId'=>$userId));
	    $concours = array();
	    foreach($result as $attributes){
		    $concours[] = new Application_Model_Concours($attributes);
	    }
	    return $concours;
    }
    
    
    public function getInvalidConcours(){
	    $db = $this->getAdapter();
	    $sql = "select c.idConcours, e.name, c.dateDeb, c.dateFin, c.nbGagnants, c.nbPlaces, c.publie, c.valide from hapta_concours c join hapta_event e on c.idEvent=e.id where c.valide = 0 and (dateDeb>=now() or dateFin>=now()) order by c.dateFin";
	    return $db->fetchAll($sql);
    }
    
    public function getUpcomingConcours(){
	    $db = $this->getAdapter();
	    $sql = "select c.idConcours, e.name, c.dateDeb, c.dateFin, c.nbGagnants, c.nbPlaces, c.publie, c.valide from hapta_concours c join hapta_event e on c.idEvent=e.id where c.valide = 1 and c.publie = 0 and dateFin>=now() order by c.dateFin";
	    return $db->fetchAll($sql);
    }
    
    public function getCurrentConcours(){
	    $db = $this->getAdapter();
	    $sql = "select c.idConcours, e.name, c.dateDeb, c.dateFin, c.nbGagnants, c.nbPlaces, c.publie, c.valide from hapta_concours c join hapta_event e on c.idEvent=e.id where valide = 1 and c.publie = 1 and (dateDeb<now() and dateFin>=now()) and passe=0 order by c.dateFin";
	    return $db->fetchAll($sql);
    }
    
    public function getPastConcours(){
	    $db = $this->getAdapter();
	    $sql = "select c.idConcours, e.name, c.dateDeb, c.dateFin, c.nbGagnants, c.nbPlaces, c.publie, c.valide from hapta_concours c join hapta_event e on c.idEvent=e.id where valide = 1 and c.publie = 1 and dateDeb<now() and passe=1 order by c.dateFin";
	    return $db->fetchAll($sql);
    }
    
    public function addConcours($data){
	    return $this->insert($data);
    }
    
    public function getConcours($id){
	    $rowConcours = $this->fetchRow('idConcours = ' . (int)$id);
	    if(!$rowConcours){
		    throw new Exception("Concours non trouvé : " . $id);
	    }
	    return $rowConcours->toArray();
    }
    
    public function updateConcours($id, $data){
	    $this->update($data, 'idConcours = '.(int)$id);
    }
    
    public function getConcoursEdit($id){
	    $db = $this->getAdapter();
	    $sql = 'SELECT idConcours, dateDeb, dateFin, nbPlaces, nbGagnants, name, date FROM hapta_concours c, hapta_event e WHERE c.idEvent = e.id AND c.idConcours = :idConcours LIMIT 1';
	    return $db->fetchAll($sql, array(':idConcours'=>$id))[0];
    }
    
    public function deleteConcours($id){
	    $this->delete('idConcours ='. (int)$id);
    }
    
    public function checkUserOwnConcours($id, $userId){
	    $db=$this->getAdapter();
	    $sql = 'SELECT he.poster, ea.idUser FROM hapta_concours hc RIGHT JOIN hapta_event he ON hc.idEvent=he.id LEFT JOIN hapta_event_admin ea ON he.id = ea.idEvent WHERE hc.idConcours=:idConcours';
	    $rowUsers = $db->fetchAll($sql, array(':idConcours'=>$id));
	    foreach($rowUsers as $user){
	    	if($user['poster'] == $userId || $user['idUser'] == $userId){
		    	return;
	    	}
	    }
	    throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
    }
    
    public function changeStateConcours($id, $data){
    	$this->update($data, 'idConcours = '.(int)$id);
    }
    
    public function getIdPostFromIdEvent($idEvent){
	    $db = $this->getAdapter();
	    $sql = 'SELECT idPost FROM hapta_concours WHERE idEvent=:idEvent';
	    $result = $db->fetchAll($sql, array(':idEvent'=>$idEvent));//[0]['idPost'];
	    //return $result;
	    if(count($result) > 0){
	    	return $result[0]['idPost'];
	    } else {
		    return -1;
	    }
    }
    
    public function updateIdPost($idEvent, $data){
	    $this->update($data, 'idEvent = '.(int)$idEvent);
    }
    
}

