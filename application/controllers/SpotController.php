<?php

class SpotController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    private function isValid($data)
    {
	    /*if(!isset($data['id'])){
		    return false;
	    }
	    if(!isset($data['name'])){
		    return false;
	    }
	    if(!isset($data['city'])){
		    return false;
	    }
	    if(!isset($data['address'])){
		    return false;
	    }*/
	    return true;
    }

    private function isValidChangeRequest($data)
    {
	    if(!isset($data['id'])){
		    return false;
	    }
	    if(!isset($data['name'])){
		    return false;
	    }
	    if(!isset($data['changeRequest'])){
		    return false;
	    }
	    return true;
    }

    public function indexAction()
    {
        $spots = new Application_Model_DbTable_Spot();
        $this->view->spots = $spots->getAllValidSpots();
        $this->view->lastSpots = $spots->getLastAddedSpots();
        $msg = $this->_getParam('msg',0);
		if($msg == 1){
			$this->view->notInsertable = 1;
		}
    }

    public function addAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $name = $formData['name'];
		        $city = $formData['city'];
		        $address = $formData['address'];
		        $spots = new Application_Model_DbTable_Spot();
		        $data = array(
		        	'name'=>$name,
		        	'city'=>$city,
		        	'address'=>$address,
		        	'valide'=>0,
		        );
		        
		        if($spots->addSpot($data) < 0){
			        $this->redirect('/spot/index/msg/1');
		        } else {
			        $this->_helper->redirector('index');			        
		        }
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }

    public function deleteAction()
    {
    	//CHECK ADMIN
        if($this->getRequest()->isPost()){
        	$delete = $this->getRequest()->getPost('del');
        	if($delete == 'Oui'){
		        $id = $this->getRequest()->getPost('id');
		        $spots = new Application_Model_DbTable_Spot();
		        $spots->deleteSpot($id);	
        	}
		    $this->_helper->redirector('indexAdmin');    
        } else {
        	$id = $this->_getParam('id',0);
        	$spots = new Application_Model_DbTable_Spot();
        	$this->view->spot = $spots->getSpot($id);
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }


    public function validateAction()
    {
        $valid = $this->_getParam('valid',-1);
        $id = $this->_getParam('id', 0);
        if($id > 0){
	        if($valid == 0 || $valid == 1){
	    		$genre = new Application_Model_DbTable_Spot();
	    		$data = array('valide'=>$valid);
	    		$genre->updateSpot($id, $data);
	    		$this->_helper->redirector('indexAdmin');
    		} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		    }
        } else {
		    throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function changerequestAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValidChangeRequest($formData)){
	        	$id = $formData['id'];
		        $spots = new Application_Model_DbTable_Spot();
		        $data = array(
		        	'changeRequest'=>$formData['changeRequest'],
		        );
		        $spots->updateSpot($id,$data);
		        //$this->view->messageModif = "Club edite";
		        $this->_helper->redirector('index');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$spots = new Application_Model_DbTable_Spot();
        		$this->view->dataPopulate = $spots->getSpot($id);
        		$this->_helper->getHelper('layout')->disableLayout();
	        }
        }
    }

    public function addadminAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $name = $formData['name'];
		        $city = $formData['city'];
		        $address = $formData['address'];
		        $spots = new Application_Model_DbTable_Spot();
		        $data = array(
		        	'name'=>$name,
		        	'city'=>$city,
		        	'address'=>$address,
		        	'valide'=>0,
		        );
		        
		        if($spots->addSpot($data) < 0){
			        $this->redirect('/spot/indexAdmin/msg/1');
		        } else {
			        $this->_helper->redirector('indexAdmin');			        
		        }
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }

    public function indexadminAction()
    {
        $spots = new Application_Model_DbTable_Spot();
        $this->view->spotsValid = $spots->getAllValidSpots();
        $this->view->spotsModifReq = $spots->getAllModifRequestSpots();
        $this->view->spotsInvalid = $spots->getAllInvalidSpots();
        $msg = $this->_getParam('msg',0);
		if($msg == 1){
			$this->view->notInsertable = 1;
		}
    }

    public function getcachedspotsAction()
    {
        $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'spots';
		$this->_helper->getHelper('layout')->disableLayout();
		if(false === ($spots = $cache->load($cacheId))){
			$spot = new Application_Model_DbTable_Spot();
			$spots = $spot->cacheAllValidSpots();
			$spotsJson = json_encode($spots);
			$cache->save($spotsJson, $cacheId);
			$this->view->spots = $spotsJson;
		} else {
			$this->view->spots = $spots;
		}
    }

    public function addfromeventAction()
    {
	    $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $name = $formData['name'];
		        $city = $formData['city'];
		        $address = $formData['address'];
		        $spots = new Application_Model_DbTable_Spot();
		        $data = array(
		        	'name'=>$name,
		        	'city'=>$city,
		        	'address'=>$address,
		        	'valide'=>1,
		        );
		        $this->_helper->viewRenderer->setNoRender(true);
		        if($spots->addSpot($data) < 0){
		        	echo "INSERTION->Erreur";
		        } else {
			    	echo $name;
		        }
	        } else {
	        	$this->view->dataPopulate = $formData;
	        }
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }

    public function editadminAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
	        	$id = $formData['id'];
		        $name = $formData['name'];
		        $city = $formData['city'];
		        $address = $formData['address'];
		        $changeRequest = $formData['changeRequest'];
		        $spots = new Application_Model_DbTable_Spot();
		        $data = array(
		        	'name'=>$name,
		        	'city'=>$city,
		        	'address'=>$address,
		        	'changeRequest'=>$changeRequest,
		        );
		        if($spots->updateSpot($id,$data)){
			        $this->redirect('/spot/indexAdmin/msg/1');
		        } else {
			        $this->_helper->redirector('indexAdmin');
		        }
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$spots = new Application_Model_DbTable_Spot();
        		$this->view->dataPopulate = $spots->getSpot($id);
	        }

        }
		$this->_helper->getHelper('layout')->disableLayout();
    }


}


