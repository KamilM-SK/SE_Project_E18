<?php

include_once( 'sessioncheck.php' );

if (!isset($_GET['user'])) {
	header ('location: admin/designarticles.php?user='.$_SESSION['user_id']);
}

if (isset($_GET['user'])) {
	if ($_GET['user'] != $_SESSION['user_id']) {
		header ('location: admin/404.php');
	}
}

include_once( '../api/Database.php' );
include_once( '../classes/Article.php' );
include_once( '../classes/UserByArticle.php' );
include_once( '../classes/Photo.php' );
include_once( '../classes/Design.php' );


$article = new Article( $conn );
$userByArticle = new UserByArticle( $conn );
$photo = new Photo($conn);
$design = new Design($conn);

include( 'header.php' );

?>

<main>

    <div class="container-fluid">
		
		<?php 
	
			if (isset($_GET['status'])) {
				
				if ($_GET['status'] == 1000) {
					$article->setArticleToFinished($conn, $_GET['design']);
					$design->bindDesignToArticleAndUser($_GET['user'], $_GET['design'], $_GET['name'], $conn);
					?>

		<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success!</strong> You've uploaded design to article!
		</div>

		<?php
		}

		}

		?>


        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home">Current Designs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu1">Pick Articles</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="home" class="container tab-pane active"><br>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Article Title</th>
                            <th>Deadline</th>
                            <th>Number of Pages</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        $articlesForDesignList = $userByArticle->fetchAllCurrentArticlesGivenToDesigner($_SESSION['user_id'], $conn);

                        while ($list = $articlesForDesignList->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $list['title'] . '</td>';
                            echo '<td>' . $list['design_deadline'] . '</td>';
                            echo '<td>' . $list['page_number'] . '</td>';
                            echo '<td>' . $list['section'] . '</td>';
							echo '<td>';
							$photoNumber = $photo->getAllPhotosForArticle($list['ID'], $conn);
							if ($photoNumber->num_rows > 0) {
								echo '<a href="admin/zipphotos.php?article='.$list['ID'].'" role="button" class="btn btn-warning btn-sm">Download Photos</a> ';
							}
                            echo '<a href="admin/articletext.php?id='.$list['ID'].'" target="_blank role="button" class="btn btn-warning btn-sm">Download Text</a> ';

							if($userByArticle->cancelButtonDesign($_SESSION['user_id'],$list['ID'], $conn) == 1)
echo '<a href="admin/canceldesign.php?user='.$_SESSION['user_id'].'&id='.$list['ID'].'" target="_blank role="button" class="btn btn-danger btn-sm">Cancel</a>' ;
                            echo '<form action="admin/uploaddesign.php?design='.$list['ID'].'" method="post" enctype="multipart/form-data">

<input type="file" name="file" />

<input type="submit" name="upload" class="btn btn-success btn-sm" value="Upload" />

</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
				
				?>
                    </tbody>
                </table>
            </div>
            <div id="menu1" class="container tab-pane fade"><br>
                <h3>Check All Articles You Want to Design</h3>
                <p>Pick all articles you want to contribute to.</p>
                <br>
                <?php
                $result = $article->fetchAllArticlesAvailableForDesign($conn);

                if ($result->num_rows == 0) {
                    echo('<h3>There are no articles left for designing.</h3>');
                } else {
                    ?>

                    <form method="post" action="admin/libs/pickarticles.php?user=<?php echo($_SESSION['user_id']) ?>" enctype="multipart/form-data">

                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="form-check">';
                            echo '<label class="form-check-label">';
                            echo '<input type="checkbox" name="article_id[]" class="form-check-input" value="' . $row['ID'] . '">';
                            echo $row['title'] . ' - ' . $row['section'] . ' - <em>' . $row['page_number'] . ' pages</em>';
                            echo ' </label>';
                            echo '</div><br>';
                        }
                        ?>

                        <button type="submit" name="select_articles_for_design" class="btn btn-primary">Save</button>

                    </form>

                <?php } ?>

            </div>
        </div>

    </div>

</main>