<?php

include_once( 'sessioncheck.php' );

include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );

$article = new Article( $conn );


if ( !isset( $_GET[ 'id' ] ) ) {
	header( 'location: admin/404.php' );
}

if ( $_SESSION[ 'user_type' ] != 1 ) {
	header( 'location: admin/403.php' );
}

$articleExists = $article->checkIfArticleExists($_GET['id'], $conn);

if ($articleExists != 1) {
	header( 'location: admin/404.php' );
}

else {
	$result = $article->fetchSuggestedArticleDataByID($_GET['id'], $conn);
	
}

include( 'header.php' );
?>

<main>
	<div class="container-fluid">

		<h1 class="display-4">Modify Suggested Articles</h1>

		<form method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']) ?>?id=<?php echo($_GET['id']) ?>">

			<div class="form-group">
				<label for="sel1">Enter Article Title</label>
				<input type="text" name="title" value="<?php echo($result['title']) ?>" required class="form-control">
			</div>

			<div class="form-w-50-l">
				<div class="form-group">
					<label for="sel1">Select Article Section</label>
					<select name="section" class="form-control" id="sel1">
						<option <?php if ($result['section'] == 'IT Club') echo('selected')?> value="IT Club">IT Club</option>
						<option <?php if ($result['section'] == 'MultiKulti') echo('selected')?> value="MultiKulti">MultiKulti</option>
						<option <?php if ($result['section'] == 'Fun') echo('selected')?>  value="Fun">Fun</option>
						<option <?php if ($result['section'] == 'In Between') echo('selected')?> value="In Between">In Between</option>
						<option <?php if ($result['section'] == 'Like') echo('selected')?> value="Like">Like</option>

					</select>
				</div>
			</div>
			<div class="form-w-50-r">
				<div class="form-group">
					<label for="sel1">Enter Page Number</label>
					<input type="number" min="1" max="6" name="page_number" value="<?php echo($result['page_number']) ?>" required class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label for="comment">Enter Short Description</label>
				<textarea class="form-control" maxlength="250" rows="5" name="description" ><?php echo($result['description']) ?></textarea>
			</div>
			<button class="btn btn-primary" name="update_suggest_article" type="submit">Modify Suggestion</button>
		</form>

	</div>
</main>