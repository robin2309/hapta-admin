<?php

class My_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract{
	
	protected $_defaultRole = 'guest';
	
	public function preDispatch(Zend_Controller_Request_Abstract $request){
		
		$auth = Zend_Auth::getInstance();
		$acl = new My_Acl();
		$mysession = new Zend_Session_Namespace('mysession');
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$resource = $controller . '::' . $action;
		
		if($auth->hasIdentity()) {
			$user = $auth->getIdentity();
			if(!($acl->has($resource))){
				throw new Zend_Controller_Action_Exception('This page does not exist', 404);
            } else {
	        	if(!$acl->isAllowed($user->role, $resource)){
	                $mysession->destination_url = $request->getPathInfo();
	                return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoUrl('auth/denied');
	            }
            }
        } else {
        	if(!($acl->has($resource))){
				throw new Zend_Controller_Action_Exception('This page does not exist', 404);
            } else {
		        if(!$acl->isAllowed($this->_defaultRole, $request->getControllerName() . '::' . $request->getActionName())) {
			        $mysession->destination_url = $request->getPathInfo();
			        return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->setGotoUrl('auth/index');
	            }
	        }
        }
	}
	
}