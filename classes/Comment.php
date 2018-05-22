<?php

class Comment {
	private $tableName = "comment";
	private $ID;
	private $article;
	private $user;
	private $commentText;
	private $dateTime;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_POST['comment_button'])) {
			$this->sendComment($_SESSION['user_id'], $_GET['article'], $_GET['magazine']);
		}
	}
	
	private
	function sendComment($user, $article, $magazine) {
		$this->user = $user;
		$this->article = $article;
		$this->commentText = $_POST['comment'];
		
		$stmt = $this->db->prepare('INSERT INTO comment(article, user, comment_text) VALUES(?, ?, ?)');
		$stmt->bind_param("iis", $this->article, $this->user, $this->commentText);
		
		if ($stmt->execute()) {
			header('location: allarticles.php?magazine='.$magazine.'&article='.$this->article);
		}
	}
	
	public
	function fetchAllCommentsForSpecifiedArticle($article, $db) {
		$this->db = $db;
		$this->article = $article;
		
		$sql = 'SELECT * FROM comment WHERE article = '.$this->article;
		return $this->db->query($sql);
	}
}

?>