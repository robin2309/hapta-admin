<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
	public function loggedInAs ()
	{
		/*$auth = Zend_Auth::getInstance();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		
		if ($auth->hasIdentity()) {
			$username = $auth->getIdentity()->username;
			$logoutUrl = $this->view->url(array('controller'=>'auth','action'=>'logout'), null, true);
			if($controller == 'auth' && $action == 'index') {
				$indexUrl = $this->view->url(array('controller'=>'index', 'action'=>'index'));
				$redirector->gotoUrl($indexUrl);
			}
			require_once('navbar.php');
			return '';
		}

		if($controller == 'auth' && $action == 'index') {
			return '';
		}
		$loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'));
		$redirector->gotoUrl($loginUrl);*/
		
	}
}

?>