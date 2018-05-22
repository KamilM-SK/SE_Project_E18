<?php

if (isset($_GET['id'])) {
	include_once( '../api/Database.php' );
	include_once( '../classes/Article.php' );
	
	$article = new Article($conn);
	$articleData = $article->fetchArticleData($_GET['id'], $conn);
	
	echo('<h1>'.$articleData['title'].'</h1>');
	echo('<p>'.$articleData['article_text'].'</p>');
}

?>