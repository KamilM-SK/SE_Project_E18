<!doctype html>
<html>

<head>
	<base href="http://localhost/stak/"/>
	<meta charset="utf-8">
	<title>Stak</title>
	<title>Stak - Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="api/res/style.css">
	<script src="api/res/adminscript.js"></script>
</head>

<body>

	<header id="header__opacity">

		<div class="sitelink__icon"> <a href="<?php echo($_SERVER['PHP_SELF']); ?>#" onClick="runResponsiveNavigation()"> &#9776; </a> </div>


		<div class="logo"><img src="api/res/stakLOGODark.png">
		</div>

		<div id="account">
			<div id="account__username">
				<?php echo($_SESSION['first_name']." ".$_SESSION['last_name']) ?> &#9662; </div>


			<div class="account__dropdown">
				<div id="account_arrow"></div>
				<a href="users.php?id=<?php echo($_SESSION['user_id']) ?>"> My Account </a>
				<a href="logout.php?logout=true&location=2"> Logout </a>

			</div>
		</div>

	</header>


	<?php include('navigation.php'); ?>