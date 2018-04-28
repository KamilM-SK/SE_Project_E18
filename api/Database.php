<?php

/*
* Filename: Database.php
* Description: Database connection file
* Since: 25-04-2018
* Author: Petar Jadek
*/

require('config.php');

$conn = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME);

?>