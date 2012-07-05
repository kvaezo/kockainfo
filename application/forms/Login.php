<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $username = $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                'Alpha',
		'NotEmpty',
                array('StringLength', false, array(3, 20)),
            ),
            'required'   => true,
            'label'      => 'Felhasználónév:',
	    "class"	 => "input"
        ));

        $password = $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
		'NotEmpty',
                array('StringLength', false, array(6, 20)),
            ),
            'required'   => true,
            'label'      => 'Jelszó:',
	    "class"	 => "input"
        ));

        $login = $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Belépés',
        ));

        // We want to display a 'failed authentication' message if necessary;
        // we'll do that with the form 'description', so we need to add that
        // decorator.
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'id' => 'login_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }

}

