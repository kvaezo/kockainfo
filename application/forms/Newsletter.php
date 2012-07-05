<?php

class Application_Form_Newsletter extends Zend_Form
{

    public function init()
    {
	$arts = new Application_Model_DbTable_Articles();

	foreach ($arts->fetchAll()->toArray() as $article) {
	    $multioptions[$article["id"]] = $article["title"];
	}

	$title = new Zend_Form_Element_Text("title");
	$title->setLabel("A hírlevél tárgya: ")->setRequired(true)->setAttrib("style", "width: 490px;");

	$lead = new Zend_Form_Element_Textarea("lead");
	$lead->setLabel("Bevezető: ")->setAttrib('cols', 59)->setAttrib('rows', 20);

        $articles = new Zend_Form_Element_Multiselect("articles");
	$articles->setLabel("Cikkek: ")->addMultiOptions($multioptions)->setRequired(true);

	$submit = new Zend_Form_Element_Submit("submit");
	$submit->setLabel("Elküld");

	$this->addElements(array($title, $lead, $articles, $template, $submit));
    }


}

