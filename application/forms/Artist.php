<?php

class Application_Form_Artist extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post')
        	 ->setName("Ajout Genre");

        $id = new Zend_Form_Element_Hidden('idArtist');
        $id->addFilter('Int');
    
        $genre = new Zend_Form_Element_Text('name');
        $genre->setLabel('Nom')
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton');      
        
        $this->addElements(array($id, $genre, $submit));
    }


}

