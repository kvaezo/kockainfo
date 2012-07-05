<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';
    protected $_rowClass = "Application_Model_Row_User";
    protected $_dependentTables = array('Application_Model_DbTable_Articles');

    public function getUserByNick($nick) {
	$row = $this->fetchRow("nick = '".$nick."'");
	if (!$row) {
	    return null;
	}

	return $row->toArray();
    }

    public function getUserIdByNick($nick) {
	$row = $this->fetchRow("nick = '".$nick."'");
	if (!$row) {
	    return null;
	}

	$data = $row->toArray();
	$id = $data["id"];

	return $id;
    }

    public function getUserById($id) {
	$row = $this->fetchRow("id = '".$id."'");
	if (!$row) {
	    return null;
	}

	return $row->toArray();
    }

    public function getUserNickById($id) {
	$row = $this->fetchRow("id = '".$id."'");
	if (!$row) {
	    return null;
	}

	$data = $row->toArray();
	$nick = $data["nick"];

	return $nick;
    }
}

