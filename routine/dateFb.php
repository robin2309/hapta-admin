<?php
require_once(realpath(dirname(__FILE__)."/accessModel.simpleClass.php"));
require_once(realpath(dirname(__FILE__)."/host.php"));



$eventsQuickAdd = getAddQuickEvents($model);

foreach($eventsQuickAdd as $event){
	$content = file_get_contents("http://admindev.hapta.fr/infoEventFB.php?fullFb=". $event['linkFb']);
	$infoEvent = json_decode($content, true);
	$dateEvent = $infoEvent["date"];
	echo "date recup : " . $dateEvent ."\n";
	$idEvent = $event['id'];
	updateDateEvent($model, $idEvent, $dateEvent);
	echo "event mis a jour!\n";
}



function getAddQuickEvents($model){
	$sql = "SELECT linkFb, id, date FROM `hapta_event` WHERE linkFb LIKE 'https://%' AND heureDebut IS NULL AND heureFin IS NULL AND (name IS NULL OR name='') order by date, linkFb";
	return $model->rechquery($sql);
}

function updateDateEvent($model, $idEvent, $date){
	$sql = "UPDATE hapta_event set date = \"$date\" where id=$idEvent";
	$model->queryTransac($sql);
}