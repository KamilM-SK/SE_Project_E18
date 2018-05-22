<?php

include_once( '../Database.php' );
include_once( '../../classes/Notification.php' );

$notification = new Notification($conn);
$notification->deleteNotification($_POST["id"], $conn);

header('location: http://google.com'):

?>