<?php

class User {
	private $tableName = "user";
	private $ID;
	private $username;
	private $password;
	private $salt;
	private $email;
	private $firstName;
	private $lastName;
	private $userType;
	private $db;
	
	function __construct($db) {
		$this->db = $db;
		
		if (isset($_POST['login'])) {
			$this->login();
		}
		
		if (isset($_POST['register'])) {
			$this->register();
		}
	}
	
	private function login() {
		$this->username = $this->db->real_escape_string($_POST['username']);
		$this->password = $this->db->real_escape_string($_POST['password']);
		
		$sql = 'SELECT * FROM user WHERE username = "'.$this->username.'"';
		$result = $this->db->query($sql);
		
		if ($result->num_rows == 1) {
			$result = $result->fetch_assoc();
			$this->password = sha1($this->password.$result['salt']);
			
			if ($this->password == $result['password']) {
				$_SESSION['user_id'] = $result['ID'];
				$_SESSION['username'] = $result['username'];
				$_SESSION['user_type'] = $result['user_type'];
				$_SESSION['first_name'] = $result['first_name'];
				$_SESSION['last_name'] = $result['last_name'];
				header('location: admin/index.php');
			}
			
			else {
				header('location: login.php?status=1');
			}
		}
		else {
			header('location: login.php?status=0');
		}
	}
	
	private function register() {
		$this->username = $this->db->real_escape_string($_POST['username']);
		$this->firstName = $this->db->real_escape_string($_POST['first_name']);
		$this->lastName = $this->db->real_escape_string($_POST['last_name']);
		$this->email = $this->db->real_escape_string($_POST['email']);
		$this->userType = $_POST['user_type'];
		
		$this->salt = rand(1000000000, 9999999999);
		$this->password = rand(1000000000, 9999999999);
		$emailPass = $this->password;
		$this->password = sha1($this->password.$this->salt);
		
		$stmt = $this->db->prepare('INSERT INTO user(username, first_name, last_name, email, salt, password, user_type) VALUES(?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param("ssssisi", $this->username, $this->firstName, $this->lastName, $this->email, $this->salt, $this->password, $this->userType);
		$stmt->execute();
		
		$this->sendVerificationMailToANewMember($this->email, $this->username, $emailPass);
	}
	
	private function sendVerificationMailToANewMember($email, $username, $password) {
		$this->email = $email;
		$this->username = $username;
		$this->password = $password;
		$email = 'USERNAME = '.$this->username.' /////// PASSWORD = '.$this->password;
		mail($this->email, 'ACCESS DATA', $email);
	}
	
}

?>