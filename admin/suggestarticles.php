<?php

include_once( 'sessioncheck.php' );

include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );
include_once( '../classes/Notification.php' );

$article = new Article( $conn );
$userByArticle = new UserByArticle( $conn );
$notification = new Notification( $conn );

include( 'header.php' )

?>

<main>
	<div class="container-fluid">

		<?php 
	
			if (isset($_GET['status'])) {
				
				if ($_GET['status'] == 1000 || $_GET['status'] == 1001) {
					?>

		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success!</strong> New suggestion has been submitted.
		</div>

		<?php
		}

		if ( $_GET[ 'status' ] == 999 ) {

			?>

		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Fail!</strong> Title already exists.
		</div>

		<?php
		}

		}

		?>

		<h1 class="display-4">Suggested Articles <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#myModal">
    New Suggestion
  </button></h1>
		
		<br>

		<div class="modal fade" id="myModal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<div class="modal-header">
						<h4 class="modal-title">Suggest Article</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
						<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']) ?>">

							<div class="form-group">
								<label for="sel1">Enter Article Title</label>
								<input type="text" name="title" placeholder="Title.." required class="form-control">
							</div>

							<div class="form-w-50-l">
								<div class="form-group">
									<label for="sel1">Select Article Section</label>
									<select name="section" class="form-control" id="sel1">
										<option value="IT Club">IT Club</option>
										<option value="MultiKulti">MultiKulti</option>
										<option value="Fun">Fun</option>
										<option value="In Between">In Between</option>
										<option value="Like">Like</option>

									</select>
								</div>
							</div>
							<div class="form-w-50-r">
								<div class="form-group">
									<label for="sel1">Enter Page Number</label>
									<input type="number" min="1" max="6" name="page_number" placeholder="Page number.." required class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label for="comment">Enter Short Description</label>
								<textarea class="form-control" maxlength="250" rows="5" name="description" placeholder="Description"></textarea>
							</div>
							<button class="btn btn-primary" name="suggest_article" type="submit">Send Suggestion</button>
						</form>
					</div>



				</div>
			</div>
		</div>

		<table class="table table-striped">
			<thead>
				<tr>
					<th>Article title</th>
					<th>Suggested by</th>
					<th>Section</th>
					<th>Description</th>
					<th>Number of Pages</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				
				<?php 
					
					$suggestedArticleList = $userByArticle->fetchAllSuggestedArticles($conn);
					
					while ($list = $suggestedArticleList->fetch_assoc()) {
						echo '<tr>';
						echo '<td>'.$list['title'].'</td>';
						echo '<td>'.$list['first_name'].' '.$list['last_name'].'</td>';
						echo '<td>'.$list['section'].'</td>';
						echo '<td>'.$list['description'].'</td>';
						echo '<td>'.$list['page_number'].'</td>';
						echo '<td>';
						echo '<a href="api/article/approve.php?id='.$list['article_ID'].'" role="button" class="btn btn-primary btn-sm">Approve</a> ';
						echo '<a href="admin/modifysuggestedarticle.php?id='.$list['article_ID'].'" role="button" class="btn btn-primary btn-sm">Modify</a> ';
						echo '<a href="api/article/decline.php?id='.$list['article_ID'].
							 '&user='.$list['user_ID'].'&token='.$list['user_by_article'].
							 '" role="button" class="btn btn-danger btn-sm">Decline</a> ';
						echo '</td>';
						echo '</tr>';
					}
				
				?>

				</tbody>
			</table>

	</div>

</main>