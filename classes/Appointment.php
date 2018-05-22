<?php

class Appointment {

    private $tableName = "meeting_appointment";
    private $ID;
    private $meeting;
    private $date;
    private $time;
    private $db;

    function __construct($db) {
        $this->db = $db;       
    }

    public function fetchAllAppointmentsForMeeting($db, $meeting) {
        $this->db = $db;
        $sql = 'SELECT * FROM meeting_appointment WHERE meeting = ' . $meeting;
        return $this->db->query($sql);
    }

    private function incrementAppointmentCount($db, $ID) {
        $this->db = $db;
        $this->ID = $ID;
        $sql = 'UPDATE meeting_appointment SET count = count + 1 WHERE ID = ' . $this->ID;
        $this->db->query($sql);
    }

    public function fillPoll($db, $user) {
        $this->db = $db;

        if (!empty($_POST['appointment_id'])) {
            foreach ($_POST['appointment_id'] as $ID) {
                $this->ID = $ID;
                $this->incrementAppointmentCount($db, $ID);

                $stmt = $this->db->prepare('INSERT INTO meeting_attendes(meeting_appointment, user) VALUES(?, ?)');
                $stmt->bind_param("ii", $this->ID, $user);
                $stmt->execute();
            }
        }

        header('location: ../vote.php?status=1000');
    }

    public function addAppointments($db, $meeting) {
        $this->db = $db;
        $num = count($_POST['date_appointment']);
        for ($i = 0; $i < $num; $i++) {
            $d = $_POST['date_appointment'][$i];
            $t = $_POST['time_appointment'][$i];

            $stmt = $this->db->prepare('INSERT INTO meeting_appointment(meeting, date, time,  count) VALUES(?, ?, ?, 0)');
            $stmt->bind_param("iss", $meeting, $d, $t);
            $stmt->execute();
        }

        header('location: ../organizemeeting.php?status=1000');
    }

}

?>