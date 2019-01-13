<?php

class Application_Model_DbTable_ArtistEvent extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_event_artist';
    protected $_primary = array('idArtist','idEvent');
    
    public function addLine($data){
	    $this->insert($data);
    }
    
    public function getArtists($id){
	    $select = $this->select()
	    			   ->from($this->_name, array('idArtist'))
	    			   ->where('idEvent = ?', $id);
        $rows = $this->fetchAll($select);
        $idArtists = array();
        foreach($rows as $row){
        	$idArtists[]=$row['idArtist'];
        }
        return $idArtists;
    }
    
    public function getArtistsName($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT a.name FROM hapta_artist a, hapta_event_artist ea WHERE ea.idArtist = a.idArtist and ea.idEvent = :idEvent";
	    $rows = $db->fetchAll($sql, array(':idEvent'=>$idEvent));
	    $artists = array();
        foreach($rows as $row){
        	$artists[]=$row['name'];
	        //array_push($idArtists, $row);
        }
        return $artists;
    }
    
    public function deleteArtists($idEvent){
	    $this->delete('idEvent ='. $idEvent);
    }
    
    public function insertArtistEventFromNames($idEvent, $nameArtists){
	    $db = $this->getAdapter();
	    $sql = "";
	    foreach($nameArtists as $name){
	    	try{
	    		$sql = "INSERT INTO hapta_event_artist(idArtist, idEvent) SELECT ha.idArtist, :idEvent FROM hapta_artist ha WHERE ha.name = :nameArtist ";
	    		$db->query($sql, array(':idEvent' => $idEvent, ':nameArtist' => $name));
	    	} catch(Zend_Db_Statement_Exception $e){
		    	if ($e->getCode() == 23000) {
		    		
		    	} else {
			    	//throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		    	}
	    	}
	    }
    }
    
    public function checkArtistsExist($nameArtists){
    	$db = $this->getAdapter();
    	foreach($nameArtists as $name){
		    $sqlIdArtist = "SELECT idArtist FROM hapta_artist WHERE name = :nameArtist";
			$resultIdArtist = $db->query($sqlIdArtist, array(':nameArtist' => $name))->fetchAll();
			if(count($resultIdArtist) == 0){
	    		return $name;
			}
		}
		return 1;
    }
    
    public function updateArtistEventFromNames($idEvent, $nameArtists){
    	$this->deleteArtists($idEvent);
    	$this->insertArtistEventFromNames($idEvent, $nameArtists);
    }
    
    public function getArtistNameFromIdEvent($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT ha.name as name FROM hapta_artist ha JOIN hapta_event_artist hea ON ha.idArtist = hea.idArtist WHERE hea.idEvent = :idEvent";
	    $result = $db->fetchAll($sql, array(':idEvent'=>$idEvent));
	    $artists = array();
	    foreach($result as $artist){
	    	$artists[] = $artist['name'];
	    }
	    return implode(', ', $artists);
    }
    
}

