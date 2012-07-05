<?php

class AdminController extends Zend_Controller_Action
{

    public function preDispatch()
    {
	$auth = Zend_Auth::getInstance();
	if (!$auth->hasIdentity()) {
	    if ($this->_getParam("idozitett") != 1 && $this->_getParam("action") == "send-nl") {
		$this->_helper->redirector('index', 'index');
	    }
	    elseif ($this->_getParam("action") != "send-nl") {
		$this->_helper->redirector('index', 'index');
	    }
	}
	$this->view->__set("admin_user", true);
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function newArticleAction()
    {
	$form = new Application_Form_Article();
        $this->view->form = $form;

	$request = $this->getRequest();
	if ($request->isPost()) {
	    $article = new Application_Model_DbTable_Articles;

	    if ($_POST["mentes"]) {
		$_POST["public"] = 1;
	    }
	    else {
		$_POST["public"] = 0;
	    }

	    unset ($_POST["vazlat"]);
	    unset ($_POST["mentes"]);
	    $article->addArticle($_POST);
	    $this->view->__set("ment", "Sikeres mentés");

	    $form->populate($_POST);
	}
    }

    public function newCategoryAction()
    {
        // action body
    }

    public function editArticleAction()
    {
        $form = new Application_Form_Article();
	$articles = new Application_Model_DbTable_Articles();

	$article = $articles->find($this->getRequest()->getParam("id"))->toArray();

        $this->view->form = $form;
	$form->populate($article[0]);

	$request = $this->getRequest();
	if ($request->isPost()) {
	    $article = new Application_Model_DbTable_Articles;

	    if ($_POST["mentes"]) {
		$_POST["public"] = 1;
	    }
	    else {
		$_POST["public"] = 0;
	    }

	    unset ($_POST["vazlat"]);
	    unset ($_POST["mentes"]);
	    $article->addArticle($_POST);
	    $this->view->__set("ment", "Sikeres mentés");

	    $form->populate($_POST);
	}
	
    }

    public function viewAllArticlesAction()
    {
	$articles_db = new Application_Model_DbTable_Articles();
        $articles = $articles_db->fetchAll();

	$this->view->__set("articles", $articles);
    }

    public function categoriesAction()
    {
        $articles = new Application_Model_DbTable_Articles();
	$articles = $articles->fetchAll();

	$this->view->__set("articles", $articles);
    }

    public function newNlAction() {
	$form = new Application_Form_Nl();
	$this->view->form = $form;

	if ($this->_request->isPost()) {

	    $formData = $this->_request->getPost();
	    if ($form->isValid($formData)) {
		/* Uploading Document File on Server */
		$upload = new Zend_File_Transfer_Adapter_Http();
		$upload->setDestination("./uploads/files/");


		try {
		    // upload received file(s)
		    $upload->receive();
		} catch (Zend_File_Transfer_Exception $e) {
		}

		// New Code For Zend Framework :: Rename Uploaded File
		$renameFile = $upload->getFileName('file_path', false);

		$fullFilePath = APPLICATION_PATH.'/views/scripts/emails/'.$renameFile;

		// Rename uploaded file using Zend Framework
		$filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));

		try {
		    $filterFileRename->filter($upload->getFileName('file_path'));
		} catch (Zend_File_Transfer_Exception $e) {
		    $e->getMessage();
		}

		$data["title"] = $formData["title"];
		$data["filename"] = $renameFile;

		$nl = new Application_Model_DbTable_Nl();
		$nl->insert($data);
	    }
	}
    }

    public function createNewsletterAction() {
	$form = new Application_Form_Newsletter();
	$this->view->form = $form;

	if ($this->_request->isPost()) {
	    $data = $this->_request->getPost();
	    $data["articles"] = implode(",", $data["articles"]);
	    unset ($data["submit"]);

	    $newsletter = new Application_Model_DbTable_Newsletter();
	    $newsletter->insert($data);
	    $this->view->message = "Sikeres mentés!";
	}
    }

    public function editNlAction() {
	$form = new Application_Form_Newsletter();
	$newsletters = new Application_Model_DbTable_Newsletter();
	$newsletter = $newsletters->find((int)$this->getRequest()->getParam("id"))->current()->toArray();
	$articles = explode(",", $newsletter["articles"]);
	$newsletter["articles"] = $articles;

	$form->populate($newsletter);
	$this->view->form = $form;

	if ($this->_request->isPost()) {
	    $data = $this->_request->getPost();
	    $data["articles"] = implode(",", $data["articles"]);
	    unset ($data["submit"]);

	    $newsletters->update($data,"id = '".$this->getRequest()->getParam("id")."'");
	    $this->view->message = "Sikeres mentés!";

	    $form->populate($this->_request->getPost());
	    $this->view->form = $form;
	}
    }

    public function newslettersAction() {
	$nls = new Application_Model_DbTable_Newsletter();
	$this->view->__set("nls", $nls->fetchAll(null, "id ASC"));
    }

    public function templatesAction() {
	$nls = new Application_Model_DbTable_Nl();
	$this->view->__set("nls", $nls->fetchAll(null, "id ASC"));
    }

    public function sendNlAction() {
	$form = new Application_Form_SendNl();
	$this->view->form = $form;

	if ($this->_request->isPost()) {
	    $post = $this->_request->getPost();
	    $users = new Application_Model_DbTable_User();
	    $newsletters = new Application_Model_DbTable_Newsletter();
	    $articles = new Application_Model_DbTable_Articles();

	    if ($post["year"]) {
		$sendings = new Application_Model_DbTable_Sending();
		$date = $post["year"]."-".$post["month"]."-".$post["day"]." ".$post["hour"].":".$post["minute"].":00";
		$data = array(
		    "newsletter_id" => $post["newsletter"],
		    "template" => $post["templates"],
		    "send_date" => $date
		);
		$sendings->insert($data);
	    }
	    else {
		$users = $users->fetchAll("newsletter = '1' AND verified = '1'");
		$newsletter = $newsletters->find((int)$post["newsletter"])->current()->toArray();
		$articles = $articles->fetchAll("id IN (".$newsletter["articles"].")", "create_date DESC");

		foreach ($users as $user) {
		    $mail = new Zend_Custom_Mail($user, $newsletter["title"], $articles, $post["templates"], $newsletter["lead"]);
		    $mail->send();
		}
	    }
	}
	elseif ($this->_getParam("idozitett") == 1) {
	    $users = new Application_Model_DbTable_User();
	    $newsletters = new Application_Model_DbTable_Newsletter();
	    $articles = new Application_Model_DbTable_Articles();
	    $sendings = new Application_Model_DbTable_Sending();

	    $sendings = $sendings->fetchAll("send_date = '".date('Y-m-d H:i').":00'");

	    foreach ($sendings as $sending) {
		$users = $users->fetchAll("newsletter = '1' AND verified = '1'");
		$newsletter = $newsletters->find((int)$sending["newsletter_id"])->current()->toArray();
		$articles = $articles->fetchAll("id IN (".$newsletter["articles"].")", "create_date DESC");

		foreach ($users as $user) {
		    $mail = new Zend_Custom_Mail($user, $newsletter["title"], $articles, $sending["template"], $newsletter["lead"]);
		    $mail->send();
		}
	    }
	}
    }

    public function sendingsAction() {
	
    }
}













