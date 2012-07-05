<?php

class Application_Form_Article extends Zend_Form
{

    public function init()
    {
	$user = new Application_Model_DbTable_User();

	$this->addElement("hidden", "author");
	$this->author->setValue($user->getUserIdByNick(Zend_Auth::getInstance()->getIdentity()));

	$this->addElement("text","title");
	$this->title->setLabel("Cím:")
		    ->setRequired(true)
		    ->addFilter('StringTrim')
		    ->addValidator('NotEmpty')
		    ->addValidator('StringLength', false, array(0,200));

	$this->addElement("textarea", "lead");
	$this->lead->setLabel('Lead:')
		     ->setRequired(true)
		     ->setAttrib('cols', 80)
		     ->setAttrib('rows', 20)
		     ->addFilter('StringTrim')
		     ->addValidator('NotEmpty');

        $this->addElement("textarea", "article");
	$this->article->setLabel('Cikk:')
		     ->setRequired(true)
		     ->setAttrib('cols', 80)
		     ->setAttrib('rows', 40)
		     ->addFilter('StringTrim')
		     ->addValidator('NotEmpty');

	$this->addElement("select","category");
	$categories = new Application_Model_DbTable_Categories();
	$categories = $categories->fetchAll(null, "id ASC")->toArray();
	foreach ($categories as $category) {
	    $this->category->addMultiOption($category["id"],$category["category"]);
	}

	$this->addElement("submit", "vazlat");
	$this->vazlat->setLabel("Mentés vázlatként");

	$this->addElement("submit", "mentes");
	$this->mentes->setLabel("Közzététel");
    }

}

