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
	
	public function getDeadlineForWriting($ID, $db) {
		$this->ID = $ID;
		$this->db = $db;
		$sql = 'SELECT writing_deadline FROM magazine WHERE ID = '.$this->ID;
		$result = $this->db->query($sql);
		$result = $result->fetch_assoc();
		return $result['writing_deadline'];
	}
	
}

?>