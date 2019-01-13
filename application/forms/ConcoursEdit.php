<?php

class Application_Form_ConcoursEdit extends Zend_Form
{

	public $elementDecorators = array(
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
    );

    public function init()
    {
        $this->setMethod('post')
        	->setName('Evénement');
        
        $id = new Zend_Form_Element_Hidden('idConcours');
        $id->addFilter('Int');
        
        $event = new Zend_Form_Element_Text('event');
        $event->setLabel('Evénement')
        	->setDecorators($this->elementDecorators)
        	->setAttrib('readonly','true');
        
        $dateDeb = new Zend_Form_Element_Text('dateDeb');
        $dateDeb->setLabel('Date début')
        	 ->setDecorators($this->elementDecorators)
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        	 
        $dateFin = new Zend_Form_Element_Text('dateFin');
        $dateFin->setLabel('Date fin')
        	 ->setDecorators($this->elementDecorators)
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        
        $places = new Zend_Form_Element_Text('nbPlaces');
        $places->setLabel('Nombre places')
        	  ->setDecorators($this->elementDecorators)
        	  ->setRequired(true)
        	  ->addFilter('StripTags')
        	  ->addFilter('StringTrim')
        	  ->addValidator('Digits');
        	 
        $img = new Zend_Form_Element_Text('imgConcours');
        $img->setLabel('Image')
        	 ->setDecorators($this->elementDecorators)
        	 ->setRequired(true)
        	 ->addFilter('StripTags')
        	 ->addFilter('StringTrim')
        	 ->addValidator('NotEmpty');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton')
        	   ->setDecorators($this->buttonDecorators);
        
        $this->addElements(array($id, $event, $dateDeb, $dateFin, $places, $img, $submit));
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table', 'class' => 'formulaire')),
            'Form',
        ));
    }

}

