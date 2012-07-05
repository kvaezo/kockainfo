<?php

class LoginController extends Zend_Controller_Action
{

    public function getLoginForm()
    {
        return new Application_Form_Login(array(
            'action' => '/login/process',
            'method' => 'post',
        ));
    }

    public function getRegForm()
    {
        return new Application_Form_Registration(array(
            'action' => '/login/registration',
            'method' => 'post',
        ));
    }

    public function getAuthAdapter(array $params)
    {
        $authAdapter = new Zend_Auth_Adapter_Login($params["username"], $params["password"]);

        return $authAdapter;
    }

    public function preDispatch()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        } else {
            // If they aren't, they can't logout, so that action should
            // redirect to the login form
            if ('logout' == $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index');
            }
	}
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->form = $this->getLoginForm();
	$this->view->__set("regform", $this->getRegForm());
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index','index');
    }

    public function processAction() {
	$request    = $this->getRequest();
	
	if (!$request->isPost()) {
	    return $this->_helper->redirector('index');
	}

	$form = $this->getLoginForm();
	if (!$form->isValid($request->getPost())) {
	    // Invalid entries
	    $this->view->form = $form;
	    $this->view->__set("regform", $this->getRegForm());
	    return $this->render('index'); // re-render the login form
	}

	$authAdapter = $this->getAuthAdapter($_POST);

	// Set the input credential values
	$auth    = Zend_Auth::getInstance();

	// Perform the authentication query, saving the result
	$result = $auth->authenticate($authAdapter);
	$user = $result->getIdentity();

	switch($result->getCode()) {
	    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
		$form->setDescription('Nincs ilyen felhasználó');
		$this->view->form = $form;
		$this->view->__set("regform", $this->getRegForm());
		$this->render('index');
		break;
	    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
		$form->setDescription('Hibás jelszó');
		$this->view->form = $form;
		$this->view->__set("regform", $this->getRegForm());
		$this->render('index');
		break;
	    default:
		break;
	}

	if ($user["right"] == 2) {
	    $this->_helper->redirector('index', 'admin');
	}
	else {
	    $this->_helper->redirector('index', 'user');
	}
    }

    public function registrationAction() {
	$request = $this->getRequest();
	
	if (!$request->isPost()) {
	    return $this->_helper->redirector('index');
	}

	$form = $this->getRegForm();
	if (!$form->isValid($request->getPost())) {
	    $this->view->form = $this->getLoginForm();
	    $this->view->__set("regform", $form);
	    return $this->render('index');
	}
	else {
	    $users = new Application_Model_DbTable_User();
	    $data = $request->getPost();

	    if (!$users->getUserByNick($data["nick"])) {
		$form->setDescription('Ez a felhasználónév már foglalt!');
		$this->view->form = $this->getLoginForm();
		$this->view->__set("regform", $form);
		return $this->render('index');
	    }
	    elseif ($data["pwd"] != $data["pwd2"]) {
		$form->setDescription('Nem egyezik a két jelszó!');
		$this->view->form = $this->getLoginForm();
		$this->view->__set("regform", $form);
		return $this->render('index');
	    }
	    elseif ($data["agree"] != 1) {
		$form->setDescription('A regisztrációhoz el kell fogadnod a szabályzatot!');
		$this->view->form = $this->getLoginForm();
		$this->view->__set("regform", $form);
		return $this->render('index');
	    }
	    else {
		$data["pwd"] = md5($data["pwd"]);
		unset ($data["pwd2"]);
		unset ($data["submit"]);
		unset ($data["agree"]);

		$mailhost= 'smtp.gmail.com';
		$mailconfig = array('auth' => 'login',
				    'username' => 'szakdoga.noreply@gmail.com',
				    'password' => '199101200545',
				    'port'    =>  "465",
				    'ssl'    =>   'ssl');
		$transport = new Zend_Mail_Transport_Smtp($mailhost, $mailconfig);
		Zend_Mail::setDefaultTransport($transport);

		$html = new Zend_View();
		$html->setScriptPath(APPLICATION_PATH . '/views/scripts/emails/');

		// assign valeues
		$html->assign('nick', $data["nick"]);
		$html->assign('mail', $data["mail"]);

		// create mail object
		$mail = new Zend_Mail('utf-8');

		// render view
		$bodyText = $html->render('registration.phtml');

		$mail = new Zend_Mail('utf-8');
		$mail->addTo($data["mail"]);
		$mail->setBodyHtml($bodyText);
		$mail->setSubject("Regisztráció");
		$mail->setFrom("szakdoga.noreply@gmail.com", "Szakdoga Neválaszolj");
		$mail->send($transport);

		//$users->insert($data);
	    }
	}
    }

    public function getVerifiedAction() {
	$nick = base64_decode($this->_getParam("code"));
	$users = new Application_Model_DbTable_User();

	$users->update(array('verified' => 1), "nick = '".$nick."'");
    }
}



