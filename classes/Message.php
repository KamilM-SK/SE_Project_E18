<?php 

class Message {
	private $tableName = "message";
	private $ID;
	private $author;
	private $messageText;
	private $reciever;
	private $dateTime;
	private $seen;
	private $chat;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_POST['send_message'])) {
			$this->sendMessageBetweenTwoUsers($_GET['reciever'], $_SESSION['user_id']);
		}
		
		if (isset($_POST['send_message_group'])) {
			$this->sendGroupMessage($_SESSION['user_id'], $_GET['group'], $_GET['chat']);
		}
	}
	
	private
	function setAllMessagesToSeen($author, $reciever, $chat, $db) {
		$this->author = $author;
		$this->reciever = $reciever;
		$this->chat = $chat;
		$this->db = $db;
		
		if ($this->chat == 5) $sql = 'UPDATE message SET seen = 1 WHERE reciever = '.$this->author.
			' AND author = '.$this->reciever.' AND chat = 5';
		else $sql = 'UPDATE message SET seen = 1 WHERE reciever = '.$this->reciever.' AND CHAT = '.$this->chat;
		$this->db->query($sql);
	}
	
	private
	function sendMessageBetweenTwoUsers($reciever, $author) {
		$this->author = $author;
		$this->reciever = $reciever;
		$this->messageText = $_POST['message_text'];
		
		$stmt = $this->db->prepare('INSERT INTO message(author, message_text, reciever, chat) VALUES(?, ?, ?, 5)');
		$stmt->bind_param("isi", $this->author, $this->messageText, $this->reciever);
		$stmt->execute();
	}
	
	private
	function sendGroupMessage($author, $groupType, $chat) {
		$this->author = $author;
		$this->chat = $chat;
		$this->messageText = $_POST['message_text'];
		
		$sql = 'SELECT user.ID FROM user_type JOIN user ON user.user_type = user_type.ID WHERE user_type.ID = '.$groupType.' OR user_type.ID = 1';
		$result = $this->db->query($sql);
		
		while ($row = $result->fetch_assoc()) {
			$this->reciever = $row['ID'];
			
			$stmt = $this->db->prepare('INSERT INTO message(author, message_text, reciever, chat) VALUES(?, ?, ?, ?)');
			$stmt->bind_param("isii", $this->author, $this->messageText, $this->reciever, $this->chat);
			$stmt->execute();
		}
		
	}
	
	public
	function fetchAllMessagesBetweenTwoUsers($author, $reciever, $db) {
		$this->db = $db;
		$this->author = $author;
		$this->reciever = $reciever;
		
		$this->setAllMessagesToSeen($this->author, $this->reciever, 5, $this->db);
		
		$sql = 'SELECT * FROM message
				WHERE (author = '.$this->author.' OR author = '.$this->reciever 
					  .') AND (reciever = '.$this->reciever.' OR reciever = '.$this->author
					  .') AND chat = 5';
		
		return $this->db->query($sql);
	}
	
	public
	function fetchAllGroupMessages($author, $chat, $db) {
		$this->db = $db;
		$this->author = $author;
		$this->chat = $chat;
		
		$this->setAllMessagesToSeen(0, $this->reciever, $this->chat, $this->db);
		
		$sql = 'SELECT * FROM message
				WHERE chat = '.$this->chat.' GROUP BY author, date_time ORDER BY date_time ASC';

		return $this->db->query($sql);
	}
	
	public
	function countAllMessagesForUser($reciever, $db) {
		$this->db = $db;
		$this->reciever = $reciever;
		
		$sql = 'SELECT COUNT(*) FROM message WHERE seen = 0 AND reciever = '.$this->reciever;
		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		return $row['COUNT(*)'];
	}
	
	public
	function countAllMessagesForEachChat($reciever, $author, $chat, $db) {
		$this->db = $db;
		$this->reciever = $reciever;
		$this->author = $author;
		$this->chat = $chat;
		
		if ($this->author > 0) {
			$sql = 'SELECT COUNT(*) FROM message WHERE seen = 0 AND reciever = '.$this->reciever.' AND author = '.$this->author
			   .' AND chat = '.$this->chat;
		} else {
			$sql = 'SELECT COUNT(*) FROM message WHERE seen = 0 AND reciever = '.$this->reciever.' AND chat = '.$this->chat;
		}

		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		return $row['COUNT(*)'];
	}
}

?>