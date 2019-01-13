<?PHP

class AccessModel
{
	private $_user;
	private $_password;
	private $_dsn;
	private $_link;
		
	public function __construct($user,$password,$dsn)
	{
		//$this->_abstractLayer=new ModelAbstractLayer($user,$password,$host,$database);
		$this->_user=$user;
		$this->_password=$password;
		$this->_dsn = $dsn;
		$this->_open_connection();
	}

	private function _open_connection()
	{
		if($this->_link==0)
		{
			try{
				$this->_link = new PDO($this->_dsn,$this->_user,$this->_password);
				$this->_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			} catch(PDOException $e){
                echo 'Erreur connexion DB';
            } 
		}
	}
	
	//fonction pour executer une requete preparee de type SELECT
	private function executeStmt($sql,$params){
		//on prepare le statement a executer avec la connexion courante et le sql passe en parametre a la fonction
		try{
			//on recupere la connexion et on initialise une requete preparee avec la chaine sql
			$stmt = $this->getLink()->prepare($sql);
			//on execute la requete preparee en passant le tableau parametre/valeurs 
			$stmt->execute($params);
			//on parcours le resultat pour avoir le resultat dans un tableau associatif
			$resultat = $stmt->fetchAll();
			//on detruit la requete preparee
			$stmt->closeCursor();
			return $resultat;
		} catch(PDOException $e){
			//enlever cela en production
            echo 'Erreur requete:';
        } 
	}
	
	//fonction pour executer une requete preparee de type:
	//INSERT, DELETE, ou UPDATE
	private function executeStmtTransac($sql,$params){
		try{
			$stmt = $this->getLink()->prepare($sql);
			$stmt->execute($params);
			$stmt->closeCursor();
		} catch(PDOEXception $e){
			//enlever cela en production
            echo 'Erreur requete';
		}
	}
	
	private function executeStmtTransacUserFb($sql,$params){
		try{
			$stmt = $this->getLink()->prepare($sql);
			$stmt->execute($params);
			$stmt->closeCursor();
		} catch(PDOEXception $e){
            if($e->getCode() == 23000){
	            return;
            } else {
	            echo 'Erreur requete';
            }
		}
	}
	
	//fonction pour executer une requete sans parametre 
	public function rechquery($query)
	{
		//on prepare le statement a executer avec la connexion courante et le sql passe en parametre a la fonction
		try{
			$stmt = $this->getLink()->prepare($query);
			$stmt->execute();
			//on parcours les resultats
			$resultat = $stmt->fetchAll();
			//necessaire?
			$stmt->closeCursor();
			return $resultat;
		} catch(PDOException $e){
                echo 'Erreur requete:';
        } 	
	}
	
	public function queryTransac($query){
		try{
			$stmt = $this->getLink()->prepare($query);
			$stmt->execute();
			$stmt->closeCursor();
		} catch(PDOException $e){
                echo 'Erreur requete:';
        } 
	}
	
	//getter pour recuperer la connexion a la base
	private function getLink(){
		return $this->_link;
	}
	

	/* Requete pour le tirage au sort des jeux concours */
	public function getJeuxConcours() {
		$sql = "SELECT * FROM hapta_concours as hc JOIN hapta_event AS he ON hc.idEvent = he.id";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}

	public function getInfosUsersForMail($idUserFB) {
		$sql = "SELECT * FROM hapta_user_fb WHERE idUserFb = '".$idUserFB."' ";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}
	/* fin des requetes pour le tirage au sort des jeux concours */


	/* Requete pour poster le jeux concours sur la page fan Hapta Lyon */
	public function getDateJeuxConcours() {
		$sql = "SELECT * FROM hapta_concours AS hc JOIN hapta_event AS he ON hc.idEvent = he.id";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}
	
	public function getConcoursToPost(){
		$sql="SELECT hc.idConcours as idConcours, he.id as idEvent, dateDeb, dateFin, nbPlaces, nbGagnants, he.name as nameEvent, hclub.name as nameSpot, linkFb, he.date as dateEvent FROM hapta_concours hc JOIN hapta_event he ON hc.idEvent = he.id JOIN hapta_club hclub ON he.idSpot = hclub.id WHERE dateDeb<=now() AND dateFin>now() AND hc.publie =0 AND hc.valide=1";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}
	
	public function getConcoursToDraw(){
		$sql="SELECT hc.idConcours as idConcours, he.id as idEvent, dateDeb, dateFin, nbPlaces, nbGagnants, he.name as nameEvent, hclub.name as nameSpot, linkFb, idPostConcours, he.date as dateEvent FROM hapta_concours hc JOIN hapta_event he ON hc.idEvent = he.id JOIN hapta_club hclub ON he.idSpot = hclub.id WHERE dateDeb<now() AND dateFin<=now() AND hc.publie =1 AND hc.valide=1 AND passe=0";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}
	
	public function updateConcoursPasse($idConcours){
		$sql="UPDATE hapta_concours SET passe=1 WHERE idConcours=$idConcours";
		$this->queryTransac($sql);
	}

	public function getInfosJeuxConcours($dateDeb) {
		$sql = "SELECT DISTINCT hc.idConcours AS idConcours, he.id AS idEvent, he.name AS nomEvent, he.linkFb AS linkFbEvent, he.date AS dateEvent, he.img AS imgEvent, hc.dateDeb AS dateDebConcours, hc.dateFin AS dateFinConcours, hc.nbPlaces AS nbPlacesConcours, hc.nbGagnants AS nbGagnantsConcours, hclub.name AS nameClub FROM hapta_event AS he JOIN hapta_concours AS hc ON he.id = hc.idEvent JOIN hapta_club AS hclub ON he.idSpot = hclub.id WHERE hc.dateDeb = '".$dateDeb."' ";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}

	public function getArtistFromConcours($idEvent) {
		$sql = "SELECT DISTINCT ha.name AS nameArtist, ha.label AS labelArtist, ha.country AS countryArtist FROM hapta_event_artist hea JOIN hapta_artist ha ON hea.idArtist = ha.idArtist WHERE hea.idEvent = '".$idEvent."' ";
		$resultat = $this->rechquery($sql);
		return $resultat;
	}

	public function updatePostConcours($idConcours, $post)
	{
		$sql = "update hapta_concours set idPostConcours='$post', publie=1 where idConcours=$idConcours";
		$this->queryTransac($sql);
	}
	/* Fin des requetes pour le post du jeux concours sur la page Fan Hapta Lyon */
	
	public function getTodayTopThree(){
	    $sql= "SELECT DISTINCT e.id, e.name, e.linkFb, e.publie, e.heureDebut, e.heureFin, e.date, e.attending, cl.id as idSpot, cl.name as nameSpot, co.idConcours FROM hapta_event_admin ad RIGHT JOIN hapta_event e ON e.id = ad.idEvent LEFT JOIN hapta_club cl ON e.idSpot = cl.id LEFT JOIN hapta_concours co ON e.id = co.idEvent WHERE e.date=curdate() AND NOT (e.name IS NULL OR e.name='') ORDER BY e.attending DESC";
	    $resultat = $this->rechquery($sql);
	    return $resultat;
    }
}

?>
