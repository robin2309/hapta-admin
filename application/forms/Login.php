<?php

class Application_Form_Login extends Zend_Form
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
			->setName('login');


		
		$username = new Zend_Form_Element_Text('username');
		$username->setAttrib('class', 'element')
			 ->setLabel('Login')
			 ->setRequired(true)
        	 ->setAttrib('placeholder', 'Username')
        	 ->removeDecorator('Errors')
        	 ->removeDecorator('Description')
        	 ->removeDecorator('HtmlTag');
        $username->getDecorator('Label')->setOption('tag', null);
        $username->getDecorator('Label')->setOption('class', 'sr-only');
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
        		 ->setRequired(true)
        		 ->setAttrib('class', 'element')
        		 ->setAttrib('placeholder', 'Password')
        		 ->removeDecorator('Errors')
        		 ->removeDecorator('Description')
        		 ->removeDecorator('HtmlTag');
        $password->getDecorator('Label')->setOption('tag', null);
        $password->getDecorator('Label')->setOption('class', 'sr-only');
        
        //$this->addElement($username, $password);
        
		/*$this->addElement('password', 'password', array(
			'filters' => array('StringTrim'),
			'validators' => array(
			array('StringLength', false, array(0, 50)),
			),
			'required' => true,
			'label' => 'Password:',
			//'decorators' => $this->elementDecorators,
		));*/
		
		$submit = new Zend_Form_Element_Submit('Connexion');
        $submit->setAttrib('id','submitbutton')
        		 ->removeDecorator('Errors')
        		 ->removeDecorator('Description')
        		 ->removeDecorator('HtmlTag');
        //$submit->getDecorator('Label')->setOption('tag', null);
        //$submit->getDecorator('Label')->setOption('class', 'sr-only');
        
        $this->addElements(array($username,$password,$submit));
		
		/*$this->setDecorators(array(
            'FormElements',
            array('HtmlTags', array('tag' => 'table', 'class' => 'formulaire')),
            'Form',
        ));*/
        
        
    }


}

