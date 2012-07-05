<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail
 *
 * @author Tiger
 */
class Zend_Custom_Mail {

    protected $_transport = null;
    public $mail;

    public function  __construct($to, $subject, $data, $template, $intro = null) {
	$html = new Zend_View();
	$html->setScriptPath(APPLICATION_PATH . '/views/scripts/emails/');

	// assign valeues
	$html->assign('data', $data);
	$html->assign('subject', $subject);
	$html->assign('name', $to["nick"]);
	$html->assign('intro', $intro);
	$html->assign('mail', $to["mail"]);

	// render view
	$bodyText = $html->render($template);

	$this->mail = new Zend_Mail('utf-8');
	$this->mail->addTo($to["mail"]);
	$this->mail->setBodyHtml($bodyText);
	$this->mail->setSubject($subject);
	$this->mail->setFrom("szakdoga.noreply@gmail.com", "Szakdoga Neválaszolj");
    }

    public function send() {
	if (!$this->_transport) {
	    $mailhost= 'smtp.gmail.com';
	    $mailconfig = array('auth' => 'login',
				'username' => 'szakdoga.noreply@gmail.com',
				'password' => '199101200545',
				'port'    =>  "465",
				'ssl'    =>   'ssl');
	    $this->_transport = new Zend_Mail_Transport_Smtp($mailhost, $mailconfig);
	}
	
	$this->mail->send($this->_transport);
    }
}
?>
