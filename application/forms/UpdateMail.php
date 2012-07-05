<?php

class Application_Form_UpdateMail extends Zend_Form
{

    public function init()
    {
	$this->addElement("password", "pwd", array(
	    "label" => "Jelszó: ",
	    'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
		'NotEmpty',
                array('StringLength', false, array(6, 20)),
            ),
	    "required" => true,
	    "class"	 => "input"
	));

	$this->addElement("text", "mail", array(
	    "label" => "E-mail: ",
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

