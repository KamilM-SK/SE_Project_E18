<?php

include('../sessioncheck.php');

include_once( '../../api/Database.php' );
include_once( '../../classes/Article.php' );

if (!isset($_GET['article_id']) || $_GET['article_id'] == '') {
	header('location: ../index.php');
}

else {
	$article = new Article($conn);
	$articleExists = $article->checkIfArticleExists($_GET['article_id'], $conn);
	
	if ($articleExists == 1) {
		$article->sendForRevision($_GET['article_id'], $conn);
	}
	
	header('location: ../myarticles.php');
}

?>