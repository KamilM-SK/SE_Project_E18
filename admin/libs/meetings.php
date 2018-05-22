<?php

session_start();

include_once( '../../api/Database.php' );
include_once( '../../classes/Meeting.php' );
include_once( '../../classes/Appointment.php' );

if (isset($_POST['create_poll'])) {
            $meeting = new Meeting($conn);
            $appointment = new Appointment($conn);
            $lastMeeting = $meeting->getLastMeetingID($conn);
            $test = $appointment->addAppointments($conn, $lastMeeting);
        }
        
                
if (isset($_POST['vote'])) {
            $appointment = new Appointment($conn);
            $test = $appointment->fillPoll($conn, $_GET['user']);
        }