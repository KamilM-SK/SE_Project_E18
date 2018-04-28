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
	
	
}

?>