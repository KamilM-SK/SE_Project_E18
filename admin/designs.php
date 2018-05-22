<?php

include_once( 'sessioncheck.php' );
include_once( '../api/Database.php' );
include_once( '../classes/Magazine.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );
include_once( '../classes/Design.php' );

$magazine = new Magazine( $conn );
$article = new Article( $conn );
$userByArticle = new UserByArticle( $conn );
$design = new Design( $conn );

if ( !isset( $_GET[ 'magazine' ] ) || $_GET[ 'magazine' ] == '' ) {
	header( 'location: designs.php?magazine=' . $magazineID[ 'ID' ] );
}

$articleList = $article->fetchAllArticlesForSelectedMagazine( $_GET[ 'magazine' ], $conn );

if ( isset( $_GET[ 'article' ] ) ) {
	$articleData = $article->fetchArticleData( $_GET[ 'article' ], $conn );
}

include( 'header.php' );
?>


<main>

	<div class="container-fluid">
		<h1 class="display-4">Designs for Number <?php echo($_GET['magazine']) ?></h1>

		<br>

		<?php

		$designList = $design->fetchAllDesignsForMagazine( $_GET[ 'magazine' ], $conn );
		$numberOfPages = 0;
			
		if ( $designList->num_rows > 0 ) {
			?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Article Title</th>
					<th>Number of Pages</th>
					<th>Design</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				
						while ($list = $designList->fetch_assoc()) {
							echo('<tr><td>'.$list['title'].'</td>');
							echo('<td>'.$list['page_number'].'</td>');
							echo('<td><a href="articledesign/'.$list['location'].'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.$list['location'].'</a></td>');
							echo('<td>');
							echo('<a href="admin/designs.php?magazine='.$_GET['magazine'].'&article='.$list['ID'].'" 
								role="button" class="btn btn-danger btn-sm">Reject</a> ');
							echo('<a href="admin/designs.php?magazine='.$list['ID'].'" role="button" class="btn btn-primary btn-sm">View Designs</a> ');

							echo('</td></tr>');
							
							$numberOfPages += $list['page_number'];
						}
						
						?>
			</tbody>
		</table>
		<h5>Total Number of Finished Pages: <?php echo($numberOfPages) ?></h5>
		<?php
		} else {
			echo( '<h3>No designs were submitted for this number. :(</h3>' );
		}

		?>



	</div>

</main>