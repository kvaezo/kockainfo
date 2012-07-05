<?php

class Application_Form_Registration extends Zend_Form
{

    public function init()
    {
        $this->addElement("text", "nick", array(
	    "label" => "Felhasználónév: ",
	    'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                'Alpha',
		'NotEmpty',
                array('StringLength', false, array(3, 20)),
            ),
	    "required" => true,
	    "class"	 => "input"
	));

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

	$this->addElement("password", "pwd2", array(
	    "label" => "Jelszó megint: ",
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

	$this->addElement("checkbox", "newsletter", array(
	    "label" => "Kérek hírlevelet",
	    "value" => "1"
	));

	$this->addElement("checkbox", "agree", array(
	    "label" => "Regisztrációddal elfogadod, hogy adataidat nem adjuk ki harmadik félnek és kéretlen levelet nem fogsz kapni tőlünk!",
	    "required" => true
	));

	$this->addElement("submit", "submit", array(
	    "label" => "Regisztrálok"
	));

	$this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'id' => 'reg_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }
}

