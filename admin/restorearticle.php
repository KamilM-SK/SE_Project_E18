<?php

include_once( 'sessioncheck.php' );
include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );


$article = new Article( $conn );
$userByArticle = new UserByArticle( $conn );


if ( isset( $_GET[ 'articleid' ] ) ) {
	$articleList = $article->fetchArticleHistory( $_GET[ 'articleid' ], $conn );
	$currentArticle = $article->fetchArticleData( $_GET[ 'articleid' ], $conn );
}

if ( isset( $_GET[ 'article_historyid' ] ) ) {
	if ( $article->checkIfArticleExistsInHistory( $_GET[ 'article_historyid' ], $conn ) == 1 ) {
		$articleData = $article->getArticleFromArticleHistory( $_GET[ 'article_historyid' ], $conn );
	} else {
		echo "Article doesn't exist in history";
	}
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
		<h1 class="display-4">Article History : <?php  echo $currentArticle['title'] ?> </h1>



		<div class="columns">
			<div class="column-left">
				<?php 
				
				while ($row = $articleList->fetch_assoc()) {
					echo '<a  href="admin/restorearticle.php?articleid='.$_GET['articleid'].'&article_historyid='.$row['ID'].'"><div class="article_link">';
					echo $row['date_time'];
					echo '</div></a>';
				}
            
            
            	?>
			</div>
			<?php if (isset($_GET['article_historyid'])) {  
		if($article->checkIfArticleExistsInHistory($_GET[ 'article_historyid'], $conn) == 1){
		$articleData = $article->getArticleFromArticleHistory( $_GET[ 'article_historyid' ], $conn);
	
	?>
			<div class="column-right">

				<div class="row">
					<div class="col-sm-8">

						<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF'].'?articleid='.$_GET[ 'articleid' ]) ?>">

							<div class="form-group">
								<textarea class="form-control" style="width: 100%" rows="15" name="article_text" readonly >
									<?php echo($articleData['article_text']) ?>
								</textarea>
							</div>

							<button type="submit" class="btn btn-primary" name="restore_article">Restore</button>

						</form>

					</div>

				</div>

				<?php } else {
		echo "Article doesn't exist in history";
	} } ?>

			</div>


		</div>
	</div>

</main>