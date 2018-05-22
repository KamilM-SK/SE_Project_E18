<?php

include_once( 'sessioncheck.php' );
include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );
include_once( '../classes/Magazine.php' );


$article = new Article( $conn );
$magazine = new Magazine($conn);
$userByArticle = new UserByArticle($conn);

$currentMagazine = $magazine->getLastMagazineID( $conn );
$articleList = $article->fetchAllArticlesAvailableForReviewing( $conn );

if ( isset( $_GET[ 'article' ] ) ) {
	$articleData = $article->fetchArticleData( $_GET[ 'article' ], $conn );
}

include( 'header.php' );
?>


<main>
	
	<?php 
	
			if (isset($_GET['status'])) {
				
				if ($_GET['status'] == 1000) {
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

	<div class="container-fluid">
		<h1 class="display-4">Revise articles </h1>
		<p class="text-danger">Deadline:
			<?php echo($magazine->getDeadlineForReviewing($currentMagazine['ID'], $conn)) ?>
		</p>

		<div class="columns">
			<div class="column-left">
				<?php 
				
				while ($row = $articleList->fetch_assoc()) {
					echo '<a  href="admin/reviewarticles.php?&article='.$row['ID'].'"><div class="article_link">';
					echo $row['title'];
					echo '</div></a>';
				}
            
            
            	?>
			</div>
			<?php if (isset($_GET['article'])) { ?>
			<div class="column-right">
				<h3>
					<?php echo($articleData['title']) ?>
				</h3>

				<p>Author:
					<?php echo($userByArticle->getArticleAuthor($_GET['article'], $conn)) ?>
				</p>
				<p>Designer:
					<?php echo($userByArticle->getArticleDesigner($_GET['article'], $conn)) ?> </p>


				<div class="row">
					<div class="col-sm-8">

						<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF'].'?article='.$_GET[ 'article' ]) ?>">

							<div class="form-group">
								<textarea class="form-control" rows="15" name="article_text">
									<?php echo($articleData['article_text']) ?>
								</textarea>
							</div>

							<button type="submit" class="btn btn-primary" name="save_revision">Save</button>

						</form>

					</div>

				</div>

				<?php } ?>

			</div>


		</div>
	</div>

</main>