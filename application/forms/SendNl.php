<?php

class Application_Form_SendNl extends Zend_Form
{

    public function init()
    {
	$newsletters = new Application_Model_DbTable_Newsletter();
	$templates = new Application_Model_DbTable_Nl();

	$tmp = new Zend_Form_Element_Select("templates");
	$tmp->setLabel("Sablon: ")->setRequired(true);
	foreach ($templates->fetchAll() as $template) {
	    $tmp->addMultiOption($template["filename"],$template["title"]);
	}

	$nl = new Zend_Form_Element_Select("newsletter");
	$nl->setLabel("Hírlevél: ")->setRequired(true);
	foreach ($newsletters->fetchAll() as $newsletter) {
	    $nl->addMultiOption($newsletter["id"],$newsletter["title"]);
	}

	$year = new Zend_Form_Element_Text("year");
	$year->setAttrib("maxlength", 4)->setLabel("Év / 0000: ");

	$month = new Zend_Form_Element_Text("month");
	$month->setAttrib("maxlength", 2)->setLabel("Hónap / 00: ");

	$day = new Zend_Form_Element_Text("day");
	$day->setAttrib("maxlength", 2)->setLabel("Nap / 00: ");

	$hour = new Zend_Form_Element_Select("hour");
	$hour->setLabel("Óra: ");

	for ($i=0;$i<24;$i++) {
	    if ($i<10) {
		$hour->addMultiOption('0'.$i,'0'.$i);
	    } else {
		$hour->addMultiOption($i,$i);
	    }
	}

	$minute = new Zend_Form_Element_Select("minute");
	$minute->setLabel("Perc: ")->addMultiOptions(array(
	    "05" => "05",
	    "10" => "10",
	    "15" => "15",
	    "20" => "20",
	    "25" => "25",
	    "30" => "30",
	    "35" => "35",
	    "40" => "40",
	    "45" => "45",
	    "50" => "50",
	    "55" => "55",
	));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Időzítés')->setAttrib('id', 'submit');

	$this->addElements(array($tmp, $nl, $year, $month, $day, $hour, $minute, $submit));
    }


}

