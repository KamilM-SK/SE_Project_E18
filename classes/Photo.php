<?php 

class Photo {
	private $tableName = 'photo';
	private $ID;
	private $name;
	private $user;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
	}
	
	public function addPhotoToDatabase($user, $name, $article, $db) {
		$this->db = $db;
		$this->name = $name;
		$this->user = $user;
		
		$stmt = $this->db->prepare('INSERT INTO photo (name, user) VALUES(?, ?)');
		$stmt->bind_param("si", $this->name, $this->user);
		$stmt->execute();
		
		$sql = 'SELECT ID FROM photo ORDER BY 1 DESC LIMIT 1';
		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		$this->ID = $row['ID'];

		$stmt = $this->db->prepare('INSERT INTO photo_by_article (photo, article) VALUES(?, ?)');
		$stmt->bind_param('ii', $this->ID, $article);
		$stmt->execute();
		
	}
	
	public function listAllPhotos($article, $db) {
		$this->db = $db;
		
		$sql = 'SELECT
					photo.name,
					photo.ID AS "photoID"
				FROM
					photo_by_article
				JOIN photo ON photo_by_article.photo = photo.ID
				WHERE
					photo_by_article.article = '.$article;
		
		$result = $this->db->query($sql);
		return $result;
	}
	
	public function getAllPhotosForArticle($article, $db) {
		$this->db = $db;
		
		$sql = 'SELECT 
					photo.name 
				FROM 
					photo_by_article 
					JOIN photo ON photo_by_article.photo = photo.ID 
					JOIN article ON photo_by_article.article = article.ID 
				WHERE 
				article.ID = '.$article;
		
		$result = $this->db->query($sql);
		return $result;
	}
	
}

?>