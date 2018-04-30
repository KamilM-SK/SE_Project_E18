<?php

include_once( 'sessioncheck.php' );

if ($_SESSION['user_type'] != 1) {
	header('location: admin/403.php');
}

include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/User.php' );
include_once( '../classes/Notification.php' );

$user = new User($conn);
$article = new Article( $conn );
$notification = new Notification( $conn );

$journalists = $user->fetchAllJournalists($conn);
$articles = $article->fetchAllArticlesAvailableForWriting($conn);

include( 'header.php' )

?>

<main>
	<div class="container-fluid">
		
		<?php 
	
			if (isset($_GET['status'])) {
				
				if ($_GET['status'] == 1000) {
					?>

		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success!</strong> You have assigned an article!
		</div>

		<?php
			}

		}

		?>


		<h1 class="display-4">Assign Article to Journalist</h1>
		
		<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']) ?>">
		
			<div class="form-group">
					<label for="sel1">Select Journalist:</label>
					<select required name="member" class="form-control" id="sel1">
						<?php 
								
								while ($row = $journalists->fetch_assoc()) {
									echo('<option value="'.$row['ID'].'">'.$row['username'].' - '.$row['first_name'].' '.$row['last_name'].'</option>');
								}

							?>
					</select>
				</div>
			
			<div class="form-group">
					<label for="sel1">Select Article:</label>
					<select required name="article" class="form-control" id="sel1">
						<?php 
								
								while ($row = $articles->fetch_assoc()) {
									echo('<option value="'.$row['ID'].'">'.$row['title'].' - '.$row['section'].' - <em>'.$row['page_number'].' pages</em>'.'</option>');
								}

							?>
					</select>
				</div>
			
			<button name="select_journalist" class="btn btn-primary" type="submit">Save</button>
		
		</form>
	
	</div>

</main>