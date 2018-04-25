<?php 

	session_start();
	
	require('api/database.php');
	require('classes/User.php');
	
	$user = new User($conn);

 ?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>
<body>

<?php

if (isset($_GET['status'])) {
	
	if ($_GET['status'] == 0) {
		echo('User does not exists.');
	}
	
	if ($_GET['status'] == 1) {
		echo('Incorrect password.');
	}

}

?>

<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']) ?>">
<input type="text" name="username" placeholder="Username" required >

<input type="password" name="password" placeholder="Password" required >

<button type="submit" name="login" >Login</button>

</form>


</body>
</html> 