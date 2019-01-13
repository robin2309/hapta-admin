<?php

class Application_Model_DbTable_Event extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_event';
    
    protected function prepareResultSet($result){
	   	$entries   = array();
        foreach ($result as $row) {
            $entry = new Application_Model_Event($row);
            $spot = new Application_Model_Spot(array('id'=>$row['idSpot'],'name'=>$row['nameSpot']));

            $entry->setArtists($this->getArtistsEvent($row['id']))
            	  //->setGenres($this->getGenresEvent($row['id']))
            	  ->setAdmins($this->getAdminsEvent($row['id']))
            	  ->setSpot($spot);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    protected function prepareResultSetQuickAdd($result){
	    $entries   = array();
        foreach ($result as $row) {
            $entry = new Application_Model_Event($row);

            $entries[] = $entry;
        }
        return $entries;
    }
    
    protected function getArtistsEvent($idEvent){
	    $dbArtistEvent = new Application_Model_DbTable_ArtistEvent();
	    $artists = $dbArtistEvent->getArtistsName($idEvent);
	    return implode(', ', $artists);
    }
    
    protected function getAdminsEvent($idEvent){
	    $dbAdminEvent = new Application_Model_DbTable_AdminEvent();
	    $admins = $dbAdminEvent->getAdminNames($idEvent);
	    return implode(', ', $admins);
    }

    
    public function getAllEvents(){
	    $result = $this->fetchAll();
	    return $this->prepareResultSet($result);
    }
    
    public function countAllEvents(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbEvents FROM hapta_event WHERE publie=1";
	    return $db->fetchAll($sql)[0]['nbEvents'];
    }
    
    public function getEventsAdmin(){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND NOT (name IS NULL OR name='') ORDER BY e.date, e.attending";
	    $obj = $db->query($sql);
	    $events = $this->prepareResultSet($obj);
	    return $events;
    }
    
    
    public function getEventsUser($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours, co.valide as concoursValid FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) ORDER BY e.date, e.attending";
	    $params = array(':userId'=>$userId);
	    $obj = $db->query($sql, $params);
	    $events = $this->prepareResultSet($obj);
	    return $events;
    }
	
	 public function getEventsCountUser($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(DISTINCT e.id) as nbEvents FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) AND (e.publie=1 OR e.publie=0) ORDER BY e.date, e.attending ";
	    $params = array(':userId'=>$userId);
	    return $db->query($sql, $params)->fetchAll()[0]['nbEvents'];
    }
    
    public function getEvents(){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending,  e.promote, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND e.publie=1 ORDER BY e.date, e.attending";
	    $obj = $db->query($sql);
	    $events = $this->prepareResultSet($obj);
	    return $events;
    }
    
    public function getEventsCount(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(DISTINCT e.id) as nbEvents FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND e.publie=1 ORDER BY e.date, e.attending ";
	    return $db->query($sql)->fetchAll()[0]['nbEvents'];
    }
    
    public function getEventsUserPast($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) ORDER BY e.date DESC LIMIT 25";
	    $params = array(':userId'=>$userId);
	    $obj = $db->query($sql, $params);
	    $events = $this->prepareResultSet($obj);
		return $events;
	}
	
	public function getAllEventsUserPast($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) ORDER BY e.date DESC";
	    $params = array(':userId'=>$userId);
	    $obj = $db->query($sql, $params);
	    $events = $this->prepareResultSet($obj);
		return $events;
	}
	

	public function getEventsUserPastCount($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(DISTINCT e.id) as nbEvents FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) ORDER BY e.date DESC";
	    $params = array(':userId'=>$userId);
	    return $db->query($sql, $params)->fetchAll()[0]['nbEvents'];
	}
	
	public function getEventsPast(){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') ORDER BY e.date DESC LIMIT 25";
	    $obj = $db->query($sql);
	    $events = $this->prepareResultSet($obj);
		return $events;
	}
	
	public function getAllEventsPast(){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') ORDER BY e.date DESC";
	    $obj = $db->query($sql);
	    $events = $this->prepareResultSet($obj);
		return $events;
	}
	
	public function getEventsPastCount(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(DISTINCT e.id) as nbEvents FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') ORDER BY e.date DESC";
	    return $db->query($sql)->fetchAll()[0]['nbEvents'];
	}
	
	
	public function getInvalidEventsUser($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) AND e.publie = 0 ORDER BY e.date, e.attending LIMIT 25";
	    $params = array(':userId'=>$userId);
	    $obj = $db->query($sql, $params);
	    $events = $this->prepareResultSet($obj);
	    return $events;
	}
	
	 public function getInvalidEventsUserCount($userId){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(DISTINCT e.id) as nbEvents FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND (ad.idUser = :userId OR e.poster = :userId) AND e.publie = 0 AND NOT (e.name IS NULL OR e.name='') ORDER BY e.date, e.attending";
	    $params = array(':userId'=>$userId);
	    return $db->query($sql, $params)->fetchAll()[0]['nbEvents'];
    }
    
    public function getInvalidEvents(){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND e.publie = 0 AND NOT (e.name IS NULL OR e.name='') ORDER BY e.date, e.attending LIMIT 25";
	    $obj = $db->query($sql);
	    $events = $this->prepareResultSet($obj);
	    return $events;
	}
	
	public function getInvalidEventsCount(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(DISTINCT e.id) as nbEvents FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date>=date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND e.publie = 0 AND NOT (e.name IS NULL OR e.name='') ORDER BY e.date, e.attending";
	    return $db->query($sql)->fetchAll()[0]['nbEvents'];
    }
    
    public function getEventsQuickAdd(){
	    $db = $this->getAdapter();
	    $sql = "SELECT linkFb, id, date FROM `hapta_event` WHERE linkFb LIKE 'https://%' AND heureDebut IS NULL AND heureFin IS NULL AND (name IS NULL OR name='') AND date >= now() order by date, linkFb";
	    $obj = $db->query($sql);
	    $events = $this->prepareResultSetQuickAdd($obj);
		return $events;
    }
    
    public function countEventsQuickAdd(){
	    $db = $this->getAdapter();
	    $sql = "SELECT count(*) as nbEvents FROM `hapta_event` WHERE linkFb LIKE 'https://%' AND heureDebut IS NULL AND heureFin IS NULL AND (name IS NULL OR name='') AND date >= now()";
	    return $db->query($sql)->fetchAll()[0]['nbEvents'];
    }
	
	
    public function getEventNameDate($idEvent){
    	$event = $this->fetchRow('id = ' . (int)$idEvent);
    	if(!$event){
		    throw new Exception("Evenement non trouvé : " . $idEvent);
	    }
	    return $event->toArray();
    }
    
    public function getEvent($id){
	    $rowEvent = $this->fetchRow('id = ' . (int)$id);
	    if(!$rowEvent){
		    throw new Exception("Evenement non trouvé : " . $id);
	    }
	    $event = $rowEvent->toArray();
	    
        $event['idArtist'] = $this->getArtistEvent($id);
        $event['idGenre'] = $this->getGenreEvent($id);
        $event['idAdmins'] = $this->getAdminEvent($id);
        $event['idPrices'] = $this->getPriceEvent($id);
	    return $event;
    }
    
    private function getArtistEvent($idEvent){
	    $dbArtistEvent = new Application_Model_DbTable_ArtistEvent();
	    return $dbArtistEvent->getArtists($idEvent);
    }
    
    private function getGenreEvent($idEvent){
	    $dbGenreEvent = new Application_Model_DbTable_GenreEvent();
	    return $dbGenreEvent->getGenres($idEvent);
    }
    
    private function getAdminEvent($idEvent){
	    $dbAdminEvent = new Application_Model_DbTable_AdminEvent();
	    return $dbAdminEvent->getAdmins($idEvent);
    }
    
    private function getPriceEvent($idEvent){
    	$dbPriceEvent = new Application_Model_DbTable_PriceEvent();
    	return $dbPriceEvent->getPrices($idEvent);
    }
    
    public function addEvent($data){
    	try{
	    	$idEvent = $this->insert($data);
    	} catch(Zend_Db_Statement_Exception $e){
	    	//if ($e->getCode() == 23000) {
	    	//	return -1;
	    	//} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	//}
	    }
        return $idEvent;
    }
    
    private function addEventArtist($artists, $idEvent){
    	$dbArtistEvent = new Application_Model_DbTable_ArtistEvent();
    	if(is_array($artists)){
	    	for($i = 0; $i < count($artists); $i++){
		    	$dbArtistEvent->addLine(array('idArtist' => $artists[$i], 'idEvent' => $idEvent));
	    	}
    	} else {
	    	$dbArtistEvent->addLine(array('idArtist' => $artists, 'idEvent' => $idEvent));
    	}
    }
    
    private function addEventGenre($genres, $idEvent){
    	$dbGenreEvent = new Application_Model_DbTable_GenreEvent();
    	if(is_array($genres)){
	    	for($i = 0; $i < count($genres); $i++){
		    	$dbGenreEvent->addLine(array('idGenre' => $genres[$i], 'idEvent' => $idEvent));
	    	}
    	} else {
	    	$dbGenreEvent->addLine(array('idGenre' => $genres, 'idEvent' => $idEvent));
    	}
    }
    
    private function addEventAdmin($admins, $idEvent){
	    $dbAdminEvent = new Application_Model_DbTable_AdminEvent();
    	if(is_array($admins)){
	    	for($i = 0; $i < count($admins); $i++){
		    	$dbAdminEvent->addLine(array('idEvent' => $idEvent, 'idUser' => $admins[$i]));
	    	}
    	} else {
	    	$dbAdminEvent->addLine(array('idEvent' => $idEvent, 'idUser' => $admins));
    	}
    }
    
    public function addEventPrice($prices, $idEvent){
	    $dbPriceEvent = new Application_Model_DbTable_PriceEvent();
	    foreach($prices as $price){
		    $dbPriceEvent->addLine(array('idEvent'=> $idEvent, 'idType'=> $price['idType'], 'price'=> $price['price'], 'link'=> $price['link']));
	    }
    }
    
    
    public function updateEvent($idEvent, $data){
	    try{
	    	$this->update($data, 'id = '.(int)$idEvent);
    	} catch(Zend_Db_Statement_Exception $e){
	    	if ($e->getCode() == 23000) {
	    		return -1;
	    	} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    	}
	    }
	    return $idEvent;
	    /*$this->updateEventArtist($idEvent, $artists);
    	
    	$this->updateEventGenre($idEvent, $genres);
    	
    	$this->updateEventAdmin($idEvent, $admins);
    	
    	$this->updateEventPrices($idEvent, $prices);*/
    }
    
    private function updateEventArtist($idEvent, $artists){
	    $dbArtistEvent = new Application_Model_DbTable_ArtistEvent();
	    $dbArtistEvent->deleteArtists($idEvent);
	    if(is_array($artists)){
	    	for($i = 0; $i < count($artists); $i++){
		    	$dbArtistEvent->addLine(array('idArtist' => $artists[$i], 'idEvent' => $idEvent));
	    	}
    	} else {
	    	$dbArtistEvent->addLine(array('idArtist' => $artists, 'idEvent' => $idEvent));
    	}
    }
    
    private function updateEventGenre($idEvent, $genres){
	    $dbGenreEvent = new Application_Model_DbTable_GenreEvent();
    	$dbGenreEvent->deleteGenres($idEvent);
    	if(is_array($genres)){
	    	for($i = 0; $i < count($genres); $i++){
		    	$dbGenreEvent->addLine(array('idGenre' => $genres[$i], 'idEvent' => $idEvent));
	    	}
    	} else {
	    	$dbGenreEvent->addLine(array('idGenre' => $genres, 'idEvent' => $idEvent));
    	}
    }
    
    private function updateEventAdmin($idEvent, $admins){
	    $dbAdminEvent = new Application_Model_DbTable_AdminEvent();
	    $dbAdminEvent->deleteAdmins($idEvent);
    	if(is_array($admins)){
	    	for($i = 0; $i < count($admins); $i++){
		    	$dbAdminEvent->addLine(array('idEvent' => $idEvent, 'idUser' => $admins[$i]));
	    	}
    	} else {
	    	$dbAdminEvent->addLine(array('idEvent' => $idEvent, 'idUser' => $admins));
    	}   
    }
    
    private function updateEventPrices($idEvent, $prices){
	    $dbEventPrice = new Application_Model_DbTable_PriceEvent();
	    $dbEventPrice->deletePrices($idEvent);
    	foreach($prices as $priceLine){
	    	$dbEventPrice->addLine(array('idEvent' => $idEvent, 'idType' => $priceLine['idType'], 'price' => $priceLine['price'], 'link' => $priceLine['link']));
    	}
    }
    
    //CHANGE PUBLIE OU VALIDE
    public function changeStateEvent($id, $data){
    	$this->update($data, 'id = '.(int)$id);
    }
    
    public function deleteEvent($id){
	    $this->delete('id ='. (int)$id);
    }
    
    public function getEventOptions($userId){
	    $db = $this->getAdapter();
	    $sql = 'SELECT id, name, date, idConcours FROM hapta_event_admin ea, hapta_event e LEFT JOIN hapta_concours c ON e.id = c.idEvent WHERE ea.idEvent = e.id AND (e.poster=:userId OR ea.idUser=:userId)';
	    return $db->fetchAll($sql, array(':userId'=>$userId));
    }
    
    public function checkUserOwnEvent($idEvent, $userId){
    	$db = $this->getAdapter();
    	$sql="SELECT e.poster, ea.idUser FROM hapta_event_admin ea RIGHT JOIN hapta_event e ON ea.idEvent = e.id and e.id=:idEvent";
    	$rowUsers = $db->fetchAll($sql, array(':idEvent'=>$idEvent));
	    foreach($rowUsers as $user){
	    	if($user['poster'] == $userId || $user['idUser'] == $userId){
		    	return;
	    	}
	    }
	    throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
    }
    
    public function checkEventPast($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT id FROM `hapta_event` WHERE id=:idEvent AND date<date_format(date_add(now(), INTERVAL -5 hour),'%Y-%m-%d') AND date != '0000-00-00'";
	    if(!(count($db->fetchAll($sql, array(':idEvent'=>$idEvent))))){
		    return;
	    }
	    throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
    }
    
    public function checkEventValidable($idEvent){
	    $db = $this->getAdapter();
	    $sqlSpot = "SELECT hc.valide FROM hapta_club hc JOIN hapta_event he ON hc.id=he.idSpot WHERE he.id=:idEvent";
	    $sqlGenre = "SELECT valide FROM hapta_genre hg JOIN hapta_event_genre heg ON hg.idGenre=heg.idGenre WHERE heg.idEvent=:idEvent";
	    $sqlArtist = "SELECT ha.valide FROM hapta_artist ha JOIN hapta_event_artist hea ON ha.idArtist = hea.idArtist WHERE hea.idEvent = :idEvent";
	    $params = array(':idEvent'=>$idEvent);
	    
	    $spot = $db->fetchAll($sqlSpot, $params);
	    if(!($spot[0]['valide'])) return false;
	    
	    $genres = $db->fetchAll($sqlGenre, $params);
	    foreach($genres as $row){
		    if(!$row['valide']) return false;
	    }
	    
	    $artists = $db->fetchAll($sqlArtist, $params);
	    foreach($artists as $row){
		    if(!$row['valide']) return false;
	    }
	    
	    return true;
    }
    
    public function countFromUser($idUser){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT count(*) as nbEvents FROM hapta_event he LEFT JOIN hapta_event_admin hea ON he.id = hea.idEvent WHERE he.poster=:idUser OR hea.idUser=:idUser AND attending > 0";
	    return $db->fetchAll($sql, array(':idUser' => $idUser))[0]['nbEvents'];
    }
    
    public function sumAttendingFromUserEvents($idUser){
	    $db = $this->getAdapter();
	    $sql = "SELECT DISTINCT sum(attending) as nbAttending FROM hapta_event he LEFT JOIN hapta_event_admin hea ON he.id = hea.idEvent WHERE he.poster=:idUser OR hea.idUser=:idUser";
	    return $db->fetchAll($sql, array(':idUser' => $idUser))[0]['nbAttending'];
    }
    
    public function getThreeMostAttendedFromUser($idUser){
	    $db = $this->getAdapter();
	    $sql = "SELECT he.id, he.name, date, attending, hc.id as idSpot, hc.name as nameSpot FROM hapta_event he LEFT JOIN hapta_event_admin hea ON he.id = hea.idEvent LEFT JOIN hapta_club hc ON he.idSpot=hc.id WHERE he.poster=:idUser OR hea.idUser=:idUser AND publie=1 ORDER BY attending DESC LIMIT 3";
	    return $this->prepareResultSet($db->query($sql, array(':idUser' => $idUser)));
    }
    
    public function getLinkFbFromId($idUser){
	    $db = $this->getAdapter();
	    $sql = "SELECT img FROM hapta_event WHERE id=:idUser";//:idUser";
	    return $db->fetchAll($sql, array(':idUser' => $idUser))[0]['img'];
    }
    
    public function getTodayTopThree(){
	    $db = $this->getAdapter();
	    $sql= "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date=curdate() AND NOT (e.name IS NULL OR e.name='') ORDER BY e.attending DESC";
	    return $db->fetchAll();
    }
}

