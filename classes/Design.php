<?php

class Design {
	private $tableName = "design";
	private $user;
	private $article;
	private $location;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_GET['magazine']) && isset($_GET['article'])) {
			$this->rejectDesign($_GET['magazine'], $_GET['article']);
		}
	}
	
	private function rejectDesign($magazine, $article) {
		$this->article = $article;
		
		$sql = 'SELECT location FROM design WHERE article = '.$this->article;
		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		
		$location = '../articledesign/'.$row['location'];
		unlink($location);
		
		$sql = 'DELETE FROM design WHERE article = '.$this->article;
		$this->db->query($sql);
		
		$sql = 'UPDATE article SET status = 5 WHERE ID = '.$this->article;
		$this->db->query($sql);
		
		header ('location: designs.php?magazine='.$_GET['magazine'].'&status=rejected');
	}
	
	public function bindDesignToArticleAndUser($user, $article, $location, $db) {
		$this->db = $db;
		$this->user = $user;
		$this->article = $article;
		$this->location = $location;
		
		$stmt = $this->db->prepare("INSERT INTO design VALUES(?, ?, ?)");
		$stmt->bind_param("iis", $this->user, $this->article, $this->location);
		$stmt->execute();
	}
	
	public function fetchAllDesignsForMagazine($magazine, $db) {
		$this->db = $db;
		
		$sql = 'SELECT
					design.location,
					user.first_name,
					user.last_name,
					article.title,
					article.ID,
					article.page_number
				FROM
					design
				JOIN user ON design.user = user.ID
				JOIN article ON design.article = article.ID
				JOIN magazine ON article.magazine = magazine.ID
				WHERE magazine.ID = '.$magazine;
		
		return $this->db->query($sql);
	}
}

?>