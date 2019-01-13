<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initDocType(){
		$this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
	}
	
	protected function _initLocation(){
		//$config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$customParams = $this->getOption('custom');
		$city = $customParams['location']['city'];
		$sitename = $customParams['location']['sitename'];
		$fbAppId = $customParams['location']['social']['facebook']['appId'];
		$fbAppSecret = $customParams['location']['social']['facebook']['appSecret'];
		$fbAccessToken = $customParams['location']['social']['facebook']['accessToken'];
		$analyticsId = $customParams['location']['google']['analytics']['id'];
		Zend_Registry::set('City_Location', $city);
		Zend_Registry::set('Sitename', $sitename);
		Zend_Registry::set('Fb_App_Id', $fbAppId);
		Zend_Registry::set('Fb_App_Secret', $fbAppSecret);
		Zend_Registry::set('Fb_App_Token', $fbAccessToken);
		Zend_Registry::set('Analytics_Id', $analyticsId);
	}
	
    protected function _initPlugins()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('My_');
 
        $objFront = Zend_Controller_Front::getInstance();
        $objFront->registerPlugin(new My_Controller_Plugin_ACL(), 1);
        return $objFront;
    }
    
    protected function _initRegisterLogger() {
    	$this->bootstrap('Log');

    	if (!$this->hasPluginResource('Log')) {
        	throw new Zend_Exception('Log not enabled in config.ini');
        }

        $logger = $this->getResource('Log');
        
        assert($logger != null);
        Zend_Registry::set('Zend_Log', $logger);
    }
    
    protected function _initCache(){
	    $cacheManager = new Zend_Cache_Manager();
		$frontendOptions = array(
        	'lifetime' => 999999999,
        	'automatic_serialization' => true
        	);
        $backendOptions = array(
        	'cache_dir' => APPLICATION_PATH . '/../data/cache'
        	);
        	
        $coreCache = Zend_Cache::factory(
        	'Core',
        	'File',
        	$frontendOptions,
        	$backendOptions
        	);
        $cacheManager->setCache('coreCache', $coreCache);
        
        $pageCache = Zend_Cache::factory(
            'Page', 
            'File', 
            $frontendOptions, 
            $backendOptions
            );
        $cacheManager->setCache('pageCache', $pageCache);
        
        Zend_Registry::set('cacheMan', $cacheManager);
		return $cacheManager;
    }

}

