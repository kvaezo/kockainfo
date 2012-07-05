<?php

class Application_Model_User
{
    private $_table;

    public function  __construct() {
	
    }

    public function setTable($table)
    {
        if (is_string($table)) {
            $table = new $table();
        }
        if (!$table instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Érvénytelen tábladefiníció');
        }
        $this->_table = $table;
        return $this;
    }

    public function getTable() {
        if (null === $this->_table) {
            $this->setTable('Application_Model_DbTable_User');
        }
        return $this->_table;
    }

    public function getUserByNick($nick) {
	return $this->getTable()->getUserByNick($nick);
    }

}

