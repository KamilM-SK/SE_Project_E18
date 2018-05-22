<?php

class Meeting {

    private $tableName = "meeting";
    private $ID;
    private $description;
    private $deadline;
    private $db;

    function __construct($db) {
        $this->db = $db;
        if(isset($_POST['create_poll'])){
           $this->organizeMeeting($db);
        }
		
		$this->sendFinalNotification();
    }
	
	public function sendFinalNotification() {
		$lastMeeting = $this->getLastMeetingID($this->db);
		$sql = 'SELECT * FROM meeting_appointment WHERE meeting = '.$lastMeeting.' ORDER BY 5 DESC LIMIT 1';
		$meetingTime = $this->db->query($sql);
		$meetingTime = $meetingTime->fetch_assoc();
		
		$sql = 'SELECT notify FROM meeting WHERE ID = '.$lastMeeting;
		$not = $this->db->query($sql);
		$abc = $not->fetch_assoc();
		
		if ($abc['notify'] == 0) {
		
		$sql = 'SELECT deadline FROM meeting WHERE ID = '.$lastMeeting;
		$meetingDeadline = $this->db->query($sql);
		$meetingDeadline = $meetingDeadline->fetch_assoc();
		$currentTime = time();
		if ($meetingDeadline['deadline'] - $currentTime <= 0) {
			$sql = 'SELECT * FROM user WHERE user_type < 6';
			$res = $this->db->query($sql);
			while ($list = $res->fetch_assoc()) {
			
			$sql = 'INSERT INTO notification VALUES(
							DEFAULT, DEFAULT, 
							"Meeting deadline has ended! Next meeting starts on '.$meetingTime['date'].' at '.$meetingTime['time'].'",
							DEFAULT, 2, '.$list['ID'].', DEFAULT)';
					$this->db->query($sql);
			}
			$sql = 'UPDATE meeting SET notify = 1 WHERE ID ='.$lastMeeting;
			$this->db->query($sql);
		}
		}		
	}

	public function getLastMeeting($db) {
		$this->db = $db;
		$lastMeeting = $this->getLastMeetingID($this->db);
		$sql = 'SELECT * FROM meeting_appointment WHERE meeting = '.$lastMeeting.' ORDER BY 5 DESC LIMIT 1';
$meetingTime = $this->db->query($sql);
		
		if ($meetingTime->num_rows > 0) {
			$meetingTime = $meetingTime->fetch_assoc();
			echo date('d.m.y', strtotime($meetingTime['date'])).' at '.date('h:m', strtotime($meetingTime['time']));
		} else {
			echo 'No session';
		}
	}
	
    public function getLastMeetingID($db) {
        $this->db = $db;
        $sql = 'SELECT ID FROM meeting ORDER BY 1 DESC';
        $result = $this->db->query($sql);
        $result = $result->fetch_assoc(); 
        return $result['ID'];
    }
    
    public function getMeetingDeadline($db, $ID){
        $this->db = $db;
        $this->ID = $ID;
        $sql = 'SELECT deadline FROM meeting WHERE ID ='.$this->ID;
        $result = $this->db->query($sql);
        $result = $result->fetch_assoc(); 
        return $result['deadline'];
    }
    
    public function getMeetingDescription($db, $ID){
        $this->db = $db;
        $this->ID = $ID;
        $sql = 'SELECT description FROM meeting WHERE ID ='.$this->ID;
        $result = $this->db->query($sql);
        $result = $result->fetch_assoc(); 
        return $result['description'];
    }

    private function organizeMeeting($db) {
        $this->db = $db;
        $this->description = $_POST['meeting_description'];
        $date = $_POST['deadline_date'];
        $time = $_POST['deadline_time'];
        $this->deadline = date('Y-m-d H:i:s', strtotime("$date $time"));
        $stmt = $this->db->prepare('INSERT INTO meeting(deadline, description) VALUES(?, ?)');
        $stmt->bind_param("ss", $this->deadline, $this->description);
        $stmt->execute();
    }

    public function checkIfUserAlreadyVotedOnMeeting($db, $meetingID, $user) {
        $this->db = $db;
        $sql = 'SELECT * FROM meeting_attendes '
                . 'JOIN meeting_appointment ON meeting_attendes.meeting_appointment = meeting_appointment.ID '
                . 'JOIN meeting ON meeting_appointment.meeting = meeting.ID WHERE '
                . 'meeting_attendes.user = '.$user.' AND meeting.ID = '.$meetingID;
        $result = $this->db->query($sql);
        if ($result->num_rows) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>