<?php

class Application_Form_UpdatePwd extends Zend_Form
{

    public function init()
    {
	$this->addElement("password", "pwd_old", array(
	    "label" => "Régi jelszó: ",
	    'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
		'NotEmpty',
                array('StringLength', false, array(6, 20)),
            ),
	    "required" => true,
	    "class"	 => "input"
	));

	$this->addElement("password", "pwd", array(
	    "label" => "Új jelszó: ",
	    'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
		'NotEmpty',
                array('StringLength', false, array(6, 20)),
            ),
	    "required" => true,
	    "class"	 => "input"
	));

	$this->addElement("password", "pwd2", array(
	    "label" => "Új jelszó megint: ",
	    'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
		'NotEmpty',
                array('StringLength', false, array(6, 20)),
            ),
	    "required" => true,
	    "class"	 => "input"
	));

	$this->addElement("submit", "submit", array(
	    "label" => "Megváltoztatom"
	));

	$this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'id' => 'reg_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }
}

