<?php 

class Chat {
	private $tableName = "chat";
	private $ID;
	private $description;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
	}
	
	public
	function listAllGroupChats($db) {
		$this->db = $db;
		$sql = 'SELECT * FROM chat WHERE ID < 5';
		return $this->db->query($sql);
	}
}

?>