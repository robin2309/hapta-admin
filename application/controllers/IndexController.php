<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$auth = Zend_Auth::getInstance();
    	$user = $auth->getIdentity();
        if($user->role == 'admin'){
	        $this->view->isAdmin = true;
	        $artist = new Application_Model_DbTable_Artist();
	        $this->view->countArtists = $artist->countAllValidArtists();
	        $spot = new  Application_Model_DbTable_Spot();
	        $this->view->countSpots = $spot->countAllValidSpots();
	        $event = new Application_Model_DbTable_Event();
	        $this->view->countEvents = $event->countAllEvents();
	        $this->view->countInvalidEvent = $event->getInvalidEventsCount();
	        $this->view->countUpcomingEvents = $event->getEventsCount();
	        $this->view->countQuickAddEvents = $event->countEventsQuickAdd();
	        $artists = new Application_Model_Artist();
	        $this->view->countInvalidArtists = $artists->countInvalid();
	        $genres = new Application_Model_Genre();
	        $this->view->countInvalidGenres = $genres->countInvalid();
	        $spots = new Application_Model_Spot();
	        $this->view->countInvalidSpots = $spots->countInvalid();
        } else {
	        $userId = $user->id;
	        $event = new Application_Model_Event();
	        $countEventFromUser = $event->countEventFromUser($userId);
	        $sumAttendingFromUser = $event->sumAttendingFromUserEvents($userId);
	        $this->view->countEventFromUser = $countEventFromUser;
	        if(is_null($sumAttendingFromUser)){
		        $this->view->sumAttendingFromUser = 0;
	        } else {
		        $this->view->sumAttendingFromUser = $sumAttendingFromUser;
	        }
	        if($countEventFromUser > 0){
		        $this->view->averageAttending = round($sumAttendingFromUser/$countEventFromUser,0);
	        } else {
		        $this->view->averageAttending = 0;
	        }
	        $this->view->mostAttendedEvents = $event->getThreeMostAttendedFromUser($userId);
        }
	    $msg = $this->_getParam('msg',0);
        if($msg > 0){
        	if($msg == 1){
	        	$this->view->msgAddedEvent = 1;
        	} elseif($msg == 2){
	        	$this->view->msgErrorAddEvent = 1;
        	} elseif($msg == 3){
	        	$this->view->msgExistingEvent = 1;
        	}
        }
    }

    public function addAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function addAdminAction()
    {
        // action body
    }


}
