<?php

class Application_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{

    protected $_name = 'articles';
    protected $_rowClass = 'Application_Model_Row_Article';
    protected $_referenceMap = array('User' => array(
					'columns' => 'author',
					'refTableClass' => 'Application_Model_DbTable_User',
					'refColumns' => 'id'
				    ),
				    'Category' => array(
					'columns' => 'category',
					'refTableClass' => 'Application_Model_DbTable_Categories',
					'refColumns' => 'id'
				    )
				);

    public function getAllArticle($where = null, $order = null) {
	$articles = $this->fetchAll($where, $order);
	return $articles;
    }

    public function getArticleById($id) {
        $id = (int)$id;
        $row = $this->fetchRow('id = '.$id);
        if (!$row) {
            //throw new Exception("Could not find row $id");
        }
        return $row;
    }

    public function getArticleByTitle($title) {
        $row = $this->fetchRow('title = '.$title);
        if (!$row) {
            return null;
        }
        return $row;
    }

    public function getArticleByAuthor($id) {
        $id = (int) $id;
        $row = $this->fetchRow('author = '.$id);
        if (!$row) {
            return null;
        }
        return $row;
    }

    public function addArticle($data) {
        $this->insert($data);
    }

    public function updateArticle($id, $data) {
        $data["modified_date"] = date("Y:m:d H:i:s");
        $this->update($data, "id = ".$id);
    }

    public function deleteArticle($id) {
        $this->delete("id = ".$id);
    }

    public static function showArticleBox($articles,$title=null,$fresh=false) {
	$friss = "";
	if ($fresh) {
	    $friss = " fresh";
	}
	echo "<div class='box".$friss."'>";
	if ($title) {
	    echo '<h2 class="box_name box_head">'.$title.'</h2>';
	}
	foreach ($articles as $article) {
	    self::showArticleShort($article,true);
	}
	echo "</div>";
    }

    public static function showArticleShort($article,$box = false) {
	if ($box) {
	    $class = "box_title";
	}
	else {
	    $class = "article_title";
	}

	if (!is_object($article)) {
	    $articles = new Application_Model_DbTable_Articles();
	    $article = $articles->getArticleById((int)$article);
	}

	echo "<div class='article'><div class='article_head'>";
	echo "<h2 class='".$class."'>".$article["title"]."</h2>";
	echo $article->getUsername()." / ".str_replace("-", ". ", $article["create_date"]);
	echo "</div>";
	echo "<div class='article_content'>";
	echo $article["lead"];
	echo "</div>";
	echo "<div class='article_footer'>";
	echo "<a href='/cikk/".$article["id"]."'>Tovább >></a>";
	echo "</div>";
	echo "</div>";
    }

}

