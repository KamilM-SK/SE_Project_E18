<?php

include_once( '../classes/Notification.php' );
include_once( '../api/Database.php' );
$notification = new Notification( $conn )

?>
<!doctype html>
<html>

<head>
	<base href="http://botticelliproject.com/stak/"/>
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
		<div class="user__notification">

			<?php 
				
					$numberOfNotification = $notification->countAllUnseenNotificationsForUser($_SESSION['user_id'], $conn);
				
				?>

			<span class="badge badge-primary">
				<?php echo($numberOfNotification) ?>
			</span>

			<div class="user__dropdown">
				<div id="user_arrow"></div>
				Notifications
				<?php 
				
				if ($numberOfNotification > 0) {
					$result = $notification->fetchAllUnseenNotificationsForUser($_SESSION['user_id'], $conn);
					
					while ($row = $result->fetch_assoc()) {
						
						switch ($row['notification_type']) {
							case 1: {
								?>

				<div class="general_news">
					<div class="small">
						<?php echo $row['time'] ?>
					</div>
					<div class="delete"><a href="api/removenotification.php?id=<?php echo($row['ID']); ?>">×</a>
					</div>
					<?php echo $row['description'] ?>
				</div>
				<?php
							break;
							}
							case 2: {
								?>
				
					<div class="article_news">
					<div class="small">
						<?php echo $row['time'] ?>
					</div>
					<div class="delete"><a href="api/removenotification.php?id=<?php echo($row['ID']); ?>">×</a>
					</div>
					<?php echo $row['description'] ?>
				</div>
				<?php
							break;
							}
				}

				}
				} else {
					echo( 'There are no new notifications.' );
				}


				?>


			</div>

		</div>

	</header>


	<?php include('navigation.php'); ?>