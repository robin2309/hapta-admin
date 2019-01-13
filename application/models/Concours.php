<?php

class Application_Model_Concours
{
	protected $_id;
	protected $_dateDeb;
	protected $_heureDeb;
	protected $_dateFin;
	protected $_heureFin;
	protected $_nbPlaces;
	protected $_nbGagnants;
	protected $_imgConcours;
	protected $_name;
	protected $_publie;
	protected $_valide;
	protected $_dataTable;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
        $this->_dataTable = new Application_Model_DbTable_Concours();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    //SETTERS
	public function setIdConcours($id){
		$this->_id = $id;
		return $this;
	}
	
	public function setDateDeb($date){
		$this->_dateDeb = $date;
		return $this;
	}
	
	public function setHeureDeb($hour){
		$this->_heureDeb = $hour;
		return $this;
	}
	
	public function setDateFin($date){
		$this->_dateFin = $date;
		return $this;
	}
	
	public function setHeureFin($hour){
		$this->_heureFin = $hour;
		return $this;
	}
	
	public function setNbPlaces($nb){
		$this->_nbPlaces = $nb;
		return $this;
	}
	
	public function setNbGagnants($nb){
		$this->_nbGagnants = $nb;
		return $this;
	}
	
	public function setImgConcours($img){
		$this->_imgConcours = $img;
		return $this;
	}
	
	public function setName($nom){
		$this->_name = $nom;
		return $this;
	}
	
	public function setPublie($publie){
		$this->_publie = $publie;
		return $this;
	}
	
	public function setValide($valide){
		$this->_valide = $valide;
		return $this;
	}
	
	//GETTERS
	public function getIdConcours(){
		return $this->_id;
	}
	
	public function getDateDeb(){
		return $this->_dateDeb;
	}
	
	public function getHeureDeb(){
		return $this->_heureDeb;
	}
	
	public function getDateFin(){
		return $this->_dateFin;
	}
	
	public function getHeureFin(){
		return $this->_heureFin;
	}
	
	public function getNbPlaces(){
		return $this->_nbPlaces;
	}
	
	public function getNbGagnants(){
		return $this->_nbGagnants;
	}
	
	public function getImgConcours(){
		return $this->_imgConcours;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getPublie(){
		return $this->_publie;
	}
	
	public function getValide(){
		return $this->_valide;
	}
	
	public function downloadConcoursImage($idEvent, $idConcours){
		$events = new Application_Model_DbTable_Event();
		$imgFb = $events->getLinkFbFromId($idEvent);
        if(file_put_contents('../routine/post_concours/img_concours/img_concours_'.$idConcours.'.jpg', file_get_contents($imgFb))){
        	
        }
	}
	
	private function prepareResultSet($result){
		$concours = array();
	    foreach($result as $attributes){
	    	$dateDebComplete = strtotime($attributes['dateDeb']);
        	$dateFinComplete = strtotime($attributes['dateFin']);
        	$attributes['dateDeb'] = date('Y-m-d', $dateDebComplete);
        	$attributes['heureDeb'] = date('H:i', $dateDebComplete);
        	$attributes['dateFin'] = date('Y-m-d', $dateFinComplete);
        	$attributes['heureFin'] = date('H:i', $dateFinComplete);
		    $concours[] = new Application_Model_Concours($attributes);
	    }
	    return $concours;
	}
	
	
	public function getInvalidConcours(){
		return $this->prepareResultSet($this->_dataTable->getInvalidConcours());
	}
	
	public function getUpcomingConcours(){
		return $this->prepareResultSet($this->_dataTable->getUpcomingConcours());
	}
	
	public function getCurrentConcours(){
		return $this->prepareResultSet($this->_dataTable->getCurrentConcours());
	}
	
	public function getPastConcours(){
		return $this->prepareResultSet($this->_dataTable->getPastConcours());
	}
}

