<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
    private function isValid($data){
	    if(!isset($data['username'])){
		    return false;
	    }
	    if(!isset($data['password'])){
		    return false;
	    }
	    return array('username' => $data['username'], 'password' => $data['password']);
    }

    public function indexAction()
    {
        //$form = new Application_Form_Login();
		$request = $this->getRequest();
		if ($request->isPost()) {
			if ($values = $this->isValid($request->getPost())) {
				if ($this->_process($values)) {
					// Redirect to the home page
					$this->_helper->redirector('index', 'index');
				} else {
					$this->view->errorMsg = true;
				}
			}
		}
    }

    protected function _process($values)
    {
		// Get our authentication adapter and check credentials
		$adapter = $this->_getAuthAdapter();
		$adapter->setIdentity(strlen($values['username'])==0 ? " " : $values['username']);
		$adapter->setCredential(strlen($values['password'])==0 ? " " : $values['password']);
		
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($adapter);
		if ($result->isValid()) {
			$user = $adapter->getResultRowObject();
			$auth->getStorage()->write($user);
			return true;
		}
		return false;
    }

    protected function _getAuthAdapter()
    {
	
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		
		$authAdapter->setTableName('hapta_user')
					->setIdentityColumn('username')
					->setCredentialColumn('password')
					->setCredentialTreatment('SHA1(?)');
		
		return $authAdapter;
    }

    public function logoutAction()
    {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('index'); // back to login page
    }

    public function deniedAction()
    {
        $this->view->message = 'Permission insuffisante';
    }


}







