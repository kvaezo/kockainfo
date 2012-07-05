<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
	if ($auth->hasIdentity()) {
	    $user = Zend_Auth::getInstance()->getIdentity();
	    $update_mail = new Application_Form_UpdateMail();
	    $update_pwd = new Application_Form_UpdatePwd();

	    $this->view->__set("update_mail", $update_mail);
	    $this->view->__set("update_pwd", $update_pwd);
	    $this->view->__set("nick", $user["nick"]);
	}
	else {
	    $this->_helper->redirector("index","index");
	}
    }


}

