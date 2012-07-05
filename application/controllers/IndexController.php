<?php

class IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        Zend_Layout::getMvcInstance()->setLayout('layout');
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            if ($user["right"] == 2) {
            $this->view->__set("admin_user", true);
            }
            else {
            $this->view->__set("user", true);
            }
        }

        $params = $this->_getAllParams();
        if ($params["action"] == "search" && preg_match("/\?/", $_SERVER["REQUEST_URI"])) {
            $this->_helper->redirector("search", "index", null, array("key" => $params["key"]));
        }
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $articles = new Application_Model_DbTable_Articles();
	$this->view->__set("articles", $articles->fetchAll(null,"create_date DESC"));
    }

    public function articleAction() {
	$articles = new Application_Model_DbTable_Articles();
	$article = $articles->find($this->_getParam("id"))->current();

	$this->view->__set("article", $article);
    }

    public function polgariAction() {
	$articles = new Application_Model_DbTable_Articles();
	$articles = $articles->fetchAll("category = '1'","create_date DESC");

	foreach ($articles as $article) {
	    $article["author"] = $article->getUsername();
	    $arts[] = $article;
	}

	if (count($arts)) {
	    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($arts));
	    $paginator->setDefaultItemCountPerPage(10);
	    $paginator->setCurrentPageNumber($this->_getParam("page"));
	    $this->view->paginator = $paginator;
	}
	else {
	    $this->view->message = "Sajnos ebben a témában még nincs egyetlen hírünk sem. Gyere vissza később!";
	}
    }

    public function katonaiAction() {
	$articles = new Application_Model_DbTable_Articles();
	$articles = $articles->fetchAll("category = '2'","create_date DESC");

	foreach ($articles as $article) {
	    $article["author"] = $article->getUsername();
	    $arts[] = $article;
	}

	if (count($arts)) {
	    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($arts));
	    $paginator->setDefaultItemCountPerPage(10);
	    $paginator->setCurrentPageNumber($this->_getParam("page"));
	    $this->view->paginator = $paginator;
	}
	else {
	    $this->view->message = "Sajnos ebben a témában még nincs egyetlen hírünk sem. Gyere vissza később!";
	}
    }

    public function helikopterAction() {
	$articles = new Application_Model_DbTable_Articles();
	$articles = $articles->fetchAll("category = '3'","create_date DESC");

	foreach ($articles as $article) {
	    $article["author"] = $article->getUsername();
	    $arts[] = $article;
	}

	if (count($arts)) {
	    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($arts));
	    $paginator->setDefaultItemCountPerPage(10);
	    $paginator->setCurrentPageNumber($this->_getParam("page"));
	    $this->view->paginator = $paginator;
	}
	else {
	    $this->view->message = "Sajnos ebben a témában még nincs egyetlen hírünk sem. Gyere vissza később!";
	}
    }

    public function searchAction() {
	$key = $this->_getParam("key");
	$this->view->__set("key", $key);
	$articles = new Application_Model_DbTable_Articles();
	$articles = $articles->fetchAll("title LIKE '%".$key."%' OR lead LIKE '%".$key."%' OR article LIKE '%".$key."%'","create_date DESC");

	foreach ($articles as $article) {
	    $article["author"] = $article->getUsername();
	    $arts[] = $article;
	}

	if (count($arts)) {
	    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($arts));
	    $paginator->setDefaultItemCountPerPage(10);
	    $paginator->setCurrentPageNumber($this->_getParam("page"));
	    $this->view->paginator = $paginator;
	}
	else {
	    $this->view->message = "Nincs keresési találat!";
	}
    }
}

