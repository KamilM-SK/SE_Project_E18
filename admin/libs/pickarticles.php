<?php

session_start();

include_once( '../../api/Database.php' );
include_once( '../../classes/Article.php' );

if (isset($_POST['select_articles_for_writing'])) {
	$article = new Article($conn);
	$test = $article->updateArticleSetItForWriting($_GET['user'], $conn);
}

?>