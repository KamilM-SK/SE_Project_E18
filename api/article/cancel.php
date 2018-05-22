<?php
include_once( 'sessioncheck.php' );

if (!isset($_GET['id']) || $_GET['id'] == '') {
	header('location: ../../admin/myarticles.php?user='.$_GET['user']);
}

include_once('../Database.php');
include_once('../../classes/Article.php');


$article = new Article($conn);
$articleExists = $article->checkIfArticleExists($_GET['id'], $conn);

if ($articleExists == 1) {
	$articleCancel = $article->approveCancellationOfArticle($_GET['user'], $_GET['id'], 2, $conn);
}

else {
	header('location: ../../admin/myarticles.php?user='.$_GET['user']);
}

?>