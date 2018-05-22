<?php

include_once( 'sessioncheck.php' );
include_once( '../api/Database.php' );
include_once( '../classes/Magazine.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/Photo.php' );
include_once( '../classes/UserByArticle.php' );
include_once( '../classes/Comment.php' );
include_once( '../classes/User.php' );

$magazine = new Magazine($conn);
$photo = new Photo($conn);
$article = new Article($conn);
$userByArticle = new UserByArticle($conn);
$comment = new Comment($conn);
$user = new User($conn);

if (!isset($_GET['magazine']) || $_GET['magazine'] == '') {
	$magazineID = $magazine->getLastMagazineID($conn);
	header('location: allarticles.php?magazine='.$magazineID['ID']);
}

$articleList = $article->fetchAllArticlesForSelectedMagazine($_GET['magazine'], $conn);

if (isset($_GET['article'])) {
	$articleData = $article->fetchArticleData($_GET['article'], $conn);
}

$magazineList = $magazine->fetchAllMagazines($conn);

include( 'header.php' );


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"
        ></script>
<script>
    $(function(){
      // bind change event to select
      $('#dynamic_select').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });
</script>

<main>

    <div class="container-fluid">
        <h1 class="display-4">All Articles</h1>
        
        <div class="columns-top">
            <select name="magazine" id="dynamic_select" class="form-control">
                
				<?php 
				
				while ($magList = $magazineList->fetch_assoc()) {
					echo('<option ');
					if ($_GET['magazine'] == $magList['ID']) echo (' selected ');
					echo('value="admin/allarticles.php?magazine='.$magList['ID'].'">'.$magList['name'].'</option>');
				}
				
				?>
			
            </select>
            
        </div>
        
        <br>
        
        <div class="columns">
            <div class="column-left">
				<?php 
				
				while ($row = $articleList->fetch_assoc()) {
					echo '<a  href="admin/allarticles.php?magazine='.$_GET['magazine'].'&article='.$row['ID'].'"><div class="article_link">';
					switch ($row['status']) {
						case 3:
							echo('<i title="Picked for Writing" class="fa fa-pencil"></i> ');
							break;
						case 4:
							echo('<i title="Under revision" class="fa fa-magic"></i> ');
							break;
						case 5:
							echo('<i title="Designing" class="fa fa-heart"></i> ');
							break;
						case 6:
							echo('<i title="Done" class="fa fa-check"></i> ');
							break;
						case 7:
							echo('<i title="Picked for Writing" class="fa fa-pencil"></i> ');
							break;
					}
					echo $row['title'];
					echo '</div></a> ';
				}
            
            
            	?>
            </div>
			<?php if (isset($_GET['article'])) { ?>
            <div class="column-right">
				<h3><?php echo($articleData['title'].' '); 
					if ($articleData['status'] == 4) {
						if ($_SESSION['user_type'] == 1) {
								echo '<a role="button" class="btn btn-dark btn-sm" href="admin/libs/extend.php?id='.$row['ID'].'" >Extend</a> ';
							}
					};
					
					?></h3>
				
				<p>Author: <?php echo($userByArticle->getArticleAuthor($_GET['article'], $conn)) ?></p>
				
				
				<p><?php echo($articleData['article_text']) ?></p>
				
				<br>
				
				<h4>Photos</h4>
				
				<?php 
				
				$photoList = $photo->listAllPhotos($_GET['article'], $conn);
					  
					  if ($photoList->num_rows == 0) {
						  echo 'No photos are included for this article. ';
					  } else {
						  while ($photograph = $photoList->fetch_assoc()) {
							  echo('<div class="img-article"><a href="uploads/'.$photograph['name'].'" target="_blank"><img class="img-thumbnail img-article" src="uploads/'.$photograph['name'].'" /></a>  ');
							  echo('</div>');
						  }
					  }
				
				?>
				<div style="clear: both"></div><br>
				<h4>Comments</h4>
				
				<form method="post" enctype="multipart/form-data" action="">
					<div class="form-group">
						<textarea class="form-control" rows="4" name="comment" required placeholder="Write your comment here..."></textarea><br>
						<button type="submit" name="comment_button" class="btn btn-primary">Comment</button>
					</div>
					
				</form>
				
				<?php 
				
					$commentList = $comment->fetchAllCommentsForSpecifiedArticle($_GET['article'], $conn);
												
					if ($commentList->num_rows > 0) {
						
						while ($comments = $commentList->fetch_assoc()) {
							$userFirstLastName = $user->getFirstLastNameAvatar($comments['user'], $conn);
				?>
				
				  <div class="media border p-3">
					<img src="admin/members/<?php echo($userFirstLastName['avatar']) ?>" class="mr-3 mt-3 rounded-circle" style="width:60px;">
					<div class="media-body">
					  <h4><?php echo($userFirstLastName['first_name'].' '.$userFirstLastName['last_name']) ?><small><i> Posted on
						  <?php
					echo date( 'd M', strtotime( $comments[ 'date_time' ] ) );
					echo( ',' );
					?>
					<?php 
						  echo date('Y', strtotime( $comments[ 'date_time' ]));
							?>
						  
						  </i></small></h4>
					  <p>
						<?php echo($comments['comment_text']) ?>
						</p>      
					</div>
				  </div><br>
				
				<?php 
						}
					} else {
						echo 'No comments on this article. Be the first one to comment it.';
					}
				
				
				?>
			
			</div>
			
			<?php } ?>
            
        </div>
        
        
    </div>
    
</main>