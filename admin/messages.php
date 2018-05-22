<?php
include_once( 'sessioncheck.php' );
include_once( '../api/Database.php' );
include_once( '../classes/User.php' );
include_once( '../classes/Chat.php' );

$user = new User( $conn );
$chat = new Chat( $conn );


include( 'header.php' );

?>

<main>
	<div class="container-fluid">
		<h1 class="display-4">Messages</h1>
	</div>

	<div class="columns">
		<div class="column-left">
			<h5 align="center">Group Chats</h5>
			<?php 
				
				$groupChat = $chat->listAllGroupChats($conn);
				$userList = $user->fetchAllMembers($conn);
				
				while ($gc = $groupChat->fetch_assoc()) {
					$numberOfMessagesForUser = $message->countAllMessagesForEachChat($_SESSION['user_id'], 0, $gc['ID'], $conn);
					
					if (($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 2) && $gc['description'] == 'Designers') {
						echo '<a  href="admin/messages.php?group=2&chat='.$gc['ID'].'#end"><div class="article_link';
						if (isset($_GET['chat']) && $_GET['chat'] == 1) echo ' activen';
						echo '">';
						echo('<i class="fa fa-magic"></i> '.$gc['description']);
						if ($numberOfMessagesForUser > 0) echo(' <span class="badge badge-pill badge-warning">'.$numberOfMessagesForUser.'</span>');
						echo '</div></a>';
					}
					
					if (($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 3) && $gc['description'] == 'Journalists') {
						echo '<a  href="admin/messages.php?group=3&chat='.$gc['ID'].'#end"><div class="article_link';
						if (isset($_GET['chat']) && $_GET['chat'] == 2) echo ' activen';
						echo '">';
						echo('<i class="fa fa-edit"></i> '.$gc['description']);
						if ($numberOfMessagesForUser > 0) echo(' <span class="badge badge-pill badge-warning">'.$numberOfMessagesForUser.'</span>');
						echo '</div></a>';
					}
					
					if (($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 4) && $gc['description'] == 'Revisors') {
						echo '<a  href="admin/messages.php?group=4&chat='.$gc['ID'].'#end"><div class="article_link';
						if (isset($_GET['chat']) && $_GET['chat'] == 3) echo ' activen';
						echo '">';
						echo('<i class="fa fa-address-book-o"></i> '.$gc['description']);
						if ($numberOfMessagesForUser > 0) echo(' <span class="badge badge-pill badge-warning">'.$numberOfMessagesForUser.'</span>');
						echo '</div></a>';
					}
					
					if (($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 5) && $gc['description'] == 'Photographers') {
						echo '<a  href="admin/messages.php?group=5&chat='.$gc['ID'].'#end"><div class="article_link';
						if (isset($_GET['chat']) && $_GET['chat'] == 4) echo ' activen"';
						echo '">';
						echo('<i class="fa fa-camera"></i> '.$gc['description']);
						if ($numberOfMessagesForUser > 0) echo(' <span class="badge badge-pill badge-warning">'.$numberOfMessagesForUser.'</span>');
						echo '</div></a>';
					}
					
				}
            
            
            	?>

			<h5 align="center">Personal Chats</h5>

			<?php 
				
					while  ($ul = $userList->fetch_assoc()) {
						if ($ul['ID'] != $_SESSION['user_id']) {
							$numberOfMessagesForUser = $message->countAllMessagesForEachChat($_SESSION['user_id'], $ul['ID'], 5, $conn);
							
							echo '<a  href="admin/messages.php?reciever='.$ul['ID'].'#end"><div class="article_link';
							
							if (isset($_GET['reciever']) && $_GET['reciever'] == $ul['ID']) echo ' activen';
							
							echo'"> <img class="profile_photo_circle-sm"';
							 if ($ul['avatar']) echo(' src="admin/members/'.$ul['avatar'].'">');
									 else echo('src="admin/members/default.jpg">');
							echo '   '.$ul['first_name'].' '.$ul['last_name'];
							if ($numberOfMessagesForUser > 0) echo(' <span class="badge badge-pill badge-warning">'.$numberOfMessagesForUser.'</span>');
							echo '</div></a>';
						}
					}
				
				?>
		</div>
		<?php if (isset($_GET['chat']) || isset($_GET['reciever'])) { ?>
		<div class="column-right" >

			<?php 
				
					if (isset($_GET['reciever'])) {
						$userName = $user->fetchAccountData($_GET['reciever'], $conn);
						
						echo('<h3 align="center">'.$userName['first_name'].' '.$userName['last_name'].'</h3>');
						
						$messageListBetweenUsers = $message->fetchAllMessagesBetweenTwoUsers($_SESSION['user_id'], $_GET['reciever'], $conn);
						
						if ($messageListBetweenUsers->num_rows > 0) {
							
							while ($messagesBetweenUser = $messageListBetweenUsers->fetch_assoc()) {
								echo('<div class="message-area">');
								if ($_SESSION['user_id'] == $messagesBetweenUser['author']) {
									echo('<div class="message_author">');
									echo($messagesBetweenUser['message_text']);
									echo('</div><div style="clear:both"></div>');
								}
								
								else {
									echo('<div class="message_reciever bg-warning">');
									echo($messagesBetweenUser['message_text']);
									echo('</div><div style="clear:both"></div>');
								}
								echo('</div>');
								
							}
							
						} else {
							echo('<h5 align="center">No messages in this chat.</h5>');
						}
						
						?>

			<form method="post" action="" enctype="multipart/form-data">
					<textarea name="message_text" required placeholder="Write your message here..." rows="5" id="comment"></textarea>
					<button name="send_message" type="submit" class="bottom btn btn-block btn-outline-primary">Send Message</button>
				<div id="end"></div>
			</form>
			<?php
			}
	
				if (isset($_GET['chat'])) {
					
					$groupMessageList = $message->fetchAllGroupMessages($_SESSION['user_id'], $_GET['chat'], $conn);
					
					if ($groupMessageList->num_rows > 0) {
						
						while ($groupMessages = $groupMessageList->fetch_assoc()) {
							$userName = $user->fetchAccountData($groupMessages['author'], $conn);
							
							echo('<div class="message-area">');
								if ($_SESSION['user_id'] == $groupMessages['author']) {
									echo('<div class="message_author">');
									echo($groupMessages['message_text']);
									echo('</div><div style="clear:both"></div>');
								}
								
								else {
									echo('<small>'.$userName['first_name'].' '.$userName['last_name'].'</small><br>');
									echo('<div class="message_reciever bg-warning">');
									echo($groupMessages['message_text']);
									echo('</div><div style="clear:both"></div>');
								}
								echo('</div>');
						}
						
					} else {
							echo('<h5 align="center">No messages in this chat.</h5>');
						}
					
				?>
				
				<form method="post" action="" enctype="multipart/form-data">
					<textarea name="message_text" required placeholder="Write your message here..." rows="5" id="comment"></textarea>
					<button name="send_message_group" type="submit" class="bottom btn btn-block btn-outline-primary">Send Message</button>
					<div id="end"></div>
			</form>
				
				<?php
				}


			?>

		</div>

		<?php } ?>

	</div>

</main>