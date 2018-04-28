<?php
session_start();
require( 'api/Database.php' );
require( 'classes/User.php' );

$user = new User( $conn );


?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Stak - Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="api/res/login.css">
</head>

 <body class="text-center">
	<div class="container">
	
	<form class="form-signin" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']); ?>">
		<div id="logo"></div>
		
			<?php 
	
		if (isset($_GET['status']) && $_GET['status'] == 0) {
				echo ('User does not exist.');
		}
		
		if (isset($_GET['status']) && $_GET['status'] == 1) {
				echo ('Incorrect password.');
		}
	?>
      <input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" required autofocus>
      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
      <div class="checkbox mb-3">
       
      <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Login</button>
    </form>
	
</body>


</html>