<?php
require_once(realpath(dirname(__FILE__)."/../displayFunctions.php"));
require_once(realpath(dirname(__FILE__)."/../accessModel.simpleClass.php"));
require_once(realpath(dirname(__FILE__)."/../host.php"));
require_once(realpath(dirname(__FILE__)."/../php-sdk/facebook.php")); require_once(realpath(dirname(__FILE__)."/../conf/params_app_fb.php"));

// $config est maintenant defini dans params_app_fb.php
$fb = new Facebook($config);
$todayEvents = $model->getTodayTopThree();

if(count($todayEvents) >= 3){
	$postFb = "Le top 3 de ce soir parmis ". count($todayEvents)." events sur www.".$ville.".fr :\n";
	foreach($todayEvents as $key => $event) {
		if($key > 2){
			break;
		}
		$postFb .= "> ".$event['name']." - ".$event["nameSpot"]."\n- ";
		$artists = $model->getArtistFromConcours($event["id"]);
		foreach($artists as $key => $artist){
			if($key == count($artists)-1){
				$postFb .= $artist["nameArtist"]."\n";
			} else {
				$postFb .= $artist["nameArtist"].", ";
			}
		}
		//$postFb .= "Event : " . $event["linkFb"]."\n\n";
		$postFb .= "Event : http://$ville.fr/events/detail/id/".$event["id"]."\n\n";
	}
	$postFb .= "Retrouvez les autres soirées sur www.".$ville.".fr\n";
	
	
	
	$imgPathForPostTop = dirname(__FILE__).'/../../public/img/'.$ville.'-pub.jpg';
	
	$params = array(
		"access_token" => $access_token,
		"message" => $postFb,
		"source" => "@$imgPathForPostTop"
	);
	
	echo date('Y-m-d H:i:s')." --- post fb prepare\n";
	
	try {
	  	$ret = $fb->api('/'.$pageId.'/photos', 'POST', $params);
	  	echo date('Y-m-d H:i:s')." --- posted to fb\n";
	} catch(Exception $e) {
	  	echo date('Y-m-d H:i:s').$e->getMessage()."\n";
	}
} else {
	echo date('Y-m-d H:i:s')." --- rien a poster\n";
}
