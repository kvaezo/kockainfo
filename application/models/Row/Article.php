<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Article
 *
 * @author Tiger
 */
class Application_Model_Row_Article extends Zend_Db_Table_Row_Abstract {
    private $user = null;
    private $category = null;

    public function getUsername()
    {
	if (!$this->user) {
	    $this->user = $this->findParentRow('Application_Model_DbTable_User');
	}

	if (is_object($this->user)) {
	    $user = $this->user->toArray();
	    return $user["nick"];
	}
	else {
	    return $this->user;
	}
    }

    public function getCategory()
    {
	if (!$this->category) {
	    $this->category = $this->findParentRow('Application_Model_DbTable_Categories');
	}

	if (is_object($this->category)) {
	    $category = $this->category->toArray();
	    return $category["category"];
	}
	else {
	    return $this->category;
	}
    }
}
?>
