<?php

class Application_Model_DbTable_AdminEvent extends Zend_Db_Table_Abstract
{

    protected $_name = 'hapta_event_admin';
    protected $_primary = array('idEvent','idUser');
    
    public function addLine($data){
	    $this->insert($data);
    }
    
    public function getAdminNames($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT u.username FROM hapta_user u, hapta_event_admin ea WHERE ea.idUser = u.id and ea.idEvent = :idEvent";
	    $rows = $db->fetchAll($sql, array(':idEvent'=>$idEvent));
	    $admins = array();
        foreach($rows as $row){
        	$admins[]=$row['username'];
	        //array_push($idArtists, $row);
        }
        return $admins;
    }
    
    public function deleteAdmins($idEvent){
	    $this->delete('idEvent ='. $idEvent);
    }
    
    public function getAdmins($id){
	    $select = $this->select()
	    			   ->from($this->_name, array('idUser'))
	    			   ->where('idEvent = ?', $id);
        $rows = $this->fetchAll($select);
        $idAdmins = array();
        foreach($rows as $row){
        	$idAdmins[]=$row['idUser'];
        }
        return $idAdmins;
    }
    
    public function checkAdminsExist($nameAdmins){
    	$db = $this->getAdapter();
    	foreach($nameAdmins as $name){
		    $sqlIdAdmin = "SELECT id FROM hapta_user WHERE username = :nameAdmin";
			$resultIdAdmin = $db->query($sqlIdAdmin, array(':nameAdmin' => $name))->fetchAll();
			if(count($resultIdAdmin) == 0){
	    		return $name;
			}
		}
		return 1;
    }
    
    
    public function insertAdminEventFromNames($idEvent, $nameAdmins){
	    $db = $this->getAdapter();
	    $sql = "";
	    foreach($nameAdmins as $name){
	    	try{
		    	$sql = "INSERT INTO hapta_event_admin(idEvent, idUser) SELECT :idEvent, id FROM hapta_user WHERE username = :nameAdmin  ";
		    	$db->query($sql, array(':idEvent' => $idEvent, ':nameAdmin' => $name));
	    	} catch(Zend_Db_Statement_Exception $e){
		    	if ($e->getCode() == 23000) {
		    		
		    	} else {
			    	throw new Zend_Controller_Action_Exception('Oops, erreur !!', 500);
		    	}
	    	}
	    	
	    }
    }
    
    public function updateAdminEventFromNames($idEvent, $nameAdmins){
    	$this->deleteAdmins($idEvent);
    	$this->insertAdminEventFromNames($idEvent, $nameAdmins);
    }
    
    public function getAdminNameFromIdEvent($idEvent){
	    $db = $this->getAdapter();
	    $sql = "SELECT hu.username as username FROM hapta_user hu JOIN hapta_event_admin hea ON hu.id = hea.idUser WHERE hea.idEvent = :idEvent";
	    $result = $db->fetchAll($sql, array(':idEvent'=>$idEvent));
	    $admins = array();
	    foreach($result as $admin){
	    	$admins[] = $admin['username'];
	    }
	    return implode(', ', $admins);
    }

}

