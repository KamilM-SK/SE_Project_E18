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
	private $dateOfBirth;
	private $telephoneNumber;
	private $emailPrivate;
	private $studyArea;
	private $avatar;
	private $status;
	private $db;

	function __construct( $db ) {
		$this->db = $db;

		if ( isset( $_POST[ 'login' ] ) ) {
			$this->login();
		}

		if ( isset( $_POST[ 'register' ] ) ) {
			$this->register();
		}

		if ( isset( $_POST[ 'update' ] ) ) {
			$this->update( $_GET[ 'id' ], $_GET[ 'type' ], $_SESSION[ 'user_type' ] );
		}

		if ( isset( $_POST[ 'update_password' ] ) ) {
			$this->changePassword( $_GET[ 'id' ], $_POST[ 'password' ] );
		}

		if ( isset( $_GET[ 'remove' ] ) && $_GET[ 'remove' ] == true && $_SESSION[ 'user_type' ] == 1 ) {
			$this->removeAccount( $_GET[ 'id' ] );
		}

		if ( isset( $_GET[ 'editor' ] ) && $_GET[ 'editor' ] == true && $_SESSION[ 'user_type' ] == 1 ) {
			$this->setNewEditor( $_SESSION[ 'user_id' ], $_GET[ 'id' ] );
		}

		if ( isset( $_POST[ 'update_role' ] ) ) {
			$this->updateRole( $_GET['id'] );
		}
	}

	private

	function login() {
		$this->username = $this->db->real_escape_string( $_POST[ 'username' ] );
		$this->password = $this->db->real_escape_string( $_POST[ 'password' ] );

		$sql = 'SELECT * FROM user WHERE username = "' . $this->username . '"';
		$result = $this->db->query( $sql );

		if ( $result->num_rows == 1 ) {
			$result = $result->fetch_assoc();
			$this->password = sha1( $this->password . $result[ 'salt' ] );

			if ( $this->password == $result[ 'password' ] && $result[ 'user_type' ] < 6 ) {
				$_SESSION[ 'user_id' ] = $result[ 'ID' ];
				$_SESSION[ 'username' ] = $result[ 'username' ];
				$_SESSION[ 'user_type' ] = $result[ 'user_type' ];
				$_SESSION[ 'first_name' ] = $result[ 'first_name' ];
				$_SESSION[ 'last_name' ] = $result[ 'last_name' ];
				$_SESSION[ 'avatar' ] = $result[ 'avatar' ];
				header( 'location: admin/index.php' );
			} else {
				header( 'location: login.php?status=1' );
			}
		} else {
			header( 'location: login.php?status=0' );
		}
	}

	private

	function register() {
		$this->username = $this->db->real_escape_string( $_POST[ 'username' ] );
		$this->firstName = $this->db->real_escape_string( $_POST[ 'first_name' ] );
		$this->lastName = $this->db->real_escape_string( $_POST[ 'last_name' ] );
		$this->email = $this->db->real_escape_string( $_POST[ 'email' ] );
		$this->dateOfBirth = $this->db->real_escape_string( $_POST[ 'date_of_birth' ] );
		$this->studyArea = $this->db->real_escape_string( $_POST[ 'study_area' ] );
		$this->userType = $_POST[ 'user_type' ];
		$this->status = 1;

		$this->salt = rand( 1000000000, 2147483647 );
		$this->password = rand( 1000000000, 2147483647 );
		$emailPass = $this->password;
		$this->password = sha1( $this->password . $this->salt );

		$stmt = $this->db->prepare( 'INSERT INTO user(username, first_name, last_name, email, salt, password, date_of_birth, study_area, user_type, status) 
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
		$stmt->bind_param( "ssssisssii", $this->username, $this->firstName, $this->lastName, $this->email,
			$this->salt, $this->password, $this->dateOfBirth, $this->studyArea, $this->userType, $this->status );

		if ( $stmt->execute() ) {
			$this->sendVerificationMailToANewMember( $this->email, $this->username, $emailPass );
			header( 'location: register.php?register_status=1000&username=' . $this->username );
		}
		else {
			$stmt = $this->db->prepare( 'INSERT INTO temp_user(first_name, last_name, date, study, role) 
			VALUES(?, ?, ?, ?, ?)' );
			$stmt->bind_param( "ssssi", $this->firstName, $this->lastName, 
				$this->dateOfBirth, $this->studyArea, $this->userType );
			$stmt->execute();
			
			$sql = 'SELECT ID FROM temp_user ORDER BY 1 DESC LIMIT 1';
			$result = $this->db->query($sql);
			$result = $result->fetch_assoc();
			header('location: register.php?status=fail&id='.$result['ID']);
		}

	}

	private

	function sendVerificationMailToANewMember( $email, $username, $password ) {
		$this->email = $email;
		$this->username = $username;
		$this->password = $password;
		$email = 'USERNAME = ' . $this->username . ' /////// PASSWORD = ' . $this->password;
		mail( $this->email, 'ACCESS DATA', $email );
	}

	private

	function update( $ID, $user, $userType ) {
		$this->ID = $ID;
		$this->userType = $userType;

		$this->telephoneNumber = $this->db->real_escape_string( $_POST[ 'telephone_number' ] );

		$this->emailPrivate = $this->db->real_escape_string( $_POST[ 'email_private' ] );
		//Editor mode

		if ( $this->userType == 1 ) {
			$this->username = $this->db->real_escape_string( $_POST[ 'username' ] );
			$this->email = $this->db->real_escape_string( $_POST[ 'email' ] );
			$this->dateOfBirth = $this->db->real_escape_string( $_POST[ 'date_of_birth' ] );
			$this->firstName = $this->db->real_escape_string( $_POST[ 'first_name' ] );
			$this->lastName = $this->db->real_escape_string( $_POST[ 'last_name' ] );
			$this->studyArea = $this->db->real_escape_string( $_POST[ 'study_area' ] );

			$sql = 'UPDATE user SET username = "' . $this->username . '", 
			email = "' . $this->email . '", 
			date_of_birth = "' . $this->dateOfBirth . '", 
			first_name = "' . $this->firstName . '", 
			last_name = "' . $this->lastName . '", 
			study_area = "' . $this->studyArea . '", 
			email_private = "' . $this->emailPrivate . '",
			telephone_number = "' . $this->telephoneNumber . '"
			WHERE ID = ' . $this->ID;

			$this->db->query( $sql );
		} else if ( $this->ID == $user ) {
			$sql = 'UPDATE user SET email_private = "' . $this->emailPrivate . '",
			telephone_number = "' . $this->telephoneNumber . '"
			WHERE ID = ' . $this->ID;

			$this->db->query( $sql );
		}
	}

	private

	function changePassword( $ID, $password ) {
		$this->ID = $ID;
		$this->salt = rand( 1000000000, 2147483647 );
		$this->password = $password;
		$this->password = sha1( $this->password . $this->salt );

		$sql = 'UPDATE user SET password = "' . $this->password . '", salt = "' . $this->salt . '" WHERE ID = ' . $this->ID;

		$this->db->query( $sql );

	}

	private

	function removeAccount( $ID ) {
		$this->ID = $ID;
		$sql = 'UPDATE user SET user_type = 6 WHERE ID = ' . $this->ID;
		$this->db->query( $sql );
	}

	private

	function setNewEditor( $ID, $newEditorID ) {
		$this->ID = $ID;

		$sql = 'UPDATE user SET user_type = 1 WHERE ID = ' . $newEditorID;
		$this->db->query( $sql );

		$sql = 'UPDATE user SET user_type = 3 WHERE ID = ' . $this->ID;
		$this->db->query( $sql );
		$_SESSION[ 'user_type' ] = 3;
	}
	
	function updateRole( $ID ) {
		$this->ID = $ID;

		$sql = 'UPDATE user SET user_type = '.$_POST['user_type'].' WHERE ID = ' . $ID;
		$this->db->query( $sql );
	}


	public
	function fetchAllJournalists( $db ) {
		$this->db = $db;
		$sql = 'SELECT ID, username, first_name, last_name FROM user WHERE user_type = 3';
		return $this->db->query( $sql );
	}

	public
	function fetchAllMembers( $db ) {
		$this->db = $db;
		$sql = 'SELECT * FROM user WHERE user_type < 6';
		return $this->db->query( $sql );
	}

	public
	function fetchAccountData( $ID, $db ) {
		$this->ID = $ID;
		$this->db = $db;

		$sql = 'SELECT * FROM user WHERE ID = ' . $this->ID;
		$result = $this->db->query( $sql );
		return $result->fetch_assoc();

	}

	public
	function fetchUserType( $ID, $db ) {
		$this->ID = $ID;
		$this->db = $db;

		$sql = 'SELECT description 
				FROM user_type 
				LEFT JOIN user ON user_type.ID=user.user_type WHERE user.ID = ' . $this->ID;
		$result = $this->db->query( $sql );
		return $result->fetch_assoc();
	}

	public
	function fetchRegistrationDate( $ID, $db ) {
		$this->ID = $ID;
		$this->db = $db;

		$sql = 'SELECT registration_date
				FROM user 
				WHERE ID = ' . $this->ID;
		$result = $this->db->query( $sql );
		return $result->fetch_assoc();
	}

	public
	function setNewUserAvatar( $ID, $avatar, $db ) {
		$this->db = $db;
		$this->ID = $ID;
		$this->avatar = $avatar;

		$sql = 'UPDATE user SET avatar = "' . $this->avatar . '" WHERE ID = ' . $this->ID;
		$this->db->query( $sql );
	}

	public
	function getFirstLastNameAvatar( $ID, $db ) {
		$this->db = $db;
		$this->ID = $ID;

		$sql = 'SELECT first_name, last_name, avatar FROM user WHERE ID = ' . $this->ID;
		$result = $this->db->query( $sql );
		return $result->fetch_assoc();
	}
	
	public function fetchTempData($ID, $db) {
		$this->db = $db;
		$this->ID = $ID;
		$sql = 'SELECT * FROM temp_user WHERE ID = '.$ID;
		$result = $this->db->query($sql);
		return $result->fetch_assoc();
	}

}

?>