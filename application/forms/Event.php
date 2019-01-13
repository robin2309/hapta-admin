<?php

class Application_Form_Event extends Zend_Form
{

	/*public $elementDecorators = array(
        'ViewHelper',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array('Label', array('tag' => 'td')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
        
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );*/

    public function init()
    {
        $this->setMethod('post')
        	->setName('Evenement');
        
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nom')
        	 //->setDecorators($this->elementDecorators)
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        	 
        $spot = new Zend_Form_Element_Select('idSpot');
        $spot->setLabel('Club');
        	//->setDecorators($this->elementDecorators);
        
        $artists = new Zend_Form_Element_Multiselect('idArtist');
        $artists->setLabel('Artistes');
        	//->setDecorators($this->elementDecorators);
        
        $linkFb = new Zend_Form_Element_Text('linkFb');
        $linkFb->setLabel('Lien FB')
        	   //->setDecorators($this->elementDecorators)
        	   ->setRequired(true)
        	   ->addFilter('StripTags')
        	   ->addFilter('StringTrim')
        	   ->addValidator('NotEmpty');
        	   
        $price = new Zend_Form_Element_Text('price');
        $price->setLabel('Prix')
        	  //->setDecorators($this->elementDecorators)
        	  ->setRequired(true)
        	  ->addFilter('StripTags')
        	  ->addFilter('StringTrim')
        	  ->addValidator('Digits');
        	  
        $genre = new Zend_Form_Element_Multiselect('idGenre');
        $genre->setLabel('Genre');
        	  //->setDecorators($this->elementDecorators);
        	  
        $linkTicket = new Zend_Form_Element_Text('linkTicket');
        $linkTicket->setLabel('Lien Ticket')
        		   //->setDecorators($this->elementDecorators)
        	   	   ->setRequired(true)
        	       ->addFilter('StripTags')
        	       ->addFilter('StringTrim')
        	       ->addValidator('NotEmpty');
        	       
        $heureDebut = new Zend_Form_Element_Text('heureDebut');
        $heureDebut->setLabel('Heure Début')
        		   //->setDecorators($this->elementDecorators)
        	   	   ->setRequired(true)
        	       ->addFilter('StripTags')
        	       ->addFilter('StringTrim')
        	       ->addValidator('NotEmpty');
        	       
        $heureFin = new Zend_Form_Element_Text('heureFin');
        $heureFin->setLabel('Heure Fin')
        		 //->setDecorators($this->elementDecorators)
        	   	   ->setRequired(true)
        	       ->addFilter('StripTags')
        	       ->addFilter('StringTrim')
        	       ->addValidator('NotEmpty');
        
        $date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date')
        	 //->setDecorators($this->elementDecorators)
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addValidator('NotEmpty');
        	 
        /*$concours = new Zend_Form_Element_Text('concours');
        $concours->setLabel('Concours')
        	 ->setDecorators($this->elementDecorators)
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addValidator('NotEmpty');*/
        	 
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton');
        	   //->setDecorators($this->buttonDecorators);
        
        $this->addElements(array($id, $name, $date, $spot, $artists, $linkFb, $price, $genre, $linkTicket, $heureDebut, $heureFin, $submit));
        
        /*$this->setDecorators(array(
            'FormElements',
            array('HtmlTags', array('tag' => 'table', 'class' => 'formulaire')),
            'Form',
        ));*/
        
        /*echo $this->formElementErrors()
                ->setMessageOpenFormat('<div class="help-inline">')
                ->setMessageSeparatorString('</div><div class="help-inline">')
                ->setMessageCloseString('</div>')
                ->render($element);*/
        
    }


}

