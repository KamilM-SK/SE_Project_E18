<?php

if (!isset($_GET['id']) || $_GET['id'] == '') {
	header('location: ../../admin/suggestarticles.php');
}

include_once('../Database.php');
include_once('../../classes/Article.php');

$article = new Article($conn);
$articleExists = $article->checkIfArticleExists($_GET['id'], $conn);

if ($articleExists == 1) {
	$articleApprove = $article->approveSuggestedArticle($_GET['id'], $conn);
}

else {
	header('location: ../../admin/suggestarticles.php?article=404');
}

?>