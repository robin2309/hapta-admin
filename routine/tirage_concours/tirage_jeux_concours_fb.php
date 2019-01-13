<?PHP
require_once(realpath(dirname(__FILE__)."/../displayFunctions.php"));
require_once(realpath(dirname(__FILE__)."/../accessModel.simpleClass.php"));
require_once(realpath(dirname(__FILE__)."/../host.php"));
require_once(realpath(dirname(__FILE__)."/PHPMailer.php"));
require_once(realpath(dirname(__FILE__)."/SMTP.php"));
require_once(realpath(dirname(__FILE__)."/../conf/mail_conf.php"));

/* ------------
 * MAIN
 * -------------
*/
$concoursToDraw = $model->getConcoursToDraw();
if(count($concoursToDraw) === 0){
	echo date('Y-m-d H:i:s')." --- aucun concours a tirer\n";
}
foreach($concoursToDraw as $concours) {
	echo date('Y-m-d H:i:s')." --- concours a tirer : ".$concours['idPostConcours']."\n";
	
	$nombrePlace = $concours['nbPlaces'];
	$nombrePlaceFinal = 0;
	if($nombrePlace != 1) {
		$nombrePlaceFinal = $nombrePlace - 1;
	}
	// Nombre de gagnant 
	$nombreGagnants = $concours['nbGagnants'];
	// Id du post de la fan page hapta lyon 
	$idPostJeux = $concours['idPostConcours'];
	// Nom de la soirée et date de la soirée 
	$name = $concours['nameEvent'];
	$dateSoiree = $concours['dateEvent'];

	/* RECUPERER LES LIKES */	
	$arrayLikeFb = getFbData($idPostJeux, 'likes');
	$nbrs_posts = count($arrayLikeFb['likes']['data']);				
	$array_like = array();
	for($count_post = 0; $count_post <= $nbrs_posts; $count_post++) {
		$name_like = $arrayLikeFb['likes']['data'][$count_post]['name'];
		$id_name_like = $arrayLikeFb['likes']['data'][$count_post]['id'];
		if(!empty($name_like) && !empty($id_name_like)){
			$array_like[] = $name_like." ".$id_name_like;
		}
	}


	/* RECUP COMMENTAIRES */
	$arrayCommentFb = getFbData($idPostJeux, 'comments');
	$nbrs_posts = count($arrayCommentFb['comments']['data']);
	$array_comments = array();
	for($count_post = 0; $count_post <= $nbrs_posts; $count_post++) {
		$name_comments = $arrayCommentFb['comments']['data'][$count_post]['from']['name'];
		$id_name_comments = $arrayCommentFb['comments']['data'][$count_post]['from']['id'];
		$mess = $arrayCommentFb['comments']['data'][$count_post]['message'];
		if(!empty($name_comments) && !empty($id_name_comments)){
			$array_comments[] = $name_comments." ".$id_name_comments;
		}
	}

	$users = array_intersect($array_like, $array_comments);
	
	if($nombreGagnants == 1) {
		echo date('Y-m-d H:i:s')." --- un seul gagnant\n";
		$random_keys = array_rand($users);

		// Envoi des résultats au gagnant et au orga
		if($users[$random_keys] != null) {
			$winnerInfo = explode(" ", $users[$random_keys]);
			$winner = $winnerInfo[0]." ".$winnerInfo[1]." (id facebook: http://www.facebook.com/".$winnerInfo[2].")";
			echo "<br/>gagnant: $winner <br /><br />";
			$winners = array();
			$winners[] = $winner;
			// Envoi des résultats au gagnant et au orga
			if(sendMail($name, $dateSoiree, $idPostJeux, $winners, $confMail)){
				$model->updateConcoursPasse($concours['idConcours']);
				echo date('Y-m-d H:i:s')." --- concours archive\n";
			}
		} else {
			echo date('Y-m-d H:i:s')." --- probleme lors de la recuperation des infos l'user\n";
		}
	}
	else {
		echo date('Y-m-d H:i:s')." --- plus de 1 gagnant\n";
		if(count($users) < $nombreGagnants){
			$random_keys = array_rand($users, count($users));
		} else {
			$random_keys = array_rand($users, $nombreGagnants);
		}
		
		if($random_keys != null) {
			$winners = array();
			foreach ($random_keys as $key) {
				$winnerInfo = explode(" ", $users[$key]);
				$winner = $winnerInfo[0]." ".$winnerInfo[1]." - http://www.facebook.com/".$winnerInfo[2] ;
				$winners[] = $winner;
			}
			// Envoi des résultats au gagnant et au orga
			if(sendMail($name, $dateSoiree, $idPostJeux, $winners, $confMail)){
				$model->updateConcoursPasse($concours['idConcours']);
				echo date('Y-m-d H:i:s')." --- concours archive\n";
			}
		} else {
			echo date('Y-m-d H:i:s')." --- probleme lors de la recuperation des infos des users\n";
		}
	}
}


//Recuperer donnees de facebook (likes et comments)
function getFbData($idPost, $field){
	$data_posts  = file_get_contents("https://graph.facebook.com/v2.1/".$idPost."?fields=$field.limit(999999999)&access_token=CAAFJWaxvuA8BAImqUlmOT6pXTF5opGDvY2EuTXPrMaB068lJBiKydZAbcaOLbic76GTpcZAjSMZB4JM9wGZB4iaprxt2unvCfumnyWJAuqdOsEHsydmQmJzhrkzD6X2bXak8akngCzZCNUMRZBd9MlF3waG3s6QJjPDGpcglBrZCOHQgrlvZBVCK");
	return json_decode($data_posts, true);
}


