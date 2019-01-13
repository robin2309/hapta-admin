<?php

class Application_Form_Spot extends Zend_Form
{

    public function init()
    {
    
    	$this->setMethod('post')
        	 ->setName("Club");
    
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
    
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nom')
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        	 
        
        $city = new Zend_Form_Element_Text('city');
        $city->setLabel('Ville')
        	   ->setRequired(true)
        	   ->addFilter('StripTags')
        	   ->addFilter('StringTrim')
        	   ->addValidator('NotEmpty');
        	   
        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Adresse')
        	  ->setRequired(false)
        	  ->addFilter('StripTags')
        	  ->addFilter('StringTrim');
        	  
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton');
        
        $this->addElements(array($id, $name, $city, $address,$submit));
        
    }


}

