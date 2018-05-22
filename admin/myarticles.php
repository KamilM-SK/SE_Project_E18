<?php

include_once( 'sessioncheck.php' );

if (!isset($_GET['user'])) {
	header ('location: myarticles.php?user='.$_SESSION['user_id']);
}

if (isset($_GET['user'])) {
	if ($_GET['user'] != $_SESSION['user_id']) {
		header ('location: 404.php');
	}
}


include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );
include_once( 'header.php' );

$article = new Article($conn);
$userByArticle = new UserByArticle($conn);

?>

<main>

	<div class="container-fluid">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#home">Current Articles</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#menu1">Past Articles</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#menu2">Pick Articles</a>
			</li>
		</ul>

		<div class="tab-content">
			<div id="home" class="container tab-pane active"><br>
				<table class="table table-striped">
    <thead>
      <tr>
        <th>Article Title</th>
        <th>Deadline</th>
        <th>Section</th>
		<th>Number of Pages</th>
		<th>Actions</th>
      </tr>
    </thead>
    <tbody>
		<?php 
					
					$articlesForWritingList = $userByArticle->fetchAllCurrentArticlesGivenToJournalist($_GET['user'], $conn);
					
					while ($list = $articlesForWritingList->fetch_assoc()) {
						echo '<tr>';
						echo '<td>'.$list['title'].'</td>';
						echo '<td>'.$list['writing_deadline'].'</td>';
						echo '<td>'.$list['section'].'</td>';
						echo '<td>'.$list['page_number'].'</td>';
						echo '<td>';
						echo '<a href="admin/writearticle.php?userid='.$_GET['user'].'&articleid='.$list['ID'].'" role="button" class="btn btn-success btn-sm">Edit</a> ';
						echo '<a class="btn btn-primary btn-sm" role="button" href="admin/libs/revision.php?article_id='.$list['ID'].'">Send For Revision</a>  ';
						if($userByArticle->cancelButtonArticle($_GET['user'],$list['ID'], $conn) == 1){
						echo '<a href="api/article/cancel.php?user='.$_GET['user'].'&id='.$list['ID'].'" 
							role="button" class="btn btn-danger btn-sm">Cancel</a> ';
						}
						echo '</td>';
						echo '</tr>';
					}
				
				?>
    </tbody>
  </table>
			</div>
			<div id="menu1" class="container tab-pane fade"><br>
				<h3>Past Articles</h3>
				<table class="table table-striped">
					<thead>
					  <tr>
						<th>Article Title</th>

						<th>Section</th>
						<th>Number of Pages</th>
						<th>Actions</th>
					  </tr>
					</thead>
					<tbody>
		<?php 
					
					$aList = $userByArticle->fetchFinalArticles($_GET['user'], $conn);
					
					while ($list = $aList->fetch_assoc()) {
						echo '<tr>';
						echo '<td>'.$list['title'].'</td>';

						echo '<td>'.$list['section'].'</td>';
						echo '<td>'.$list['page_number'].'</td>';
						echo '<td>';
						echo '<a href="admin/allarticles.php?magazine='.$list['magazine'].'&article='.$list['ID'].'" role="button" class="btn btn-success btn-sm">Read</a> ';
						
						echo '</td>';
						echo '</tr>';
					}
				
				?>
    </tbody>
  </table>
			</div>
			<div id="menu2" class="container tab-pane fade"><br>
				<h3>Check All Articles You Want to Write</h3>
				<p>Pick all articles you want to contribute to.</p>
				<br>
				<?php 
				
					$result = $article->fetchAllArticlesAvailableForWriting($conn);
				
					if ($result->num_rows == 0) {
						echo('<h3>There are no articles left for writing.</h3>');
					}
					
					else {
				?>
				
				<form method="post" action="admin/libs/pickarticles.php?user=<?php echo($_SESSION['user_id']) ?>" enctype="multipart/form-data">
				
					<?php 
					
						while ($row = $result->fetch_assoc()) {
							echo '<div class="form-check">';
							echo '<label class="form-check-label">';
							echo '<input type="checkbox" name="article_id[]" class="form-check-input" value="'.$row['ID'].'">';
							echo '<a data-toggle="tooltip" title="'.$row['description'].'">'.$row['title'];
							echo ' - '.$row['section'].' - <em>'.$row['page_number'].' pages</em>';
							echo ' </label></a>';
							echo '</div><br>';
						}
					
					?>
					
					<button type="submit" name="select_articles_for_writing" class="btn btn-primary">Save</button>
					
				</form>
				
				<?php } ?>
				
			</div>
		</div>

	</div>

</main>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>