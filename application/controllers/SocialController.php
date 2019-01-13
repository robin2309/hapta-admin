<?php

class SocialController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
        $instagram = new My_Instagram('b4c7ca21ea95479da30dee61ed326fc1');
		$tag = 'hapta';
		// Get recently tagged media
		$this->view->media = $instagram->getTagMedia($tag);
		$this->view->tag = $tag;
    }

    public function loadmorepictureinstagramAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        
		// Initialize class for public requests
		$instagram = new My_Instagram('b4c7ca21ea95479da30dee61ed326fc1');
		
		// Receive AJAX request and create call object
		
		$tag = $this->_getParam('tag', '');
		$maxID = $this->_getParam('max_id', 0);
		if($tag !== '' && $maxID !== 0){
			$clientID = $instagram->getApiKey();
			
			$call = new stdClass;
			$call->pagination->next_max_id = $maxID;
			$call->pagination->next_url = "https://api.instagram.com/v1/tags/{$tag}/media/recent?client_id={$clientID}&max_tag_id={$maxID}";
			
			// Receive new data
			$media = $instagram->getTagMedia($tag,$auth=false,array('max_tag_id'=>$maxID));
			
			// Collect everything for json output
			$images = array();
			foreach ($media->data as $data) {
				$images[] = $data->user->profile_picture.",".$data->user->username.",".$data->link.",".$data->images->standard_resolution->url.",".$data->caption->text;
			}
			
			$this->_helper->json->sendJson(array('next_id'=>$media->pagination->next_max_id, 'images'=>$images));
		}
		
    }
}



