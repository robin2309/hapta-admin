<?php

class ArtistController extends Zend_Controller_Action
{

    public function init()
    {
    }

    private function isValid($data)
    {
    	return true;
    	/*if(!isset($data['idArtist'])){
		    return false;
	    }
	    if(!isset($data['name'])){
		    return false;
	    }
	    return true;*/
    }

    public function indexAction()
    {
        $artists = new Application_Model_DbTable_Artist();
        $this->view->artists = $artists->getAllValidArtists();
        $this->view->lastArtists = $artists->getLastAddedArtists();
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
		        $artist = array(
		        	'name'=>$formData['name'],
		        	'label'=>$formData['label'],
		        	'valide'=>0,
		        	'country'=>$formData['country'],
		        );
		        $artists = new Application_Model_DbTable_Artist();
		        if($artists->addArtist($artist) < 0){
		        	$this->redirect('/artist/index/msg/1');
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
        if($this->getRequest()->isPost()){
        	$delete = $this->getRequest()->getPost('del');
        	if($delete == 'Oui'){
		        $id = $this->getRequest()->getPost('id');
		        $artists = new Application_Model_DbTable_Artist();
		        $artists->deleteArtist($id);	
        	}
		    $this->_helper->redirector('indexAdmin');    
        } else {
        	$id = $this->_getParam('id',0);
        	$artists = new Application_Model_DbTable_Artist();
        	$this->view->artist = $artists->getArtist($id);
        }
		 $this->_helper->getHelper('layout')->disableLayout();
    }

    public function validateAction()
    {
        $valid = $this->_getParam('valid',-1);
        $id = $this->_getParam('id', 0);
        if($id > 0){
	        if($valid == 0 || $valid == 1){
	    		$artist = new Application_Model_DbTable_Artist();
	    		$data = array('valide'=>$valid);
	    		$artist->updateArtist($id, $data);
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
	        if($this->isValid($formData)){
	        	$id = $formData['idArtist'];
		        $spots = new Application_Model_DbTable_Artist();
		        $data = array(
		        	'changeRequest'=>$formData['changeRequest'],
		        );
		        $spots->updateArtist($id,$data);
		        //$this->view->messageModif = "Club edite";
		        $this->_helper->redirector('index');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$artists = new Application_Model_DbTable_Artist();
        		$this->view->dataPopulate = $artists->getArtist($id);
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
		        $artist = array(
		        	'name'=>$formData['name'],
		        	'label'=>$formData['label'],
		        	'valide'=>0,
		        	'changeRequest'=>$formData['changeRequest'],
		        	'country'=>$formData['country'],
		        );
		        $artists = new Application_Model_DbTable_Artist();
		        
		        if($artists->addArtist($artist) < 0){
		        	$this->redirect('/artist/indexAdmin/msg/1');
		        } else {
			        $this->_helper->redirector('indexAdmin');
		        }
	        } else {
	        	$this->view->dataPopulate = $formData;
		        //$form->populate($formData);
	        }
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }

    public function indexadminAction()
    {
        $artists = new Application_Model_DbTable_Artist();
        $this->view->artistsValid = $artists->getAllValidArtists();
        $this->view->artistsModifReq = $artists->getAllModifRequestArtists();
        $this->view->artistsInvalid = $artists->getAllInvalidArtists();
        $msg = $this->_getParam('msg',0);
		if($msg == 1){
			$this->view->notInsertable = 1;
		}
    }

    public function getcachedartistsAction()
    {
        //$request = $this->getRequest();
        //if($request-> isXmlHttpRequest()){
        	//retrieve cache
		$cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'artists';
		$this->_helper->getHelper('layout')->disableLayout();
		if(false === ($artists = $cache->load($cacheId))){
			$artist = new Application_Model_DbTable_Artist();
			$artists = $artist->cacheAllValidArtists();
			$artistsJson = json_encode($artists);
			$cache->save($artistsJson, $cacheId);
			$this->view->artists = $artistsJson;
		} else {
			$this->view->artists = $artists;
		}
        //}else{
	        //throw new Zend_Controller_Action_Exception('Oops, erreur !!', 404);
        //} 
    }

    public function addfromeventAction()
    {
        $request = $this->getRequest();
    	if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $artist = array(
		        	'name'=>$formData['name'],
		        	'label'=>$formData['label'],
		        	'valide'=>1,
		        	'country'=>$formData['country'],
		        );
		        $artists = new Application_Model_DbTable_Artist();
		        $this->_helper->viewRenderer->setNoRender(true);
		        if($artists->addArtist($artist) < 0){
			        echo "INSERTION->Erreur";
		        } else {
			        echo $formData['name'];
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
	        	$id = $formData['idArtist'];
		        $name = $formData['name'];
		        $label = $formData['label'];
		        $changeRequest = $formData['changeRequest'];
		        $country = $formData['country'];
		        $artists = new Application_Model_DbTable_Artist();
		        $data = array(
		        	'name'=>$name,
		        	'label'=>$label,
		        	'changeRequest'=>$changeRequest,
		        	'country'=>$country,
		        );
		        if($artists->updateArtist($id,$data) < 0){
		        	$this->redirect('/artist/indexAdmin/msg/1');
		        } else {
			        $this->_helper->redirector('indexAdmin');
		        }
	        } else {
	        	$this->view->dataPopulate = $formData;
		        //$form->populate($formData);
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$artists = new Application_Model_DbTable_Artist();
        		$this->view->dataPopulate = $artists->getArtist($id);     
	        }
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }


}




