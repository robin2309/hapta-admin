<?PHP
//classe Modele d'event

class Application_Model_Event{
	protected $_id;
	protected $_name;
	protected $_spot;
	protected $_linkFb;
	protected $_price;
	protected $_linkTicket;
	protected $_publie;
	protected $_heureDebut;
	protected $_heureFin;
	protected $_date;
	protected $_img;
	protected $_attending;
	protected $_admins;
	protected $_artists;
	protected $_idConcours;
	protected $_genres;
	protected $_promote;
	protected $_dataTable;
	protected $_concoursValid;
	
	//TODO
	//ajout classe spot dans attributs
	
	
    public function __construct(array $options = null)
    {
    	$this->_dataTable = new Application_Model_DbTable_Event();
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
	
	//SETTERS
	public function setId($id){
		$this->_id = $id;
		return $this;
	}
	
	public function setName($name){
		$this->_name = $name;
		return $this;	
	}
	
	public function setSpot($spot){
		$this->_spot = $spot;
		return $this;
	}
	
	public function setLinkFb($link){
		$this->_linkFb = $link;
		return $this;
	}
	
	public function setPrice($price){
		$this->_price = $price;
		return $this;
	}
	
	public function setLinkTicket($link){
		$this->_linkTicket = $link;
		return $this;
	}
	
	public function setPublie($publie){
		$this->_publie = $publie;
		return $this;
	}
	
	public function setHeureDebut($heure){
		$this->_heureDebut = $heure;
		return $this;
	}
	
	public function setHeureFin($heure){
		$this->_heureFin = $heure;
		return $this;
	}
	
	public function setDate($date){
		$this->_date = $date;
		return $this;
	}
	
	public function setImg($img){
		$this->_img = $img;
		return $this;
	}
	
	public function setAttending($attending){
		$this->_attending = $attending;
		return $this;
	}
	
	public function setGenres($genre){
		$this->_genre = $genre;
		return $this;		
	}
	
	public function setAdmins($admins){
		$this->_admins = $admins;
		return $this;
	}
	
	public function setArtists($artists){
		$this->_artists = $artists;
		return $this;
	}
	
	public function setIdConcours($concours){
		$this->_concours = $concours;
		return $this;
	}
	
	public function setPromote($promote){
		$this->_promote = $promote;
		return $this;
	}
	
	public function setConcoursValid($concoursValid){
		$this->_concoursValid = $concoursValid;
		return $this;
	}
	
	//GETTERS
	public function getId(){
		return $this->_id;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getSpot(){
		return $this->_spot;
	}
	
	public function getLinkFb(){
		return $this->_linkFb;
	}
	
	public function getPrice(){
		return $this->_price;
	}
	
	public function getLinkTicket(){
		return $this->_linkTicket;
	}
	
	public function getPublie(){
		return $this->_publie;
	}
	
	public function getHeureDebut(){
		return $this->_heureDebut;
	}
	
	public function getHeureFin(){
		return $this->_heureFin;
	}
	
	public function getDate(){
		return $this->_date;
	}
	
	public function getImg(){
		return $this->_img;
	}
	
	public function getAttending(){
		return $this->_attending;
	}
	
	public function getGenres(){
		return $this->_genre;
	}
	
	public function getAdmins(){
		return $this->_admins;
	}
	
	public function getArtists(){
		return $this->_artists;
	}
	
	public function getIdConcours(){
		return $this->_concours;
	}
	
	public function getPromote(){
		return $this->_promote;
	}
	
	public function getConcoursValid(){
		return $this->_concoursValid;
	}

	//DATA ACCESS
	
	public function insertEvent($data, $artists, $genres, $admins, $prices, $spot){
		$spotObj = new Application_Model_Spot();
		$artistEvent = new Application_Model_DbTable_ArtistEvent();
		$genreEvent = new Application_Model_DbTable_GenreEvent();
		$adminEvent = new Application_Model_DbTable_AdminEvent();
		$priceEvent = new Application_Model_DbTable_PriceEvent();
		
		//admins exist?
		if(($adminName = $adminEvent->checkAdminsExist($admins)) != 1) return array('code' => 4, 'name' => $adminName);
		
		//spot exists?
		$data['idSpot'] = $spotObj->getSpotIdFromName($spot);
		if(is_null($data['idSpot'])) return array('code' => 1, 'name' => $spot);
		
		//artists exist?
		if(($artistName = $artistEvent->checkArtistsExist($artists)) != 1) return array('code' => 2, 'name' => $artistName);
		
		//genres exist?
		if(($genreName = $genreEvent->checkGenresExist($genres)) != 1) return array('code' => 3, 'name' => $genreName);
		
		$idEvent = $this->_dataTable->addEvent($data);
		
		$artistEvent->insertArtistEventFromNames($idEvent, $artists);
		$genreEvent->insertGenreEventFromNames($idEvent, $genres);
		$adminEvent->insertAdminEventFromNames($idEvent, $admins);
		$priceEvent->addEventPrice($idEvent, $prices);
		return $idEvent;
		
	}
	
	public function updateEvent($id, $data, $artists, $genres, $admins, $prices, $spot){
		$spotObj = new Application_Model_Spot();
		$artistEvent = new Application_Model_DbTable_ArtistEvent();
		$genreEvent = new Application_Model_DbTable_GenreEvent();
		$adminEvent = new Application_Model_DbTable_AdminEvent();
		$priceEvent = new Application_Model_DbTable_PriceEvent();
		
		$concours = new Application_Model_DbTable_Concours();
		
		//admins exist?
		if(($adminName = $adminEvent->checkAdminsExist($admins)) != 1) return array('code' => 4, 'name' => $adminName);
		
		//spot exists?
		$data['idSpot'] = $spotObj->getSpotIdFromName($spot);
		if(is_null($data['idSpot'])) return array('code' => 1, 'name' => $spot);
		
		//artists exist?
		if(($artistName = $artistEvent->checkArtistsExist($artists)) != 1) return array('code' => 2, 'name' => $artistName);
		
		//genres exist?
		if(($genreName = $genreEvent->checkGenresExist($genres)) != 1) return array('code' => 3, 'name' => $genreName);
		
		$result = $this->_dataTable->updateEvent($id, $data);
		
		$artistEvent->updateArtistEventFromNames($id, $artists);
		$genreEvent->updateGenreEventFromNames($id, $genres);
		$adminEvent->updateAdminEventFromNames($id, $admins);
		$priceEvent->updateEventPrice($id, $prices);
		return $result;
	}
	
	public function quickInsertEvent($data){
		$quickAddEvents = $this->_dataTable->getEventsQuickAdd();
		if($this->checkDoubleQuickAdd($quickAddEvents, $data['linkFb'])){
			return -1;
		} else {
			return $this->_dataTable->addEvent($data);
		}
	}
	
	
	public function countEventFromUser($idUser){
		return $this->_dataTable->countFromUser($idUser);
	}
	
	public function sumAttendingFromUserEvents($idUser){
		return $this->_dataTable->sumAttendingFromUserEvents($idUser);
	}
	
	public function getThreeMostAttendedFromUser($idUser){
		return $this->_dataTable->getThreeMostAttendedFromUser($idUser);
	}
	
	private function checkDoubleQuickAdd($events,$linkFb){
		$splittedLinkFbToAdd = split('/',$linkFb);
		foreach($events as $event){
			$splittedLinkFbAdded = split('/',$event->getLinkFb());
			if(strcmp($splittedLinkFbAdded[3],$splittedLinkFbToAdd[3]) === 0 &&
				strcmp($splittedLinkFbAdded[4],$splittedLinkFbToAdd[4]) === 0){
				return true;
			}
		}
		return false;
	}
	
	public function promoteEvent($id){
		$event = $this->_dataTable->getEvent($id);
		$timesPromoted = $event['promote'];
		$linkFb = $event['linkFb'];
    	$arrayLink = explode("/", $linkFb);
    	if(substr($linkFb, -1) == "/"){
    		$fbEvent = $arrayLink[count($arrayLink)-2];
    	} else {
        	$fbEvent = $arrayLink[count($arrayLink)-1];
    	}
    	
    	$this->postEventToFb($fbEvent);
    	
    	$data = array('promote'=>$timesPromoted+1);
    	$this->_dataTable->updateEvent($id, $data);
    	return $data['promote'];
	}
	
	private function postEventToFb($idEventFb){
		$config = array(
    		'appId' => Zend_Registry::get('Fb_App_Id'),
    		'secret' => Zend_Registry::get('Fb_App_Secret'),
    		'fileUpload' => true
    	);
    	$accessToken = Zend_Registry::get('Fb_App_Token');
    	$city = strtolower(Zend_Registry::get('City_Location'));
    	$imgPath = dirname(__FILE__).'/../../public/img/'.Zend_Registry::get('Sitename').'-pub.jpg';
    	
		$fb = new My_Social_Facebook($config);

		$input = array("Rejoins la communauté Hapta sur www.hapta".$city.".fr", "Trouve facilement cet event et plein d'autres sur www.hapta".$city.".fr", "Retrouve cet event et plein d'autre sur www.hapta".$city.".fr");
		$rand_keys = array_rand($input, 2);
		// $imgPath = dirname(__FILE__).'../../public/img/hapta'.$city.'-pub.jpg';
		
		$params = array(
			"access_token" => $accessToken,
			"message" => $input[$rand_keys[0]],
			"source" => "@$imgPath"
		);
		
		try {
			$ret = $fb->api('/'.$idEventFb.'/photos', 'POST', $params);
		} catch(Exception $e) {
			throw new Zend_Controller_Action_Exception('Oops, erreur lors du post Facebook', 500);
		}
	}

}

?>