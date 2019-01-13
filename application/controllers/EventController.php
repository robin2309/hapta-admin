<?php

class EventController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    private function isValid($formData)
    {
	    if(trim($formData['name']) == ""){
		    return false;
	    }
	    if(trim(json_decode($formData['tagsSpot'], true)[0]['name']) == ""){
	    	return false;
	    }
	    if(trim($formData['date']) == ""){ //DATE FORMAT
		    return false;
	    }
	    if(count(json_decode($formData['tagsArtist'], true)) == 0){
		    return false;
	    }
	    if($formData['idType1'] == 1){
		    return false;
	    }
	    return true;
    }

    private function isValidForEdit($formData)
    {
	    if(trim($formData['name']) == ""){
		    return false;
	    }
	    if(trim(json_decode($formData['tagsSpot'], true)[0]['name']) == ""){
	    	return false;
	    }
	    if(trim($formData['date']) == ""){ //DATE FORMAT
		    return false;
	    }
	    if(count(json_decode($formData['tagsArtist'], true)) == 0){
		    return false;
	    }
	    if($formData['idType1'] == 1){
		    return false;
	    }
	    return true;
    }

    private function isValidForQuickAdd($formData)
    {
	    if(trim($formData['linkFb']) == ""){
		    return false;
	    }
	    return true;
    }

    public function indexAction()
    {
    	$auth = Zend_Auth::getInstance();
    	$userId = $auth->getIdentity()->id;

        $events = new Application_Model_DbTable_Event();
        $this->view->invalidEvents = $events->getInvalidEventsUser($userId);
		$this->view->countInvalidEvent = $events->getInvalidEventsUserCount($userId);
        
        $this->view->events = $events->getEventsUser($userId);
		$this->view->countEvents = $events->getEventsCountUser($userId);
		
        $this->view->pastEvents = $events->getEventsUserPast($userId);
		$this->view->countPastEvents = $events->getEventsUserPastCount($userId);
		
		$msg = $this->_getParam('msg',0);
		if($msg == 1){
			$this->view->notValidable = 1;
		}
    }

    public function indexadminAction()
    {
	    $events = new Application_Model_DbTable_Event();
        $this->view->invalidEvents = $events->getInvalidEvents();
		$this->view->countInvalidEvent = $events->getInvalidEventsCount();
        
        $this->view->events = $events->getEvents();
		$this->view->countEvents = $events->getEventsCount();
		
        $this->view->pastEvents = $events->getEventsPast();
        $this->view->allPastEvents = $events->getAllEventsPast();
		$this->view->countPastEvents = $events->getEventsPastCount();
		
		$this->view->quickAddEvents = $events->getEventsQuickAdd();
		$this->view->countQuickAddEvents = $events->countEventsQuickAdd();
		
		$msg = $this->_getParam('msg',0);
		if($msg == 1){
			$this->view->notValidable = 1;
		}elseif($msg == 2){
			$this->view->promoted = 1;
		}
    }

    private function getTypePrice($idTypes = null)
    {
	    $types = new Application_Model_DbTable_TypePrice();
	    $result = $types->getTypes();
	    $options = "";
	    if(is_null($idTypes)){
		    foreach($result as $row){
			    $option = '<option value="'. $row['id'] .'">'. $row['nameType'] .'</option>';
		        $options = $options . $option;
		    }
	    } else {
	        foreach ($result as $row) {
	        	if($row['id'] == $idTypes){
		        	$option = '<option value="'. $row['id'] .'" selected="selected">'. $row['nameType'] .'</option>';
	        	} else {
		        	$option = '<option value="'. $row['id'] .'">'. $row['nameType'] . '</option>';
	        	}
		        $options = $options . $option;
	        }		   
	    }
	    return $options;
    }

    public function addAction()
    {
    	$this->view->typeOptions = $this->getTypePrice();      
        $request = $this->getRequest();
        $auth = Zend_Auth::getInstance();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $spot = json_decode($formData['tagsSpot'], true)[0]['name'];
		        $artistsName = $this->parseArtists($formData['tagsArtist']);
		        $genresName = $this->parseGenres($formData['tagsGenre']);
		        $adminsName = $this->parseAdmins($formData['tagsUser']);
		        $prices = $this->parsePricesForAddNew($formData);
				//$this->view->test = $prices;
		        
		        $data = $this->parseEventForAdd($formData);
		        
		        $event = new Application_Model_Event();
		        //$this->_helper->viewRenderer->setNoRender(true);
		        $insert = $event->insertEvent($data, $artistsName, $genresName, $adminsName, $prices, $spot);
		        
		        if(is_array($insert) || $insert < 0){
		        	$formData = $this->setErrorTypeFromInsertAndUpdate($insert, $formData);
		        	if($insert < 0) $formData['existingLinkFb'] = 1;
		        	$formData['artists'] = implode(', ', $artistsName);
		        	$formData['genres'] = implode(', ', $genresName);
		        	$formData['spots'] = $spot;
		        	$formData['admins'] = implode(', ', $adminsName);
			        $this->view->dataPopulate = $formData;
			        return;
				}
		        $this->_helper->redirector('index');
	        } else {
	        	$formData['artists'] = implode(', ', $this->parseArtists($formData['tagsArtist']));
	        	$formData['genres'] = implode(', ', $this->parseGenres($formData['tagsGenre']));
	        	$formData['spots'] = json_decode($formData['tagsSpot'], true)[0]['name'];
	        	$formData['admins'] = implode(', ', $this->parseAdmins($formData['tagsUser']));
	        	$formData['errEmptyField'] = 1;
		        $this->view->dataPopulate = $formData;
	        }
        }
    }

    public function addadminAction()
    {
        $this->view->typeOptions = $this->getTypePrice();      
        $request = $this->getRequest();
        $auth = Zend_Auth::getInstance();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $spot = json_decode($formData['tagsSpot'], true)[0]['name'];
		        $artistsName = $this->parseArtists($formData['tagsArtist']);
		        $genresName = $this->parseGenres($formData['tagsGenre']);
		        $adminsName = $this->parseAdmins($formData['tagsUser']);
		        $prices = $this->parsePricesForAddNew($formData);
				//$this->view->test = $prices;
		        
		        $data = $this->parseEventForAdd($formData);
		        
		        $event = new Application_Model_Event();
		        //$this->_helper->viewRenderer->setNoRender(true);
		        $insert = $event->insertEvent($data, $artistsName, $genresName, $adminsName, $prices, $spot);
		        
		        if(is_array($insert) || $insert < 0){
		        	$formData = $this->setErrorTypeFromInsertAndUpdate($insert, $formData);
		        	if($insert < 0) $formData['existingLinkFb'] = 1;
		        	$formData['artists'] = implode(', ', $artistsName);
		        	$formData['genres'] = implode(', ', $genresName);
		        	$formData['spots'] = $spot;
		        	$formData['admins'] = implode(', ', $adminsName);
			        $this->view->dataPopulate = $formData;
			        return;
				}
		        $this->_helper->redirector('indexAdmin');
	        } else {
	        	$formData['artists'] = implode(', ', $this->parseArtists($formData['tagsArtist']));
	        	$formData['genres'] = implode(', ', $this->parseGenres($formData['tagsGenre']));
	        	$formData['spots'] = json_decode($formData['tagsSpot'], true)[0]['name'];
	        	$formData['admins'] = implode(', ', $this->parseAdmins($formData['tagsUser']));
	        	$formData['errEmptyField'] = 1;
		        $this->view->dataPopulate = $formData;
	        }
        }
    }

    public function deleteAction()
    {
    	$auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        if($this->getRequest()->isPost()){
        	$delete = $this->getRequest()->getPost('del');
        	if($delete == 'Oui'){
		        $id = $this->getRequest()->getPost('id');
		        $events = new Application_Model_DbTable_Event();
		        $events->checkUserOwnEvent($id, $userId);
		        $events->checkEventPast($id);
		        $events->deleteEvent($id);	
        	}
		    $this->_helper->redirector('index');
        } else {
        	$id = $this->_getParam('id',0);
        	$events = new Application_Model_DbTable_Event();
        	$events->checkUserOwnEvent($id, $userId);
        	$events->checkEventPast($id);
        	$this->view->event = $events->getEvent($id, $userId);
        	$this->_helper->getHelper('layout')->disableLayout();
        }
    }

    public function deleteadminAction()
    {
	    if($this->getRequest()->isPost()){
        	$delete = $this->getRequest()->getPost('del');
        	if($delete == 'Oui'){
		        $id = $this->getRequest()->getPost('id');
		        $events = new Application_Model_DbTable_Event();
		        $events->deleteEvent($id);	
        	}
		    $this->_helper->redirector('indexAdmin');    
        } else {
        	$id = $this->_getParam('id',0);
        	$events = new Application_Model_DbTable_Event();
        	$this->view->event = $events->getEvent($id);
        	$this->_helper->getHelper('layout')->disableLayout();
        }
    }

    public function editAction()
    {
    	$auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $request = $this->getRequest();
        $formData = array();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValidForEdit($formData)){
	        	$id = $formData['id'];
		        $spot = json_decode($formData['tagsSpot'], true)[0]['name'];
		        $artistsName = $this->parseArtists($formData['tagsArtist']);
		        $genresName = $this->parseGenres($formData['tagsGenre']);
		        $adminsName = $this->parseAdmins($formData['tagsUser']);
		        $prices = $this->parsePricesForEdit($formData);
				//$this->view->test = $prices;
		        
		        $data = $this->parseEventForEdit($formData);
		        
		        $idPost = trim($formData['idPost']);
		        if($idPost != ""){
		        	$data['idPost'] = $idPost;
		        } else {
		        	$data['idPost'] = null;
		        }
		        //$this->view->test = $data;

	        	$eventDb = new Application_Model_DbTable_Event();
	        	$eventDb->checkUserOwnEvent($id, $userId);
	        	$eventDb->checkEventPast($id);
	        	$event = new Application_Model_Event();
	        	$update = $event->updateEvent($id, $data, $artistsName, $genresName, $adminsName, $prices, $spot);
	        	
		        if(is_array($update)  || $update < 0){
			        $formData = $this->setErrorTypeFromInsertAndUpdate($update, $formData);
			        if($update < 0) $formData['existingLinkFb'] = 1;
			        $formData['artists'] = implode(', ', $artistsName);
		        	$formData['genres'] = implode(', ', $genresName);
		        	$formData['spot'] = $spot;
		        	$formData['admins'] = implode(', ', $adminsName);
		        	$formData = $this->setPricesToFormData($prices, $formData);
		        	$this->view->dataPopulate = $formData;
		        	$this->view->typeOptions = $this->getTypePrice();
		        	return;
		        }
		        
		        $this->_helper->redirector('index');
	        } else {
		        $formData['artists'] = implode(', ', $this->parseArtists($formData['tagsArtist']));
	        	$formData['genres'] = implode(', ', $this->parseGenres($formData['tagsGenre']));
	        	$formData['spot'] = json_decode($formData['tagsSpot'], true)[0]['name'];
	        	$formData['admins'] = implode(', ', $this->parseAdmins($formData['tagsUser']));

	        	$formData = $this->setPricesToFormData($this->parsePricesForEdit($formData), $formData);
	        	$formData['errEmptyField'] = 1;
		        $this->view->dataPopulate = $formData;
		        $this->view->typeOptions = $this->getTypePrice();
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$event = new Application_Model_DbTable_Event();
        		$event->checkUserOwnEvent($id, $userId);
        		$event->checkEventPast($id);
        		$formData = $this->setAttributesToFormData($id, $formData);
        		$this->view->dataPopulate = $formData;
        		$this->view->typeOptions = $this->getTypePrice();
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	        }
        }
    }

    public function draftAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$event = new Application_Model_DbTable_Event();
        		$event->checkUserOwnEvent($id, $userId);
        		$data = array('publie'=>0);
        		$event->changeStateEvent($id, $data);
        		$this->_helper->redirector('index');
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function editadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $request = $this->getRequest();
        $formData = array();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValidForEdit($formData)){
	        	$id = $formData['id'];
		        $spot = json_decode($formData['tagsSpot'], true)[0]['name'];
		        $artistsName = $this->parseArtists($formData['tagsArtist']);
		        $genresName = $this->parseGenres($formData['tagsGenre']);
		        $adminsName = $this->parseAdmins($formData['tagsUser']);
		        $prices = $this->parsePricesForEdit($formData);
				//$this->view->test = $prices;
		        
		        $data = $this->parseEventForEdit($formData);
		        
		        $idPost = trim($formData['idPost']);
		        if($idPost != ""){
		        	$data['idPost'] = $idPost;
		        } else {
		        	$data['idPost'] = null;
		        }
		        //$this->view->test = $data;

	        	$event = new Application_Model_Event();
	        	$update = $event->updateEvent($id, $data, $artistsName, $genresName, $adminsName, $prices, $spot);
	        	
		        if(is_array($update) || $update < 0){ // error on update
			        $formData = $this->setErrorTypeFromInsertAndUpdate($update, $formData);
			        if($update < 0) $formData['existingLinkFb'] = 1;
			        $formData['artists'] = implode(', ', $artistsName);
		        	$formData['genres'] = implode(', ', $genresName);
		        	$formData['spot'] = $spot;
		        	$formData['admins'] = implode(', ', $adminsName);
		        	$formData = $this->setPricesToFormData($prices, $formData);
		        	$this->view->dataPopulate = $formData;
		        	$this->view->typeOptions = $this->getTypePrice();
		        	return;
		        }
		        
		        $this->_helper->redirector('indexAdmin');
	        } else {
		        $formData['artists'] = implode(', ', $this->parseArtists($formData['tagsArtist']));
	        	$formData['genres'] = implode(', ', $this->parseGenres($formData['tagsGenre']));
	        	$formData['spot'] = json_decode($formData['tagsSpot'], true)[0]['name'];
	        	$formData['admins'] = implode(', ', $this->parseAdmins($formData['tagsUser']));

	        	$formData = $this->setPricesToFormData($this->parsePricesForEdit($formData), $formData);
	        	$formData['errEmptyField'] = 1;
		        $this->view->dataPopulate = $formData;
		        $this->view->typeOptions = $this->getTypePrice();
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$formData = $this->setAttributesToFormData($id, $formData);
        		$this->view->dataPopulate = $formData;
        		$this->view->typeOptions = $this->getTypePrice();
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	        }
        }
    }

    public function draftadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$event = new Application_Model_DbTable_Event();
        		$data = array('publie'=>0);
        		$event->changeStateEvent($id, $data);
        		$this->_helper->redirector('indexAdmin');
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function publishAction()
    {
    	$auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$event = new Application_Model_DbTable_Event();
        		$event->checkUserOwnEvent($id, $userId);
        		$event = new Application_Model_DbTable_Event();
    		if($event->checkEventValidable($id)){
	    		$data = array('publie'=>1);
	    		$event->changeStateEvent($id, $data);
	    		$this->_helper->redirector('index');
    		}else{
    			$this->redirect('/event/index/msg/1');
    		}
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function publishadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
    		$event = new Application_Model_DbTable_Event();
    		if($event->checkEventValidable($id)){
	    		$data = array('publie'=>1);
	    		$event->changeStateEvent($id, $data);
	    		$this->_helper->redirector('indexAdmin');
    		}else{
    			$this->redirect('/event/indexAdmin/msg/1');
    		}
	    } else {
			throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function addquickAction()
    {
	    $request = $this->getRequest();
	    $auth = Zend_Auth::getInstance();
	    $userId = $auth->getIdentity()->id;
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValidForQuickAdd($formData)){
	        	$spot = new Application_Model_Spot();
		       	$idSpotNonDef = $spot->getSpotIdFromName("-Non défini-");
		       	$this->_helper->viewRenderer->setNoRender(true);
		       	$data = array(
			       	'linkFb' => $formData['linkFb'],
			       	'idSpot' => $idSpotNonDef,
			       	'poster' => $userId,
			       	'date' => $formData['dateEventInput'],
		       	);
	        	$event = new Application_Model_Event();
	        	if($event->quickInsertEvent($data) < 0){
	        		$this->redirect('/index/index/msg/3');
	        	} else {
		        	$this->redirect('/index/index/msg/1');
	        	}
	        	//$this->_helper->redirector('index','index');
	        } else {
		        $this->redirect('/index/index/msg/2');
	        }
	    } else {
		    $this->_helper->redirector('index','index');		    
	    }
	    $this->_helper->viewRenderer->setNoRender(true);
    }
    
    
    public function promoteAction()
    {
        $id = $this->_getParam('id',0);
        if($id > 0){
        	$events = new Application_Model_Event();
        	$timesPromoted = $events->promoteEvent($id);
        	$this->_helper->viewRenderer->setNoRender(true);
        	$this->_helper->getHelper('layout')->disableLayout();
        	echo $timesPromoted;
	    } else {
		    throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }
    

    private function parseArtists($artistsField)
    {
    	$artists = json_decode($artistsField, true);
        $artistsName = array();
        foreach($artists as $artist){
	        array_push($artistsName, $artist['name']);
        }
        return $artistsName;
    }

    private function parseGenres($genresField)
    {
	    $genres = json_decode($genresField, true);
        $genresName = array();
        foreach($genres as $genre){
	        array_push($genresName, $genre['nameGenre']);
        }
        return $genresName;
    }

    private function parseAdmins($adminsField)
    {
	    $admins = json_decode($adminsField, true);
        $adminsName = array();
        foreach($admins as $admin){
	        array_push($adminsName, $admin['name']);
        }
        return $adminsName;
    }

    private function parsePricesForEdit($formData)
    {
    	$prices = array();
	    if($formData['idType1'] != 1){
    		array_push($prices,array(
	        	'idType'=>$formData['idType1'],
	        	'price'=>$formData['price1'],
	        	'link'=>$formData['link1'],
	        ));
	    }
		    
		if($formData['idType2'] != 1){
			array_push($prices, array(
					'idType'=>$formData['idType2'],
					'price'=>$formData['price2'],
					'link'=>$formData['link2'],
				));
		}
		if($formData['idType3'] != 1){
			array_push($prices, array(
				'idType'=>$formData['idType3'],
				'price'=>$formData['price3'],
				'link'=>$formData['link3'],
			));
		}
		if($formData['idType4'] != 1){
			array_push($prices, array(
				'idType'=>$formData['idType4'],
				'price'=>$formData['price4'],
				'link'=>$formData['link4'],
				));
		}
		return $prices;
    }
    
    private function parsePricesForAddNew($formData){
	    $parsedPrices = array();
	    $idTypes = $formData['idType'];
	    var_dump($idTypes);
	    echo "</br>";
	    $prices = $formData['price'];
	    var_dump($prices);
	    echo "</br>";
	    $links = $formData['link'];
	    var_dump($links);
	    echo "</br>";
	    
	    foreach($idTypes as $key => $type){
	    	array_push($parsedPrices, array(
	    		'idType'=>$type,
	    		'price'=>$prices[$key],
	    		'link'=>$links[$key]
	    	));
	    }
	    
	    var_dump($parsedPrices);
	    return $parsedPrices;
    }

    private function parsePricesForAdd($formData)
    {
	    $prices = array(
	    		array(
		        	'idType'=>$formData['idType1'],
		        	'price'=>$formData['price1'],
		        	'link'=>$formData['link1'],
		        )
		    );
		    
		if(isset($formData['idType2'])){
			array_push($prices, array(
					'idType'=>$formData['idType2'],
					'price'=>$formData['price2'],
					'link'=>$formData['link2'],
				));
			if(isset($formData['idType3'])){
				array_push($prices, array(
					'idType'=>$formData['idType3'],
					'price'=>$formData['price3'],
					'link'=>$formData['link3'],
					));
				if(isset($formData['idType4'])){
					array_push($prices, array(
						'idType'=>$formData['idType4'],
						'price'=>$formData['price4'],
						'link'=>$formData['link4'],
						));
				}
			}
		}
		return $prices;
    }

    private function setPricesToFormData($prices, $formData)
    {
	    foreach($prices as $index => $price){
    		$priceToAdd = array('typeOptions' => $this->getTypePrice($price['idType']),
    						'price' => $price['price'],
    						'link' => $price['link']
    						);
    		$formData['price'.($index+1)] = $priceToAdd;
    	}
    	return $formData;
    }

    private function setErrorTypeFromInsertAndUpdate($error, $formData)
    {
	    switch($error['code']){
        	case 1:
        		$formData['errInsert'] = array('type'=>'le spot','name'=>$error['name']);
        		break;
        	case 2:
        		$formData['errInsert'] = array('type'=>'l\'artiste','name'=>$error['name']);
        		break;
        	case 3:
        		$formData['errInsert'] = array('type'=>'le genre','name'=>$error['name']);
        		break;
        	case 4:
        		$formData['errInsert'] = array('type'=>'l\'orga','name'=>$error['name']);
        		break;
        }
        return $formData;
    }

    private function setAttributesToFormData($id, $formData)
    {
    	$event = new Application_Model_DbTable_Event();
	    $spot = new Application_Model_Spot();
		$genreEvent = new Application_Model_DbTable_GenreEvent();
		$artistEvent = new Application_Model_DbTable_ArtistEvent();
		$adminEvent = new Application_Model_DbTable_AdminEvent();
		$priceEvent = new Application_Model_DbTable_PriceEvent();
		$concours = new Application_Model_DbTable_Concours();
		
		$formData = $event->getEvent($id);
		$formData['spot'] = $spot->getSpotNameFromId($formData['idSpot']);
    	$formData['genres'] = $genreEvent->getGenreNameFromIdEvent($formData['id']);
    	
    	$formData['artists'] = $artistEvent->getArtistNameFromIdEvent($formData['id']);
    	$formData['admins'] = $adminEvent->getAdminNameFromIdEvent($formData['id']);
    	
    	/*$idPostConcours = $concours->getIdPostFromIdEvent($formData['id']);
    	if($idPostConcours >= 0){
        	if(!(is_null($idPostConcours) || trim($idPostConcours) == '')){
	        	$formData['idPost'] = $idPostConcours;
        	} else {
	        	$formData['idPost'] = '';
        	}
    	}*/
    	$formData = $this->setPricesToFormData($priceEvent->getPrices($formData['id']), $formData);
    	return $formData;
    }

    private function parseEventForEdit($formData)
    {
    	$auth = Zend_Auth::getInstance();
    	$name = $formData['name'];
        $linkFb = $formData['linkFb'];
        $publie = 0;
        $heureDebut = $formData['heureDebut'];
        $heureFin = $formData['heureFin'];
        $date = $formData['date'];
        $img = $formData['img'];
        $poster = $auth->getIdentity()->id;
        
        $data = array(
        	'name'=>$name,
        	//'idSpot'=>$idSpot,
        	'linkFb'=>$linkFb,
        	'publie'=>$publie,
        	'heureDebut'=>$heureDebut,
        	'heureFin'=>$heureFin,
        	'date'=>$date,
        	'poster'=>$poster,
        	'img'=>$img,
        );
        return $data;
    }

    private function parseEventForAdd($formData)
    {
    	$auth = Zend_Auth::getInstance();
	    $name = trim($formData['name']);
        $linkFb = $formData['linkFb'];
        $publie = 0;
        $heureDebut = $formData['start'];
        $heureFin = $formData['end'];
        $date = $formData['date'];
        $attending = 0;
        $img = $formData['img'];
        $poster = $auth->getIdentity()->id;
        
        
        $data = array(
        	'name'=>$name,
        	//'idSpot'=>$idSpot,
        	'linkFb'=>$linkFb,
        	'publie'=>$publie,
        	'heureDebut'=>$heureDebut,
        	'heureFin'=>$heureFin,
        	'date'=>$date,
        	'attending'=>$attending,
        	'poster'=>$poster,
        	'img'=>$img,
        );
	    return $data;
    }

    public function pastAction()
    {
    	$auth = Zend_Auth::getInstance();
	    $userId = $auth->getIdentity()->id;
        $events = new Application_Model_DbTable_Event();
        $this->view->pastEvents = $events->getAllEventsUserPast($userId);
        $this->view->countPastEvents = $events->getEventsUserPastCount($userId);
    }

    public function pastadminAction()
    {
    	$events = new Application_Model_DbTable_Event();
        $this->view->pastEvents = $events->getAllEventsPast();
        $this->view->countPastEvents = $events->getEventsPastCount();
    }
	
	
    
	
	

}






