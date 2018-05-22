<?php

class System {
	private $db;
	
	function __construct($db) {
		$this->db = $db;
	}
	
	private function pushAllActiveMembersID($db) {
		$this->db = $db;
		$sql = 'SELECT ID FROM user WHERE user_type < 6';
		return $this->db->query($sql);
	}
	
	private
	function issueDeadlineNotification($notifyKey, $deadline, $db) {
		$this->db = $db;
		
		switch ($notifyKey) {
			case 0: {
				$memberList = $this->pushAllActiveMembersID($this->db);
				
				while ($list = $memberList->fetch_assoc()) {
					$sql = 'INSERT INTO notification VALUES(
							DEFAULT, DEFAULT, 
							"Writing deadline ends on '.$deadline.'! Be sure to submit and finish your articles!",
							DEFAULT, 2, '.$list['ID'].', DEFAULT)';
					$this->db->query($sql);
				}
				break;
			}
			case 100: {
				$memberList = $this->pushAllActiveMembersID($this->db);
				
				while ($list = $memberList->fetch_assoc()) {
					$sql = 'INSERT INTO notification VALUES(
							DEFAULT, DEFAULT, 
							"Revision deadline ends on '.$deadline.'! Be sure to revise your articles!",
							DEFAULT, 2, '.$list['ID'].', DEFAULT)';
					$this->db->query($sql);
				}
				break;
			}
			case 110: {
				$memberList = $this->pushAllActiveMembersID($this->db);
				
				while ($list = $memberList->fetch_assoc()) {
					$sql = 'INSERT INTO notification VALUES(
							DEFAULT, DEFAULT, 
							"Design deadline ends on '.$deadline.'! Be sure to submit your articles!",
							DEFAULT, 2, '.$list['ID'].', DEFAULT)';
					$this->db->query($sql);
				}
				break;
			}
		}
		
	}
	
	public
	function submitAllArticlesAfterDeadlineHasPassed($db) {
		$this->db = $db;
		
		$sql = 'SELECT ID, writing_deadline FROM magazine ORDER BY 1 DESC LIMIT 1';
		$magazine = $this->db->query($sql);
		$magazine = $magazine->fetch_assoc();
		
		$writingDeadline = $magazine['writing_deadline'];
		$currentMagazine = $magazine['ID'];
		
		$sql = 'SELECT ID FROM article WHERE status = 3 AND magazine = '.$currentMagazine;
		$result = $this->db->query($sql);
		
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$writingDeadline = strtotime($writingDeadline);
				$currentTime = time();
				if ($writingDeadline - $currentTime <= 0) {
					$sql = 'UPDATE article SET status = 4 WHERE ID = '.$row['ID'];
					$this->db->query($sql);
				}
			}	
		}
	}
	
	public
	function checkIfNotificationHasToBeIssued($notifykey, $magazineID, $db) {
		$this->db = $db;
		
		switch ($notifykey) {
			case 0: {
				$sql = 'SELECT writing_deadline FROM magazine WHERE ID = '.$magazineID;
				$result = $this->db->query($sql);
				$result = $result->fetch_assoc();
				
				$writingDeadline = $result['writing_deadline'];
				$currentTime = time();
				
				if (strtotime($writingDeadline) - $currentTime <= 172800) {
					$this->issueDeadlineNotification(0, $writingDeadline, $this->db);
					
					$sql = 'UPDATE magazine SET notify = 100 WHERE ID = '.$magazineID;
					$this->db->query($sql);
				}
				break;
			}
			case 100: {
				$sql = 'SELECT revision_deadline FROM magazine WHERE ID = '.$magazineID;
				$result = $this->db->query($sql);
				$result = $result->fetch_assoc();
				
				$revisionDeadline = $result['revision_deadline'];
				$currentTime = time();
				
				if (strtotime($revisionDeadline) - $currentTime <= 172800) {
					$this->issueDeadlineNotification(100, $revisionDeadline, $this->db);
					
					$sql = 'UPDATE magazine SET notify = 110 WHERE ID = '.$magazineID;
					$this->db->query($sql);
				}
				break;
			}
			case 110: {
				$sql = 'SELECT design_deadline FROM magazine WHERE ID = '.$magazineID;
				$result = $this->db->query($sql);
				$result = $result->fetch_assoc();
				
				$designDeadline = $result['design_deadline'];
				$currentTime = time();
				
				if (strtotime($designDeadline) - $currentTime <= 172800) {
					$this->issueDeadlineNotification(110, $designDeadline, $this->db);
					
					$sql = 'UPDATE magazine SET notify = 111WHERE ID = '.$magazineID;
					$this->db->query($sql);
				}
				break;
			}
		}
	}
	
}

?>