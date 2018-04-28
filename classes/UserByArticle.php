<?php

class UserByArticle{
	
	private $tableName = 'user_by_article';
	private $ID;
	private $user;
	private $article;
	private $workingType;
	private $db;
	
	function __construct($db){
		$this->db = $db;
		
		if (isset($_GET['article']) && isset($_GET['user'])) {
			$this->setUserByArticleAsSuggestor();
		}
	}
	
	private function setUserByArticleAsSuggestor() {
		$this->user = $_GET['user'];
		$this->article = $_GET['article'];
		$this->workingType = 1;
		
		$stmt = $this->db->prepare('INSERT INTO user_by_article(user, article, working_type) VALUES(?, ?, ?)');
		$stmt->bind_param("iii", $this->user, $this->article, $this->workingType);
		
		if ($stmt->execute()) {
			header('location: suggestarticles.php?notifyaboutsuggestedarticle=true&user='.$this->user);
		}
		
		else {
			header('location: suggestarticles.php?status=999');
		}
	}
	
}

?>