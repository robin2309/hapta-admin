<?php

class ConcoursController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    private function isValid($data)
    {
	    /*if(!isset($data['idEvent'])){
		    return false;
	    }
	    if(!isset($data['idConcours'])){
		    return false;
	    }
	    if(!isset($data['dateDeb'])){
		    return false;
	    }
	    if(!isset($data['dateFin'])){
		    return false;
	    }
	    if(!isset($data['nbPlaces'])){
		    return false;
	    }
	    if(!isset($data['imgConcours'])){
		    return false;
	    }*/
	    return true;
    }

    public function indexAction()
    {
    	$auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();
        $userId = $user->id;
        $concours = new Application_Model_DbTable_Concours();
        $this->view->concours = $concours->getAllConcoursFromUser($userId);
    }

    public function addAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $request = $this->getRequest();
        
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
	        	$dateDeb = date($formData['dateDeb'] . " " . $formData['heureDeb'].":00");
	        	$dateFin = date($formData['dateFin'] . " " . $formData['heureFin'].":00");
		        $data = array(
		        	'idEvent'=>$formData['idEvent'],
		        	'dateDeb'=>$dateDeb,
		        	'dateFin'=>$dateFin,
		        	'nbPlaces'=>$formData['nbPlaces'],
		        	'nbGagnants'=>$formData['nbGagnants'],
		        );
		        $events = new Application_Model_DbTable_Event();
		        $events->checkUserOwnEvent($formData['idEvent'], $userId);
		        $concours = new Application_Model_DbTable_Concours();
		        $newlyInsertedId = $concours->addConcours($data);
		        $concoursObj = new Application_Model_Concours();
		        $concoursObj->downloadConcoursImage($formData['idEvent'], $newlyInsertedId);
		        $this->_helper->redirector('index', 'event');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){	        	
	        	$events = new Application_Model_DbTable_Event();
	        	$events->checkUserOwnEvent($id, $userId);
	        	$event = $events->getEventNameDate($id);
	        	$formData = array('idEvent'=>$id, 'infoEvent'=>$event['name'], 'dateEvent'=>$event['date']);
		        $this->view->dataPopulate = $formData;
		        $this->_helper->getHelper('layout')->disableLayout();
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	        }
        }
    }

    public function editAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;

        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        if ($auth->hasIdentity()) {
			        $dateDeb = date($formData['dateDeb'] . " " . $formData['heureDeb'].":00");
		        	$dateFin = date($formData['dateFin'] . " " . $formData['heureFin'].":00");
			        $data = array(
			        	//'idEvent'=>$formData['idEvent'],
			        	'dateDeb'=>$dateDeb,
			        	'dateFin'=>$dateFin,
			        	'nbPlaces'=>$formData['nbPlaces'],
			        	'nbGagnants'=>$formData['nbGagnants'],
			        );
			        $id = $formData['idConcours'];
			        
			        $concours = new Application_Model_DbTable_Concours();
			        $concours->checkUserOwnConcours($id,$userId);
			        $concours->updateConcours($id, $data);
			        //$this->view->messageModif = "Event ajouté";
		        }
		        $this->_helper->redirector('index', 'event');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$concours = new Application_Model_DbTable_Concours();
        		$concours->checkUserOwnConcours($id,$userId);
        		$data = $concours->getConcoursEdit($id);
        		$data['nameEvent'] = $data['name'];
        		$data['dateEvent'] = $data['date'];
        		$dateDebComplete = strtotime($data['dateDeb']);
        		$dateFinComplete = strtotime($data['dateFin']);
        		$data['dateDeb'] = date('Y-m-d', $dateDebComplete);
        		$data['heureDeb'] = date('H:i', $dateDebComplete);
        		$data['dateFin'] = date('Y-m-d', $dateFinComplete);
        		$data['heureFin'] = date('H:i', $dateFinComplete);
        		$this->view->dataPopulate = $data;
        		$this->_helper->getHelper('layout')->disableLayout();
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur', 500);
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
		        $id = $this->_getParam('id',0);
		        $concours = new Application_Model_DbTable_Concours();
		        $concours->checkUserOwnConcours($id,$userId);
		        $concours->deleteConcours($id);
        	}
		    $this->_helper->redirector('index', 'event');
        } else {
        	$id = $this->_getParam('id',0);
        	$concours = new Application_Model_DbTable_Concours();
        	$concours->checkUserOwnConcours($id,$userId);
        	$this->view->concours = $concours->getConcoursEdit($id);
        	$this->_helper->getHelper('layout')->disableLayout();
        }
    }

    public function draftAction()
    {
    	$auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$concours = new Application_Model_DbTable_Concours();
        		$concours->checkUserOwnConcours($id, $userId);
        		$data = array('publie'=>0);
        		$concours->changeStateConcours($id, $data);
        		$this->_helper->redirector('index');
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function draftadminAction()
    {
    	$auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$concours = new Application_Model_DbTable_Concours();
        		$data = array('publie'=>0);
        		$concours->changeStateConcours($id, $data);
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
        		$concours = new Application_Model_DbTable_Concours();
        		$concours->checkUserOwnConcours($id, $userId);
        		$data = array('publie'=>1);
        		$concours->updateConcours($id, $data);
        		$this->_helper->redirector('index');
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
        		$concours = new Application_Model_DbTable_Concours();
        		$data = array('publie'=>1);
        		$concours->changeStateConcours($id, $data);
        		$this->_helper->redirector('indexAdmin');
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function indexadminAction()
    {
    	$concours = new Application_Model_Concours();
        $this->view->invalidConcours = $concours->getInvalidConcours();
        $this->view->upcomingConcours = $concours->getUpcomingConcours();
        $this->view->currentConcours = $concours->getCurrentConcours();
        $this->view->pastConcours = $concours->getPastConcours();
        //$this->view->
        
    }

    public function deleteadminAction()
    {
	    $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
	    if($this->getRequest()->isPost()){
        	$delete = $this->getRequest()->getPost('del');
        	if($delete == 'Oui'){
		        $id = $this->_getParam('id',0);
		        $concours = new Application_Model_DbTable_Concours();
		        $concours->deleteConcours($id);
        	}
		    $this->_helper->redirector('indexAdmin');
        } else {
        	$id = $this->_getParam('id',0);
        	$concours = new Application_Model_DbTable_Concours();
        	$this->view->concours = $concours->getConcoursEdit($id);
        	$this->_helper->getHelper('layout')->disableLayout();
        }
    }

    public function editadminAction()
    {
	    $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;

        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        if ($auth->hasIdentity()) {
			        $dateDeb = date($formData['dateDeb'] . " " . $formData['heureDeb'].":00");
		        	$dateFin = date($formData['dateFin'] . " " . $formData['heureFin'].":00");
			        $data = array(
			        	//'idEvent'=>$formData['idEvent'],
			        	'dateDeb'=>$dateDeb,
			        	'dateFin'=>$dateFin,
			        	'nbPlaces'=>$formData['nbPlaces'],
			        	'nbGagnants'=>$formData['nbGagnants'],
			        );
			        $id = $formData['idConcours'];
			        
			        $concours = new Application_Model_DbTable_Concours();
			        $concours->updateConcours($id, $data);
			        //$this->view->messageModif = "Event ajouté";
		        }
		        $this->_helper->redirector('indexAdmin');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$concours = new Application_Model_DbTable_Concours();
        		$data = $concours->getConcoursEdit($id);
        		$data['nameEvent'] = $data['name'];
        		$data['dateEvent'] = $data['date'];
        		$dateDebComplete = strtotime($data['dateDeb']);
        		$dateFinComplete = strtotime($data['dateFin']);
        		$data['dateDeb'] = date('Y-m-d', $dateDebComplete);
        		$data['heureDeb'] = date('H:i', $dateDebComplete);
        		$data['dateFin'] = date('Y-m-d', $dateFinComplete);
        		$data['heureFin'] = date('H:i', $dateFinComplete);
        		$this->view->dataPopulate = $data;
        		$this->_helper->getHelper('layout')->disableLayout();
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur', 500);
	        }
        }
    }

    public function addadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $request = $this->getRequest();
        
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
	        	$dateDeb = date($formData['dateDeb'] . " " . $formData['heureDeb'].":00");
	        	$dateFin = date($formData['dateFin'] . " " . $formData['heureFin'].":00");
		        $data = array(
		        	'idEvent'=>$formData['idEvent'],
		        	'dateDeb'=>$dateDeb,
		        	'dateFin'=>$dateFin,
		        	'valide'=>1,
		        	'nbPlaces'=>$formData['nbPlaces'],
		        	'nbGagnants'=>$formData['nbGagnants'],
		        );
		        $events = new Application_Model_DbTable_Event();
		        $events->checkUserOwnEvent($formData['idEvent'], $userId);
		        $concours = new Application_Model_DbTable_Concours();
		        $newlyInsertedId = $concours->addConcours($data);
		        $concoursObj = new Application_Model_Concours();
		        $concoursObj->downloadConcoursImage($formData['idEvent'], $newlyInsertedId);
		        $this->_helper->redirector('indexAdmin');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){	        	
	        	$events = new Application_Model_DbTable_Event();
	        	$events->checkUserOwnEvent($id, $userId);
	        	$event = $events->getEventNameDate($id);
		        $formData = array('idEvent'=>$id, 'infoEvent'=>$event['name'], 'dateEvent'=>$event['date']);
		        $this->view->dataPopulate = $formData;
		        $this->_helper->getHelper('layout')->disableLayout();
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	        }
        }
    }

    public function validadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$concours = new Application_Model_DbTable_Concours();
        		$data = array('valide'=>1);
        		$concours->changeStateConcours($id, $data);
        		$this->_helper->redirector('indexAdmin');
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function unvalidadminAction()
    {
        $auth = Zend_Auth::getInstance();
        $userId = $auth->getIdentity()->id;
        $id = $this->_getParam('id',0);
        if($id > 0){
        		$concours = new Application_Model_DbTable_Concours();
        		$data = array('valide'=>0);
        		$concours->changeStateConcours($id, $data);
        		$this->_helper->redirector('indexAdmin');
	    } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function tirageAction()
    {
        // action body
    }


}











