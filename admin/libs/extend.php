<?php

session_start();

include_once( '../../api/Database.php' );
include_once( '../../classes/Article.php' );

if (isset($_GET['id'])) {
	$article = new Article($conn);
	$test = $article->extendArticle($_GET['id'], $conn);
}
