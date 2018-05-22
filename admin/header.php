<?php

include_once( '../api/Database.php' );
include_once( '../classes/Notification.php' );
include_once( '../classes/Magazine.php' );
include_once( '../classes/Message.php' );
include_once( '../classes/System.php' );
include_once( '../classes/Meeting.php' );

$meeting = new Meeting($conn);

$system = new System($conn);
$system->submitAllArticlesAfterDeadlineHasPassed($conn);

$notification = new Notification( $conn );
$magazine = new Magazine( $conn );
$message = new Message( $conn );

$lastMagazineID = $magazine->getLastMagazineID($conn);
$notifyKey = $magazine->issueNotifyKey($lastMagazineID['ID'], $conn);

$system->checkIfNotificationHasToBeIssued($notifyKey, $lastMagazineID['ID'], $conn);

$numberOfNotification = $notification->countAllUnseenNotificationsForUser($_SESSION['user_id'], $conn);
$numberOfUnseenMessages = $message->countAllMessagesForUser($_SESSION['user_id'], $conn);

if ( isset($_GET["delnot"])) {
	$notification->deleteNotification($_GET["notification"], $conn);
	$numberOfNotification = $notification->countAllUnseenNotificationsForUser($_SESSION['user_id'], $conn);
}

?>
<!doctype html>
<html>

<head>
	<base href="http://botticelliproject.com/stak/"/>
	<meta charset="utf-8">
	<title>Stak</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="api/res/style.css">
	<script src="api/res/adminscript.js"></script>
	
	<script>
	function deleteNotification(id) {
	$.ajax({
		//url: "header.php",
		type: "GET",
		data: {"notification":id, "delnot":true},
		success: function() {
			var notificationType = 'notification'+id;
			$('#notification'+id).fadeOut(200);
		}
	});
}
	</script>
</head>

<body>
	
	<?php if(isset($_POST['id'])) echo 'da'; ?>

	<header id="header__opacity">

		<div class="sitelink__icon"> <a href="<?php echo($_SERVER['PHP_SELF']); ?>#" onClick="runResponsiveNavigation()"> &#9776; </a> </div>


		<div class="logo"><a href="admin"><img src="api/res/stakLOGODark.png"></a>
		</div>

		<div id="account">
			<div id="account__username">
				<?php echo($_SESSION['first_name']." ".$_SESSION['last_name']) ?> &#9662; </div>


			<div class="account__dropdown">
				<div id="account_arrow"></div>
				<a href="admin/myaccount.php?id=<?php echo($_SESSION['user_id']) ?>"> My Account </a>
				<a href="logout.php"> Logout </a>

			</div>

		</div>
		<div class="user__notification">

			<span class="badge badge-primary">
				<?php echo($numberOfNotification) ?>
			</span>

			<div class="user__dropdown">
				<div id="user_arrow"></div>
				Notifications
				<?php 
				
				if ($numberOfNotification > 0) {
					$notificationResult = $notification->fetchAllUnseenNotificationsForUser($_SESSION['user_id'], $conn);
					
					while ($row = $notificationResult->fetch_assoc()) {
						
						switch ($row['notification_type']) {
							case 1: {
								?>

				<div id="<?php echo('notification'.$row['ID']) ?>" class="general_news">
					<div class="small">
						<?php echo $row['time'] ?>
					</div>
					<div class="delete"><button onClick="deleteNotification(<?php echo($row['ID']) ?>)" class="btn btn-outline-light text-light noti" >&times;</button>
					</div>
					<?php echo $row['description'] ?>
				</div>
				<?php
							break;
							}
							case 2: {
								?>
				
					<div id="<?php echo('notification'.$row['ID']) ?>" class="article_news">
					<div class="small">
						<?php echo $row['time'] ?>
					</div>
					<div class="delete"><button onClick="deleteNotification(<?php echo($row['ID']) ?>)"  class="btn btn-outline-light text-light noti">&times;</button>
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