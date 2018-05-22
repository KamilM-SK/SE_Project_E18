<?php 

class Magazine {
	private $tableName = 'magazine';
	private $ID;
	private $name;
	private $writingDeadline;
	private $revisionDeadline;
	private $designDeadline;
	private $releaseDate;
	private $status;
	private $finalLocation;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_POST['create_magazine'])) {
			$this->createNewMagazine();
		}
	}
	
	private function createNewMagazine() {
		$runFunction = $this->checkIfLastIssueIsClosed($this->db);
		
		if ($runFunction) {
			$this->writingDeadline = $this->db->real_escape_string($_POST['writing_deadline']);
			$this->revisionDeadline = $this->db->real_escape_string($_POST['revision_deadline']);
			$this->designDeadline = $this->db->real_escape_string($_POST['design_deadline']);
			$this->releaseDate = $this->db->real_escape_string($_POST['release_date']);

			$lastID = $this->getLastMagazineID($this->db);
			$this->ID = $lastID['ID'] + 1;
			$this->name = "Number $this->ID";

			$sql = 'INSERT INTO magazine(ID, name, writing_deadline, revision_deadline, design_deadline, release_date, status)
					VALUES('.$this->ID.',  "'.$this->name.'", "'.$this->writingDeadline.'", "'.$this->revisionDeadline.'", "'.$this->designDeadline.'",
					 "'.$this->releaseDate.'", 0)';

			if ($this->db->query($sql)) {
				header('location: ../admin/magazine.php?status=success');
			} else {
				header('location: ../admin/magazine.php?status=fail');
			}
		}
		
		else {
			header('location: ../admin/magazine.php?status=issuenotclosed');
		}
	}
	
	private function checkIfLastIssueIsClosed($db) {
		$this->db = $db;
		$sql = 'SELECT ID, status FROM magazine ORDER BY 1 DESC';
		$result = $this->db->query($sql);
		
		$row = $result->fetch_assoc();
		$this->status = $row['status'];
		
		if ($row['status'] == 0) {
			return false;
		} else {
			return true;
		}
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
	
	public function getDeadlineForReviewing($ID, $db) {
		$this->ID = $ID;
		$this->db = $db;
		$sql = 'SELECT revision_deadline FROM magazine WHERE ID = '.$this->ID;

		$result = $this->db->query($sql);
		$result = $result->fetch_assoc();
		return $result['revision_deadline'];
	}
	
	public function getDeadlineForDesign($ID, $db) {
		$this->ID = $ID;
		$this->db = $db;
		$sql = 'SELECT design_deadline FROM magazine WHERE ID = '.$this->ID;

		$result = $this->db->query($sql);
		$result = $result->fetch_assoc();
		return $result['design_deadline'];
	}
	
	public function fetchAllMagazines($db) {
		$this->db = $db;
		
		$sql = 'SELECT * FROM magazine';
		return $this->db->query($sql);
	}
	
	public function setMagazineToFinished($finalLocation, $ID, $db) {
		$this->finalLocation = $finalLocation;
		$this->ID = $ID;
		
		$sql = 'UPDATE magazine SET final = "'.$this->finalLocation.'", status = 1 WHERE ID = '.$this->ID;
		$this->db->query($sql);
		
		$sql = 'UPDATE article SET status = 6 WHERE magazine = '.$this->ID;
		$this->db->query($sql);
	}
	
	public function getMagazineReleaseDate($ID, $db){
        $this->ID = $ID;
        $this->db = $db;
        $sql = 'SELECT release_date FROM magazine WHERE ID = '.$this->ID;
        $result = $this->db->query($sql);
        $result = $result->fetch_assoc();
        return $result['release_date'];
    }
	
	public function issueNotifyKey($ID, $db) {
		$this->ID = $ID;
		$this->db = $db;
		
		$sql = 'SELECT notify FROM magazine WHERE ID = '.$this->ID;
		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		return $row['notify'];
	}
	
}

?>