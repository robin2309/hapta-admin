<?php

class UserController extends Zend_Controller_Action
{

	private function isValid($data){
		return true;
	}

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $user = new Application_Model_User();
        $this->view->users = $user->getAllUsers();
    }

    public function getcachedusersAction()
    {
        $cacheManager =  Zend_Registry::get('cacheMan');
		$cache = $cacheManager->getCache('coreCache');
		$cacheId = 'users';
		$this->_helper->getHelper('layout')->disableLayout();
		//if(false === ($users = $cache->load($cacheId))){
			$user = new Application_Model_DbTable_User();
			$users = $user->cacheAllUsers();
			$usersJson = json_encode($users);
			$cache->save($usersJson, $cacheId);
			$this->view->users = $usersJson;
		//} else {
			//$this->view->users = $genres;
		//}
    }

    public function addAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
	        $formData = $request->getPost();
	        if($this->isValid($formData)){
	        	if($formData['admin']){
		        	$role = 'admin';
	        	} else {
		        	$role = 'user';
	        	}
	        	$data = array(
	        		'username'=>$formData['username'],
	        		'password'=>sha1($formData['password']),
	        		'role'=>$role,
	        		'email'=>$formData['email'],
	        	);
	        	$user = new Application_Model_User();
	        	$user->insertUser($data);
	        	$this->_helper->redirector('index');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
	    }
	    $this->_helper->getHelper('layout')->disableLayout();

    }

    public function editAction()
    {
        $request = $this->getRequest();
        $auth = Zend_Auth::getInstance();
    	if($auth->hasIdentity()) {
        	$user = $auth->getIdentity();
        	$userId = $user->id;
	        if($request->isPost()){
		        $formData = $request->getPost();
		        if($this->isValid($formData)){
		        	if($formData['admin']){
			        	$role = 'admin';
		        	} else {
			        	$role = 'user';
		        	}
		        	$id = $formData['id'];
		        	if(empty($formData['password'])){
		        		$data = array(
		        			'username'=>$formData['username'],
		        			'role'=>$role,
		        			'email'=>$formData['email'],
		        		);
		        	} else {
			        	$data = array(
		        			'username'=>$formData['username'],
		        			'password'=>sha1($formData['password']),
		        			'role'=>$role,
		        			'email'=>$formData['email'],
		        		);
		        	}
		        	if($userId == $id){
			        	$user = new Application_Model_User();
			        	$user->editUser($id, $data);
			        	$this->_helper->redirector('index');
		        	} else {
			        	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		        	}
		        } else {
			        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		        }
		    } else {
			    $id = $this->_getParam('id',0);
		        if($id > 0 && $id == $userId){
	        		$user = new Application_Model_User();
	        		$this->view->dataPopulate = $user->getUser($id);	       
		        } else {
			        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		        }
		    }
        } else {
	        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
        }
        
	    //$this->_helper->getHelper('layout')->disableLayout();
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
        	$delete = $this->getRequest()->getPost('del');
	        if($delete == 'Oui'){
		        $id = $this->getRequest()->getPost('id');
		        $user = new Application_Model_User();
		        $user->deleteUser($id);	
		    }
		    $this->_helper->redirector('index');
	    } else {
		    $id = $this->_getParam('id',0);
	        if($id > 0){
        		$user = new Application_Model_User();
        		$this->view->user = $user->getUser($id);	       
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
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
	        	if($formData['admin']){
		        	$role = 'admin';
	        	} else {
		        	$role = 'user';
	        	}
	        	$id = $formData['id'];
	        	if(empty($formData['password'])){
		        	$data = array(
	        			'username'=>$formData['username'],
	        			'role'=>$role,
	        			'email'=>$formData['email'],
	        		);
	        	} else {
		        	$data = array(
	        			'username'=>$formData['username'],
	        			'password'=>sha1($formData['password']),
	        			'role'=>$role,
	        			'email'=>$formData['email'],
	        		);
	        	}
	        	
	        	$user = new Application_Model_User();
	        	$user->editUser($id, $data);
	        	$this->_helper->redirector('index');
	        } else {
		        $this->view->dataPopulate = $formData;
	        }
	    } else {
		    $id = $this->_getParam('id',0);
	        if($id > 0){
        		$user = new Application_Model_User();
        		$this->view->dataPopulate = $user->getUser($id);	       
	        } else {
		        throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
	        }
	    }
	    $this->_helper->getHelper('layout')->disableLayout();
    }


}











