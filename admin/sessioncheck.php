<?php 

session_start();
if (!$_SESSION) {
	header('location: http://botticelliproject.com/stak');
}

?>