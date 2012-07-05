<?php

class Application_Form_Nl extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');

	$title = new Zend_Form_Element_Text("title");
	$title->setLabel("Hírlevél neve: ")->setRequired(true);

	$file = new Zend_Form_Element_File('file_path');
	$file->setLabel("Feltöltendő hírlevél: ")->setRequired(true);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Feltöltés')->setAttrib('id', 'submit');

	$this->addElements(array($title, $file, $submit));
    }


}

