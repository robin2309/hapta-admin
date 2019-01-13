<?php
require_once(realpath(dirname(__FILE__)."/../displayFunctions.php"));
require_once(realpath(dirname(__FILE__)."/../accessModel.simpleClass.php"));
require_once(realpath(dirname(__FILE__)."/../host.php"));
require_once(realpath(dirname(__FILE__)."/../php-sdk/facebook.php")); // set the right path
require_once(realpath(dirname(__FILE__)."/../conf/params_app_fb.php"));

// $config est maintenant defini dans params_app_fb.php
$fb = new Facebook($config);
$concoursToPost = $model->getConcoursToPost();

if(count($concoursToPost) === 0){
	echo date('Y-m-d H:i:s')." --- rien a poster\n";
}

foreach($concoursToPost as $concours) {
	echo date('Y-m-d H:i:s')." --- concours a poster\n";
	$artists = $model->getArtistFromConcours($concours['idEvent']);
	$allInfosArtist = array();
	foreach ($artists as $artist) {
		if($artist['labelArtist'] == null) {
			$allInfosArtist[] = "\n> ".$artist['nameArtist'];
		}
		else {
			$allInfosArtist[] = "\n> ".$artist['nameArtist']." ( ".$artist['labelArtist']." - ".$artist['countryArtist']." ) ";
		}
	}
	
	$artistsEvent = implode(' ', $allInfosArtist);

	if($concours['nbGagnants'] == 1) {
		$textChangment = " Le gagnant sera tiré au sort et obtiendra";
		$messages = "- ".$concours['nbGagnants']."x".$concours['nbPlaces']." PLACE A GAGNER // ".$concours['nameEvent']." @ ".$concours['nameSpot']." - ".dateToFrench($concours['dateEvent'])."\n".$artistsEvent."\n\nPour participer il te suffit de LIKER + COMMENTER OU PARTAGER ce post.".$textChangment." ".$concours['nbPlaces']." places pour la soirée.\n\nFin du concours : ".dateToFrench($concours['dateFin'])."\nLien de l'event :\n".$concours['linkFb']."\n\n";
	}
	else {
		$textChangment = " Les gagnants seront tirés au sort et obtiendront";
		$messages = "- ".$concours['nbGagnants']."x".$concours['nbPlaces']." PLACE A GAGNER // ".$concours['nameEvent']." @ ".$concours['nameSpot']." - ".dateToFrench($concours['dateEvent'])."\n".$artistsEvent."\n\nPour participer il te suffit de LIKER + COMMENTER OU PARTAGER ce post.".$textChangment." ".$concours['nbPlaces']." places pour la soirée.\n\nFin du concours : ".dateToFrench($concours['dateFin'])."\nLien de l'event :\n".$concours['linkFb']."\n\n";
	}
	
	echo date('Y-m-d H:i:s')." --- info du concours recuperees\n";
	
	$imgPath = dirname(__FILE__).'/img_concours/img_concours_'.$concours['idConcours'].'.jpg';
	
	$params = array(
		"access_token" => $access_token,
		"message" => $messages,
		"source" => "@$imgPath"
	);//'@'.
	
	echo date('Y-m-d H:i:s')." --- post fb prepare\n";
	
	try {
	  	$ret = $fb->api('/'.$pageId.'/photos', 'POST', $params);
	  	echo date('Y-m-d H:i:s')." --- posted to fb\n";
		$data_get_posts  = file_get_contents("https://graph.facebook.com/v2.1/haptalyon?fields=posts&access_token=$access_token");
		$array_get_posts = json_decode($data_get_posts, true);
		$id_post = $array_get_posts['posts']['data'][0]['id'];

		$idPostFinal = explode("_", $id_post);
		$id_post_fin = $idPostFinal[1];
		$model->updatePostConcours($concours['idConcours'],$id_post_fin);
		echo date('Y-m-d H:i:s')." --- concours archive\n";
	} catch(Exception $e) {
	  	echo date('Y-m-d H:i:s').$e->getMessage()."\n";
	}
}

?>