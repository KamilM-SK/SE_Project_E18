<?php
include_once( 'sessioncheck.php' );

if ( !isset( $_GET[ 'id' ] ) || $_GET[ 'id' ] == '' ) {
	header( 'location: ../../admin/designarticles.php?user=' . $_GET[ 'user' ] );
}

include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );


$article = new Article( $conn );
$articleExists = $article->checkIfArticleExists( $_GET[ 'id' ], $conn );


if ( $articleExists == 1 ) {
	$articleCancel = $article->approveCancellationOfArticle( $_GET[ 'user' ], $_GET[ 'id' ], 4, $conn );
} else {
	header( 'location: ../../admin/designarticles.php?user=' . $_GET[ 'user' ] );
}

?>