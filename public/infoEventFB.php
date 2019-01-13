<?PHP

	$linkfb = $_GET['fullFb'];
	

	$getIdEventFromFull = $linkfb;
	$linkfb2 = explode("/", $getIdEventFromFull);
	
	// Ajout du code facebook pour récupérer directement le nombre de participant d'un événement Facebook
	$count = file_get_contents("https://graph.facebook.com/v2.1/".$linkfb2[4]."/attending?access_token=CAAFJWaxvuA8BABAm2RAHkARJiy3AjqXI4IiWYIPD6ds5l93sf1yUEsNSHrZCAdALKF3YKR1KxOZBttZAecdSqZA6UsCf34HUumvUqkZAyWHWyHKndQtkZA1nTk1ROqAyZB2vOwk96g4iZC0pNPPaVUCmk8KbWi2tbZByLx6qKZA8n0ptMgYZBrivru79RsgmiTwtKvDrF6hf3VyEQZDZD");
	$getNbAttengindEventFb = json_decode($count, true);
	$attending = 0;
	foreach($getNbAttengindEventFb['data'] as $key=>$val)
	{
	    $attending += count($val['name']);
	}

	// Ajout du code facebook pour récupérer directement toute les infos a l'aide de l'ID de l'event
	$data  = file_get_contents("https://graph.facebook.com/v2.1/".$linkfb2[4]."?access_token=CAAFJWaxvuA8BABAm2RAHkARJiy3AjqXI4IiWYIPD6ds5l93sf1yUEsNSHrZCAdALKF3YKR1KxOZBttZAecdSqZA6UsCf34HUumvUqkZAyWHWyHKndQtkZA1nTk1ROqAyZB2vOwk96g4iZC0pNPPaVUCmk8KbWi2tbZByLx6qKZA8n0ptMgYZBrivru79RsgmiTwtKvDrF6hf3VyEQZDZD");
	$array = json_decode($data, true);

	// Remplacement des /n en <br> pour garder la forme de la description de l'event
	// $str     = $array['description'];
	// $order   = array("\n");
	// $replace = '<br />';
	// $newstr  = str_replace($order, $replace, $str);
	$newstr     = $array['description'];

	// Description de l'event 
	$artists = $newstr;

	// Nom de l'event 
	$name = $array['name'];
	

	// Modification de l'heure et de la date du début de l'event
	$heure_date_debut1 = explode("T", $array['start_time']); // SEPARATION ENTRE DATE ET HEURE
	$heure_date_debut2 = explode("-", $heure_date_debut1[0]); // DATE
	$heure_date_debut3 = explode("+", $heure_date_debut1[1]); // HEURE ENTIERE
	$heure_date_debut4 = explode(":", $heure_date_debut3[0]); // HEURE MINUTE

		// Date de début de l'event
		$date = $heure_date_debut2[0]."-".$heure_date_debut2[1]."-".$heure_date_debut2[2];

		// Heure de début de l'event
		if($heure_date_debut4[0] != "")
		{
			$heure_debut_final = $heure_date_debut4[0];
			$minutes_debut_final = $heure_date_debut4[1];	
		}
		else
		{
			$heure_debut = NULL;
		}

	// Modification de l'heure de fin de l'event
	$heure_date_fin1 = explode("T", $array['end_time']); // SEPARATION ENTRE DATE ET HEURE
	$heure_date_fin2 = explode("-", $heure_date_fin1[0]); // DATE
	$heure_date_fin3 = explode("+", $heure_date_fin1[1]); // HEURE ENTIERE
	$heure_date_fin4 = explode(":", $heure_date_fin3[0]); // HEURE MINUTE

		// Heure de fin de l'event
		if($heure_date_fin4[0] != "")
		{
			$heure_fin_final = $heure_date_fin4[0];
			$minutes_fin_final = $heure_date_fin4[1];
		}
		else
		{
		  	$heure_fin = NULL;
		}

	// Lieu de l'event 
	$spot = $array['location'];

	// Lien facebook de l'event
	// $linkfb3 = "https://www.facebook.com/events/".$array['id']."/"; 

	// Get image event FB
	$dataImage  = file_get_contents("https://graph.facebook.com/v2.1/".$linkfb2[4]."?fields=cover&access_token=CAAFJWaxvuA8BABAm2RAHkARJiy3AjqXI4IiWYIPD6ds5l93sf1yUEsNSHrZCAdALKF3YKR1KxOZBttZAecdSqZA6UsCf34HUumvUqkZAyWHWyHKndQtkZA1nTk1ROqAyZB2vOwk96g4iZC0pNPPaVUCmk8KbWi2tbZByLx6qKZA8n0ptMgYZBrivru79RsgmiTwtKvDrF6hf3VyEQZDZD");
	$array = json_decode($dataImage, true);

	// Image de l'event 
	$imageEvent = $array['cover']['source'];

	// Get link billet event FB
	$dataTickets  = file_get_contents("https://graph.facebook.com/v2.1/".$linkfb2[4]."?fields=ticket_uri&access_token=CAAFJWaxvuA8BABAm2RAHkARJiy3AjqXI4IiWYIPD6ds5l93sf1yUEsNSHrZCAdALKF3YKR1KxOZBttZAecdSqZA6UsCf34HUumvUqkZAyWHWyHKndQtkZA1nTk1ROqAyZB2vOwk96g4iZC0pNPPaVUCmk8KbWi2tbZByLx6qKZA8n0ptMgYZBrivru79RsgmiTwtKvDrF6hf3VyEQZDZD");
	$array = json_decode($dataTickets, true);

	// Image de l'event 
	$ticket = $array['ticket_uri'];

	// changement en json_encode
	$vals = array(
        'nameFb' => $name,
        'imageCoverFb' => $imageEvent, 
        'spot' => $spot,
        'attending' => $attending,
        'heureDebut' => $heure_debut_final,
        'minutesDebut' => $minutes_debut_final,
        'heureFin' => $heure_fin_final,
        'minutesFin' => $minutes_fin_final,
        'date' => $date,
        'ticket' => $ticket,
        'artists' => $artists,
    );
    
    // Now we want to JSON encode these values to send them to $.ajax success.
    echo json_encode($vals);
?>