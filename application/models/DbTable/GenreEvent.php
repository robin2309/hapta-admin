<?php

class Application_Model_DbTable_GenreEvent extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_event_genre';
    protected $_primary = array('idEvent','idGenre');

    public function addLine($data){
	    $this->insert($data);
    }
    
    public function getGenres($id){
	    $select = $this->select()
	    			   ->from($this->_name, array('idGenre'))
	    			   ->where('idEvent = ?', $id);
        $rows = $this->fetchAll($select);
        $idGenres = array();
        foreach($rows as $row){
        	$idGenres[]=$row['idGenre'];
	        //array_push($idGenres, $row['idGenre']);
        }
        return $idGenres;
    }
    
    public function getGenresName($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT g.nameGenre FROM hapta_genre g, hapta_event_genre eg WHERE eg.idGenre = g.idGenre and eg.idEvent = :idEvent";
	    return $db->fetchAll($sql, array(':idEvent'=>$idEvent));
    }
    
    public function deleteGenres($idEvent){
	    $this->delete('idEvent ='. $idEvent);
    }
    
    public function checkGenresExist($nameGenres){
    	$db = $this->getAdapter();
    	foreach($nameGenres as $name){
		    $sqlIdGenre = "SELECT idGenre FROM hapta_genre WHERE nameGenre = :nameGenre";
			$resultIdGenre = $db->query($sqlIdGenre, array(':nameGenre' => $name))->fetchAll();
			if(count($resultIdGenre) == 0){
	    		return $name;
			}
		}
		return 1;
    }
    
    
    public function insertGenreEventFromNames($idEvent, $nameGenres){
	    $db = $this->getAdapter();
	    $sql = "";
	    foreach($nameGenres as $name){
	    	try{
		    	$sql = "INSERT INTO hapta_event_genre(idEvent, idGenre) SELECT :idEvent, idGenre FROM hapta_genre WHERE nameGenre = :nameGenre ";
		    	$db->query($sql, array(':idEvent' => $idEvent, ':nameGenre' => $name));
	    	} catch(Zend_Db_Statement_Exception $e){
		    	if ($e->getCode() == 23000) {
		    		
		    	} else {
			    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		    	}
	    	}
	    	
	    }
    }
    
    public function updateGenreEventFromNames($idEvent, $nameGenres){
    	$this->deleteGenres($idEvent);
    	$this->insertGenreEventFromNames($idEvent, $nameGenres);
    }
    
    public function getGenreNameFromIdEvent($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT hg.nameGenre as nameGenre from hapta_genre hg JOIN hapta_event_genre heg ON hg.idGenre = heg.idGenre WHERE idEvent = :idEvent";
	    $result = $db->fetchAll($sql, array(':idEvent'=>$idEvent));
	    $genres = array();
	    foreach($result as $genre){
	    	$genres[] = $genre['nameGenre'];
	    }
	    return implode(', ', $genres);
    }


}

