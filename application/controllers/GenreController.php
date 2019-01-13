<?php

class GenreController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    private function isValid($data)
    {
	    /*if(!isset($data['idGenre'])){
		    return false;
	    }
	    if(!isset($data['nameGenre'])){
		    return false;
	    }*/
	    return true;
    }

    public function indexAction()
    {
        $genres = new Application_Model_DbTable_Genre();
        $this->view->genres = $genres->getAllValidGenres();
        $this->view->lastGenres = $genres->getLastAddedGenres();
        $msg = $this->_getParam('msg', 0);
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
		        $genre = array(
		        	'nameGenre' => $formData['nameGenre'],
		        	'valide' => 0,
		        );
		        
		        $genres = new Application_Model_DbTable_Genre();
		        if($genres->addGenre($genre) < 0){
			        $this->redirect('/genre/index/msg/1');
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
		        $id = $this->getRequest()->getPost('idGenre');
		        $genres = new Application_Model_DbTable_Genre();
		        $genres->deleteGenre($id);	
		    }
			
		    $this->_helper->redirector('indexAdmin');    
			
        } else {
        	$id = $this->_getParam('id',0);
        	$genres = new Application_Model_DbTable_Genre();
        	$this->view->genre = $genres->getGenre($id);
        }
		$this->_helper->getHelper('layout')->disableLayout();	
    }

    public function changerequestAction()
    {
    	$request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
	        	$id = $formData['idGenre'];
		        $genres = new Application_Model_DbTable_Genre();
		        $data = array(
		        	'changeRequest'=>$formData['changeRequest'],
		        );
		        $genres->updateGenre($id,$data);
		        //$this->view->messageModif = "Club edite";
		        $this->_helper->redirector('index');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$genres = new Application_Model_DbTable_Genre();
        		$this->view->dataPopulate = $genres->getGenre($id);
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
		        $data = array(
		        	'nameGenre' => $formData['nameGenre'],
		        	'valide' => 0,
		        );
		        
		        $genres = new Application_Model_DbTable_Genre();
		        
		        if($genres->addGenre($data) < 0){
			        $this->redirect('/genre/indexAdmin/msg/1');
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
		$genres = new Application_Model_DbTable_Genre();
        $this->view->genresNonValides = $genres->getAllInvalidGenres();
        $this->view->genresValides = $genres->getAllValidGenres();
        $this->view->genresModifReq = $genres->getAllModifRequestGenres();
        $msg = $this->_getParam('msg', 0);
        if($msg == 1){
			$this->view->notInsertable = 1;
		}
    }

    public function validateAction()
    {
        $valid = $this->_getParam('valid',-1);
        $id = $this->_getParam('id', 0);
        if($id > 0){
	        if($valid == 0){
	    		$genre = new Application_Model_DbTable_Genre();
	    		$data = array('valide'=>0);
	    		$genre->updateGenre($id, $data);
	    		$this->_helper->redirector('indexAdmin');
    		} elseif($valid == 1){
		    	$genre = new Application_Model_DbTable_Genre();
	    		$data = array('valide'=>1);
	    		$genre->updateGenre($id, $data);
	    		$this->_helper->redirector('indexAdmin');
    		} else {
		    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		    }
        } else {
		    throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	    }
    }

    public function getcachedgenresAction()
    {
        $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'genres';
		$this->_helper->getHelper('layout')->disableLayout();
		if(false === ($genres = $cache->load($cacheId))){
			$genre = new Application_Model_DbTable_Artist();
			$genres = $genre->cacheAllValidGenres();
			$genresJson = json_encode($genres);
			$cache->save($genresJson, $cacheId);
			$this->view->genres = $genresJson;
		} else {
			$this->view->genres = $genres;
		}
    }

    public function addfromeventAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
		        $data = array(
		        	'nameGenre' => $formData['nameGenre'],
		        	'valide' => 1,
		        );
		        $genres = new Application_Model_DbTable_Genre();
		        $this->_helper->viewRenderer->setNoRender(true);		        
		        if($genres->addGenre($data) < 0){
			        echo "INSERTION->Erreur";
		        } else {
			        echo $formData['nameGenre'];
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
	        	$id = $formData['idGenre'];
		        $genres = new Application_Model_DbTable_Genre();
		        $data = array(
		        	'nameGenre'=>$formData['nameGenre'],
		        	'changeRequest'=>$formData['changeRequest'],
		        );
		        if($genres->updateGenre($id,$data) < 0){
			        $this->redirect('/genre/indexAdmin/msg/1');
		        } else {
			        $this->_helper->redirector('indexAdmin');
		        }
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
        } else {
	        $id = $this->_getParam('id',0);
	        if($id > 0){
        		$genres = new Application_Model_DbTable_Genre();
        		$this->view->dataPopulate = $genres->getGenre($id);	       
	        }
        }
		$this->_helper->getHelper('layout')->disableLayout();
    }


}



