<?php

include_once( 'sessioncheck.php' );
include( 'header.php' );
?>

<main>
	<div class="container-fluid">

		<?php 
		
			if (isset($_GET['status'])) {
				switch ($_GET['status']) {
					case 'success':
						echo('<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Success!</strong> You have opened new issue.
							</div>');
						break;
						
					case 'issuenotclosed':
						echo('<div class="alert alert-success alert-warning">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Warning!</strong> First you have to close current magazine, then open a new one.
							</div>');
						break;
						
					case 1000: 
						echo('<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Success!</strong> You have uploaded design of magazine!
							</div>');
						$magazine->setMagazineToFinished($_GET['name'], $_GET['magazine'], $conn);
						break;
				}
			}
		
		?>

		<h1 class="display-4">Magazine
		
			<?php
				
				if ($_SESSION['user_type'] == 1) {
					echo ('<button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#myModal">
							Create New
					  </button>');
			?>	
			
			<div class="modal" id="myModal">
				<div class="modal-dialog">
				  <div class="modal-content">

					<div class="modal-header">
					  <h4 class="modal-title">Create New Magazine</h4>
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>


					<div class="modal-body">
					  <form style="font-size: 16px; font-weight: 400" method="post" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']) ?>">
						  
						  <div class="form-group">
							<label for="date">Writing deadline: </label>
							<input type="date" required name="writing_deadline" class="form-control" >
						  </div>
						  
						  <div class="form-group">
							<label for="date">Revision deadline:</label>
							<input type="date" required name="revision_deadline"  class="form-control" >
						  </div>
						  
						  <div class="form-group">
							<label for="email">Design deadline:</label>
							<input type="date" required name="design_deadline"  class="form-control" i>
						  </div>
						  
						  <div class="form-group">
							<label for="email">Release date:</label>
							<input type="date" required name="release_date" class="form-control" >
						  </div>
						  
						  <button type="submit" class="btn btn-block btn-primary" name="create_magazine">Create</button>

						</form>
					</div>

				  </div>
				</div>
			  </div>

			
			<?php } ?>
			
			
		</h1>
		<br>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Number</th>
					<th>Final</th>
					<th>Deadlines (Writing, Revision, Design)</th>
					<th>Release Date</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				
					$magazineList = $magazine->fetchAllMagazines($conn);
					$lastID = $magazine->getLastMagazineID($conn);
				
					while ($list = $magazineList->fetch_assoc()) {
						echo('<tr><td>'.$list['ID']);
						
						if ($list['ID'] == $lastID['ID']) {
							echo(' <span class="badge badge-pill badge-primary">Current</span>');
						}
						
						echo('</td>');
						if ($list['final']){
							echo('<td><a  href="articledesign/'.$list['final'].'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.$list['final'].'</a></td>');
						}
						else {
							echo('<td>No final.</td>');
						}
						echo('<td>'.$list['writing_deadline'].' / '.$list['revision_deadline'].' / '.$list['design_deadline'].'</td>');
						echo('<td>'.$list['release_date'].'</td>');
						echo('<td>');
						echo('<a href="admin/allarticles.php?magazine='.$list['ID'].'" role="button" class="btn btn-primary btn-sm">Read Articles</a> ');
						echo('<a href="admin/designs.php?magazine='.$list['ID'].'" role="button" class="btn btn-primary btn-sm">View Designs</a> ');
						if (!$list['final'] && $_SESSION['user_type'] <= 2) echo('<form action="admin/uploaddesign.php?design='.$list['ID'].'&final=final" method="post" enctype="multipart/form-data">
								<input type="file" name="file" />
								<input type="submit" name="upload" class="btn btn-success btn-sm" value="Upload Final and Close Issue" />
								</form>');

						echo('</td></tr>');
					}
				
				?>
			</tbody>
		</table>


	</div>


</main>