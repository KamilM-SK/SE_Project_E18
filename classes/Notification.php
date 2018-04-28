<?php 

class Notification {
	private $tableName = 'notification';
	private $ID;
	private $time;
	private $description;
	private $author;
	private $notificationType;
	private $reciever;
	private $seen;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_GET['register_status']) && $_GET['register_status'] == 1000) {
			$this->sendNotificationToAllMembersAboutRegisteredUser($_GET['username']);
		}
		
		if (isset($_GET['notifyaboutsuggestedarticle']) && $_GET['notifyaboutsuggestedarticle'] == 'true' && isset($_GET['user'])) {
			$this->sendNotificationToEditorAboutNewSuggestion();
		}
	}
	
	private function sendNotificationToAllMembersAboutRegisteredUser($username) {
		$this->notificationType = 1;
		$this->description = 'Welcome new user: '."$username".'!';
		
		$sql = 'SELECT ID FROM user';
		$result = $this->db->query($sql);
		
		while ($row = $result->fetch_assoc()){
			$this->reciever = $row['ID'];
			$stmt = $this->db->prepare("INSERT INTO notification ( description, notification_type, reciever) VALUES(?, ?, ?)");
			$stmt->bind_param("sii", $this->description, $this->notificationType, $this->reciever);
			
			if ($stmt->execute()) {
				header('location: register.php?status=registered');
			}
			else {
				header('location: register.php?status=fail');
			}
		}
	}
	
	private function sendNotificationToEditorAboutNewSuggestion() {
		$this->author = $_GET['user'];
		$sql = 'SELECT user_type, username FROM user WHERE ID = '.$this->author;
		$result = $this->db->query($sql);
		$result = $result->fetch_assoc();
		$username = $result['username'];
		
		if ($result['user_type'] == 1) {
			header('location: suggestarticles.php?status=1000');
		}
		
		else {
			$this->notificationType = 2;
			$sql = 'SELECT ID FROM user WHERE user_type = 1';
			$result = $this->db->query($sql);
			$result = $result->fetch_assoc();
			$this->reciever = $result['ID'];
			
			$this->description = $username.' has suggested new topic.';
			
			$stmt = $this->db->prepare("INSERT INTO notification(description, notification_type, reciever, author) VALUES(?, ?, ?, ?)");
			$stmt->bind_param("siii", $this->description, $this->notificationType, $this->reciever, $this->author);
			
			if ($stmt->execute()) {
				header('location: suggestarticles.php?status=1001');
			}
			
		}
	}
	
	public function countAllUnseenNotificationsForUser($reciever, $db) {
		$this->reciever = $reciever;
		$this->db = $db;
		
		$sql = 'SELECT * FROM notification WHERE reciever = '.$reciever.' AND seen IS NULL';
		$result = $this->db->query($sql);
		return $result->num_rows;
	}
	
	public function fetchAllUnseenNotificationsForUser($reciever, $db) {
		$this->reciever = $reciever;
		$this->db = $db;
		
		$sql = 'SELECT * FROM notification WHERE reciever = '.$reciever.' AND seen IS NULL';
		$result = $this->db->query($sql);
		return $result;
	}
	
}

?>