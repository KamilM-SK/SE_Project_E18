<?php

// check if the user is an actual journalist to that article


// update function 

include_once( 'sessioncheck.php' );

if ( !isset( $_GET[ 'userid' ] ) || $_GET[ 'userid' ] == '' ) {
	header( 'location: myarticles.php?user=' . $_SESSION[ 'user_id' ] );
}

if ( $_GET[ 'userid' ] != $_SESSION[ 'user_id' ] ) {
	header( 'location: myarticles.php?user=' . $_SESSION[ 'user_id' ] );
}

if ( !isset( $_GET[ 'articleid' ] ) || $_GET[ 'articleid' ] == '' ) {
	#header( 'location: myarticles.php?user=' . $_SESSION[ 'user_id' ] . '&status=noarticle1' );
}

include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/Magazine.php' );
include_once( '../classes/UserByArticle.php' );

$article = new Article( $conn );
$userByArticle = new UserByArticle( $conn );
$magazine = new Magazine( $conn );

$articleExists = $article->checkIfArticleExists( $_GET[ 'articleid' ], $conn );

if ( $articleExists == 1 ) {
	$checkIfUserIsGivenThatArticle = $userByArticle->checkIfUserIsSpecificArticleJournalist( $_GET[ 'userid' ], $_GET[ 'articleid' ], $conn );
	if ( $checkIfUserIsGivenThatArticle == 1 ) {
		$articleData = $article->fetchArticleData( $_GET[ 'articleid' ], $conn );
	} else {
		header( 'location: myarticles.php?user=' . $_SESSION[ 'user_id' ] . '&status=invalid' );
	}
} else {
	header( 'location: myarticles.php?user=' . $_SESSION[ 'user_id' ] . '&status=noarticle' );
}

include( 'header.php' );

?>

<main>
	<div class="container-fluid">

		<?php 
	
			if (isset($_GET['status'])) {
				
				if ($_GET['status'] == 'success') {
					?>

		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success!</strong> You've made changes to the article.
		</div>

		<?php
		}

		if ( $_GET[ 'status' ] == 'fail' ) {

			?>

		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Fail!</strong> Article changes hadn't been made.
		</div>

		<?php
		}

		}

		?>

		<div class="row">
			<div class="col-sm-8">
				<h1 class="display-4">Write Article <a class="btn btn-danger" role="button" href="#">Send For Revision</a>  </h1>
				<br><br>

				<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF'].'?userid='.$_GET[ 'userid' ].'&articleid='.$_GET[ 'articleid' ]) ?>">

					<div class="form-group">
						<label>Title:</label>
						<input class="form-control" type="text" name="title" readonly value="<?php echo($articleData['title']) ?>">
					</div>

					<p>Section:
						<?php echo($articleData['section']) ?>
					</p>
					<p>Designer: </p>
					<p class="text-danger">Deadline:
						<?php echo($magazine->getDeadlineForWriting($articleData['magazine'], $conn)) ?>
					</p>

					<div class="form-group">
						<label for="comment">Enter Article Text:</label>
						<textarea class="form-control" rows="15" name="article_text">
							<?php echo($articleData['article_text']) ?>
						</textarea>
					</div>

					<button type="submit" class="btn btn-primary" name="save_progress">Save Progress</button>

				</form>

			</div>
			<div class="col-sm-4">
				<h4>Photos <a class="btn btn-primary" role="button" href="#">Upload Photos</a></h4>
				<p>To be finished</p>
			</div>
		</div>
	</div>
</main>