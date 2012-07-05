<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Adapter
 *
 * @author koczorm
 */
class Zend_Auth_Adapter_Login implements Zend_Auth_Adapter_Interface {
    protected $_username;
    protected $_password;
    
    public function __construct($username,$password)
    {
        $this->_username=$username;
        $this->_password=$password;
    }

    public function authenticate()
    {
        $user = new Application_Model_User();
	$user = $user->getUserByNick($this->_username);
	$uname = $user["nick"];
	$pwd = $user["pwd"];

        if($uname == "") {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,$this->_username);
        }

        elseif($pwd != md5($this->_password)) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,$this->_password);
        }

        else {
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user);
        }
    }
}
?>
