<?php 

/*
* Filename: UserType.php
* Description: User Type Class
* Since: 25-04-2018
* Author: Petar Jadek
*/

class UserType {
	private $tableName = "user_type";
	private $ID;
	private $description;
	private $db;
	
	public function getAllRoles($db) {
		$this->db = $db;
		$sql = 'SELECT * FROM user_type WHERE ID > 1';
		return $this->db->query($sql);
	}
	
}

?>