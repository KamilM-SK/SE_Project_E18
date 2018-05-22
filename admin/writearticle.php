<?php


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
include_once( '../classes/Photo.php' );


$article = new Article( $conn );
$userByArticle = new UserByArticle( $conn );
$magazine = new Magazine( $conn );
$photo = new Photo($conn);

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

include('uploadphototoarticle.php'); 

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
				<h1 class="display-4">Write Article 
					<a class="btn btn-danger" role="button" href="admin/libs/revision.php?article_id=<?php echo($_GET['articleid']) ?>">Send For Revision</a> 
					<a class="btn btn-primary" role="button" href="admin/restorearticle.php?articleid=<?php echo($_GET['articleid']) ?>&userid=<?php echo($_GET['userid']) ?>">Restore Previous Version</a> </h1>
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
				<h4>Photos   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Upload
					</button></h4>

				<?php 
				
				$photoList = $photo->listAllPhotos($_GET['articleid'], $conn);
					  
					  if ($photoList->num_rows == 0) {
						  echo 'No photos are included for this article. ';
					  } else {
						  while ($photograph = $photoList->fetch_assoc()) {
							  echo('<div class="img-article"><a href="uploads/'.$photograph['name'].'" target="_blank"><img class="img-thumbnail img-article" src="uploads/'.$photograph['name'].'" /></a>  ');
							  echo('</div>');
						  }
					  }
				
				?>
<div style="clear:both"></div>
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <div class="modal-header">
          <h4 class="modal-title">Upload Photos to an Article</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        

        <div class="modal-body">
          <form enctype="multipart/form-data" action="" method="post">
			Only JPEG, PNG, JPG Type Image Uploaded.
			<input type="file" name="file[]" id="file" class="selectphoto" multiple="multiple" /><br>
			
			<input type="submit" value="Upload File" name="submit" id="upload" class="upload  btn btn-primary"/>
			</form>
			
			
        </div>

        
      </div>
    </div>
  </div><?php ?>
			</div>
		</div>
	</div>
	
</main>