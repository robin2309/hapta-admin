<?php

class Application_Form_Genre extends Zend_Form
{

    public function init()
    {
    	$this->setMethod('post')
        	 ->setName("Ajout Genre");
    
        $id = new Zend_Form_Element_Hidden('idGenre');
        $id->addFilter('Int');
    
        $genre = new Zend_Form_Element_Text('nameGenre');
        $genre->setLabel('Genre')
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton');      
        
        $this->addElements(array($id, $genre, $submit));
        
        /*$this->addElement('submit', 'add', array(
			'required' => false,
			'ignore' => true,
			'label' => 'Ajouter',
		)); */   
	}


}

