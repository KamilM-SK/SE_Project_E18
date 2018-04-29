<?php
session_start();


include_once( '../api/Database.php' );
include_once( '../classes/Magazine.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );

$magazine = new Magazine($conn);
$article = new Article($conn);
$userByArticle = new UserByArticle($conn);

if (!isset($_GET['magazine']) || $_GET['magazine'] == '') {
	$magazineID = $magazine->getLastMagazineID($conn);
	header('location: allarticles.php?magazine='.$magazineID['ID']);
}

$articleList = $article->fetchAllArticlesForSelectedMagazine($_GET['magazine'], $conn);

if (isset($_GET['article'])) {
	$articleData = $article->fetchArticleData($_GET['article'], $conn);
}

include( 'header.php' );
?>


<main>

    <div class="container-fluid">
        <h1 class="display-4">All Articles</h1>
        
        <div class="columns-top">
            <select name="magazine" class="form-control">
                <option value="">Number 17</option>
                <option value="">Number 16</option>
            </select>
            
        </div>
        
        <br>
        
        <div class="columns">
            <div class="column-left">
				<?php 
				
				while ($row = $articleList->fetch_assoc()) {
					echo '<a  href="admin/allarticles.php?magazine='.$_GET['magazine'].'&article='.$row['ID'].'"><div class="article_link">';
					echo $row['title'];
					echo '</div></a>';
				}
            
            
            	?>
            </div>
			<?php if (isset($_GET['article'])) { ?>
            <div class="column-right">
				<h3><?php echo($articleData['title']) ?></h3>
				
				<p>Author: <?php echo($userByArticle->getArticleAuthor($_GET['article'], $conn)) ?></p>
				<p>Designer: </p>
				
				<p><?php echo($articleData['article_text']) ?></p>
			
			</div>
			
			<?php } ?>
            
        </div>
        
        
    </div>
    
</main>