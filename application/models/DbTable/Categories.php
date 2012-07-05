<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';
    protected $_rowClass = 'Application_Model_Row_Categories';
    protected $_dependentTables = array('Application_Model_DbTable_Categories');

}

