<?php 

class Magazine {
	private $tableName = 'magazine';
	private $ID;
	private $name;
	private $writingDeadline;
	private $revisionDeadline;
	private $designDeadline;
	private $releaseDate;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
	}
	
	public function getLastMagazineID($db) {
		$this->db = $db;
		$sql = 'SELECT ID FROM magazine ORDER BY 1 DESC';
		$result = $this->db->query($sql);
		return $result->fetch_assoc();
	} 
	
}

?>