//Envoyer mail d'avertissement des gagnants du tirage
function sendMail($nameEvent, $dateEvent, $idPost, $winners, $confMail){

	$winnersString = "";
	foreach($winners as $winner){
		$winnersString .= "<li>$winner</li>";
	}

	echo date('Y-m-d H:i:s')." --- preparation du mail\n";

	$message  = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		</head>
		<body style="margin: 0; padding: 0; height:100%;">
		 <table border="0" background="http://www.haptalyon.fr/img/backMail.png" cellpadding="0" cellspacing="0" width="100%" height="100%">
		  <tr>
		   <td>
		     <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
		       <tr>
		         <td align="center" style="padding: 40px 0 30px 0;">
		          <img src="http://www.haptalyon.fr/img/logoMail.png" alt="Logo Hapta" />
		         </td>
		       </tr>
		       <tr>
		         <td style="padding: 0 0 20px 0;">
		           <table bgcolor="#ffffff" style="padding: 20px 30px 20px 30px;" border="0" cellpadding="0" cellspacing="0" width="100%">
		            <tr>
		             <td>
		                Hey !<br /><br />
		                Pour la soirée '.$nameEvent.' du '.dateToFrench($dateEvent).', le(s) gagnant(s) est(sont) : <br />
		                <ul>
		                '.$winnersString.'
		                </ul>
						<br /><br />
						<div style="background:rgba(243,243,243,0.85);padding:5px;">
			                Hey ! 
							Tu a gagné '.$nombrePlace.' place(s) pour '.$nameEvent.' le '.dateToFrench($dateEvent).'. <br/> Ton nom Facebook (+ '.$nombrePlaceFinal.') est sur la liste. <br/>
							Bonne soirée ;) <br/>
							Hapta. <br/> <br/>

							PS : https://www.facebook.com/'.$idPost.'/ 
							</div>
						<br /><br />
						
						<div style="background:rgba(243,243,243,0.85);padding:5px;">
							Orga : 
							Hey ! 
							Pour la soirée '.$nameEvent.' du '.dateToFrench($dateEvent).'  c’est :
							<ul>
		                		'.$winnersString.' (+ '.$nombrePlaceFinal.')
		                	</ul>
							Passez une bonne soirée :)<br />
							Hapta <br /><br />

							PS : fait moi un petit retour quand tout est bon. 
							
							</div>
		                <br /><br /><br />
						Lien de l\'event : <a href="https://www.facebook.com/'.$idPost.'/" target="_blank">https://www.facebook.com/'.$idPost.'/</a><br />
					    A bientôt sur www.haptalyon.fr !<br />
		             </td>
		            </tr>
		           </table>
		         </td>
		       </tr>
		       <tr>
		        <td style="padding: 0 0 20px 0;">
		          <table bgcolor="#ffffff" style="padding: 20px 30px 20px 30px;" border="0" cellpadding="0" cellspacing="0" width="100%">
		            <tr>
		              <td style="font-size:12px;">
		                En imprimant ce mail vous pourrez le présenter lors de la soirée pour valider votre place gagné à l\'aide de Hapta Lyon. Pour toutes informations supplémentaires n\'hésitez pas à nous contacter sur contact@haptalyon.fr.<br />
		                Si ce mail est imprimé, merci de ne pas jeter sur la voie publique.
		              </td>
		            </tr>
		          </table>
		        </td>
		       </tr>
		       <tr style="padding: 0 0 30px 0;">
		         <td bgcolor="#4e67a9" style="padding: 30px 30px 30px 30px; font-family: \'UbuntuRegular\', Helvetica, Arial, sans-serif;">
		           <table border="0" cellpadding="0" cellspacing="0" width="100%">
		             <td width="75%" style="color:#FFF;">
		              <a href="http://www.haptalyon.fr" target="_blank" style="text-decoration:none; color:#FFF;" >www.haptalyon.fr</a><br/>
		             </td>
		             <td align="right">
		              <table border="0" cellpadding="0" cellspacing="0">
		               <tr>
		                <td>
		                 <a href="http://www.facebook.com/haptalyon" target="_blank">
		                  <img src="https://cdn3.iconfinder.com/data/icons/social-icons-24/24/Facebook-1-128.png" alt="Facebook" width="34" height="34" style="display: block;" border="0" />
		                 </a>
		                </td>
		               </tr>
		              </table>
		             </td>
		           </table>
		         </td>
		       </tr>
		     </table>
		   </td>
		  </tr>
		 </table>
		</body>
		</html>
    	';
    
    $subject = "Gagnants du jeu concours pour : ".$nameEvent;
    $to = $confMail['to'];
	$noReply = $confMail['mailSender'];
	$pwd = $confMail['mdpSender'];
	$hostSmtp = $confMail['hostSmtp'];

    $mail = new My_PHPMailer;

	$mail->SMTPDebug = 0;
	
	$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->Host = $hostSmtp;
	$mail->SMTPAuth = true;
	$mail->Username = $noReply;
	$mail->Password = $pwd;
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->setFrom($noReply, 'No-Reply');
	$mail->Sender = $noReply;
	$mail->addAddress($to, 'Contact');
	$mail->isHTML(true);
	
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = "Veuillez ouvrir ce message avec un client mail qui supporte le HTML.";
	
	if(!$mail->send()) {
	    echo date('Y-m-d H:i:s')." --- ERREUR: echec dans l'envoi du mail\n";
	    return false;
	} else {
	    echo date('Y-m-d H:i:s')." --- mail envoye\n";
	    return true;
	}
}


?>