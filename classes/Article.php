<?php

class Article {
	private $tableName = 'article';
	private $ID;
	private $title;
	private $description;
	private $section;
	private $pageNumber;
	private $articleText;
	private $magazine;
	private $status;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_POST['suggest_article'])) {
			$this->sendArticleSuggestion();
		}
		
		if (isset($_POST['select_journalist'])) {
			$this->setJournalistToArticle();
		}
		
		if (isset($_POST['update_suggest_article'])) {
			$this->updateSuggestedArticle($_GET['id']);
		}
		
	}
	
	private function sendArticleSuggestion() {
		$this->title = $this->db->real_escape_string($_POST['title']);
		$this->section = $this->db->real_escape_string($_POST['section']);
		$this->description = $this->db->real_escape_string($_POST['description']);
		$this->pageNumber = $_POST['page_number'];
		$this->articleText = "You can start to write your article here...";
		$this->status = 1;
		$this->magazine = 17; //Change this in sprint 2;
		
		$stmt = $this->db->prepare('INSERT INTO article(title, description, section, page_number, article_text,
		magazine, status) VALUES(?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param("sssisii", $this->title, $this->description, $this->section, $this->pageNumber, $this->articleText, $this->magazine, $this->status);
		
		if ($stmt->execute()) {
			header('location: suggestarticles.php?article='.$this->db->insert_id.'&user='.$_SESSION['user_id']);
		}
		else {
			header('location: suggestarticles.php?status=999');
		}
	}
	
	private function setJournalistToArticle() {
		$workingType = 2;
        $this->ID = $_POST['article'];
		$member = $_POST['member'];
		
		$sql = 'UPDATE article SET status = 3 WHERE ID = '.$_POST['article'];
		$this->db->query($sql);
				
		$stmt = $this->db->prepare('INSERT INTO user_by_article(article, user, working_type) VALUES(?, ?, ?)');
		$stmt->bind_param("iii", $this->ID, $member, $workingType);
		$stmt->execute();
		
		header('location: assignarticle.php?status=1000');
	}
	
	private function updateSuggestedArticle($ID) {
		$this->ID = $ID;
		$this->title = $this->db->real_escape_string($_POST['title']);
		$this->section = $this->db->real_escape_string($_POST['section']);
		$this->description = $this->db->real_escape_string($_POST['description']);
		$this->pageNumber = $_POST['page_number'];
		
		$sql = 'UPDATE article SET title = "'.$this->title.'", section = "'.$this->section.'", description = "'.$this->description.'", page_number = '.$this->pageNumber.' WHERE ID = '.$this->ID;
		$this->db->query($sql);
	}


	public function fetchSuggestedArticleDataByID($ID, $db) {
		$this->db = $db;
		$this->ID = $ID;
		
		$sql = 'SELECT * FROM article WHERE ID = '.$this->ID;
		$result = $this->db->query($sql);
		return $result->fetch_assoc();
	}	
	
	public function updateArticleSetItForWriting($user, $db) {
		$workingType = 2;
		$this->db = $db;
		
		if(!empty($_POST['article_id'])) {
    		foreach($_POST['article_id'] as $ID) {
            	$this->ID = $ID;
				$sql = 'UPDATE article SET status = 3 WHERE ID = '.$this->ID;
				$this->db->query($sql);
				
				$stmt = $this->db->prepare('INSERT INTO user_by_article(article, user, working_type) VALUES(?, ?, ?)');
				$stmt->bind_param("iii", $this->ID, $user, $workingType);
				$stmt->execute();
    		}
		}
		
		header('location: http://botticelliproject.com/stak/admin/myarticles.php?user='.$user);
	}
	
	
	
	public function checkIfArticleExists($ID, $db) {
		$this->db = $db;
		$this->ID = $ID;
		
		$sql = 'SELECT * FROM article WHERE ID = '.$this->ID;
		$result = $this->db->query($sql);
		
		if ($result->num_rows == 1) {
			return 1;
		}
		else {
			return 0;
		}
	}
	
	public function approveSuggestedArticle($ID, $db) {
		$this->db = $db;
		$this->ID = $ID;
		
		$sql = 'UPDATE article SET status = 2 WHERE ID = '.$this->ID;

		if ($this->db->query($sql)) {
			header('location: ../../admin/suggestarticles.php?token=420&notification=true&members=all&article='.$this->ID);
		}
	}
	
	public function fetchAllArticlesAvailableForWriting($db, $ID) {
		$this->db = $db;
		$sql = 'SELECT * FROM article WHERE status = 2';
		return $this->db->query($sql);
	}
	
	public function declineSuggestedArticle($ID, $db){
		$this->db = $db;
		$this->ID = $ID;
		
		$sql = 'DELETE FROM article WHERE ID = '.$this->ID;
		
		if ($this->db->query($sql)){
			header('location: ../../admin/suggestarticles.php?status=100');
		}
	}
	
	public function fetchAllArticlesForSelectedMagazine($magazine, $db) {
		$this->db = $db;
		$this->magazine = $magazine;
		
		$sql = 'SELECT ID, title FROM article WHERE status = 3 AND magazine = '.$this->magazine;
		return $this->db->query($sql);
	}
	
	public function fetchArticleData($ID, $db) {
		$this->db = $db;
		$this->ID = $ID;
		
		$sql = 'SELECT * FROM article WHERE ID = '.$this->ID;
		$result = $this->db->query($sql);
		return $result->fetch_assoc();
	}
	
}

?>