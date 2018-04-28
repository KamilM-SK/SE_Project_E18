<?php

session_start();

include( '../api/Database.php' );

include( '../classes/UserType.php' );

include( '../classes/User.php' );

include( 'header.php' )

?>

<!doctype html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

	<title>Untitled Document</title>

	<link href="../api/res/style.css" rel="stylesheet" type="text/css">

</head>

<body>
	<main>
		<div class="container-fluid">
			<p>Suggested Articles
			<a href="#" role="button" class="btn btn-dark btn-sm">Suggest New</a>
			</p>
			
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Article title</th>
						<th>Date</th>
						<th>Suggested by</th>
						<th>Description</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Article 1</td>
						<td>20.5.2018.</td>
						<td>Petar Jadek</td>
						<td>Description</td>
						<td><a href="#" role="button" class="btn btn-primary btn-sm">Modify</a>
							<a href="#" role="button" class="btn btn-primary btn-sm">Approve</a>
							<a href="#" role="button" class="btn btn-danger btn-sm">Decline</button>
						</td>
					</tr>
					<tr>
						<td>Article 1</td>
						<td>20.5.2018.</td>
						<td>Petar Jadek</td>
						<td>Description</td>
						<td><a href="#" role="button" class="btn btn-primary btn-sm">Modify</a>
							<a href="#" role="button" class="btn btn-primary btn-sm">Approve</a>
							<a href="#" role="button" class="btn btn-danger btn-sm">Decline</button>
						</td>
					</tr>
					<tr>
						<td>Article 1</td>
						<td>20.5.2018.</td>
						<td>Petar Jadek</td>
						<td>Description</td>
						<td><a href="#" role="button" class="btn btn-primary btn-sm">Modify</a>
							<a href="#" role="button" class="btn btn-primary btn-sm">Approve</a>
							<a href="#" role="button" class="btn btn-danger btn-sm">Decline</button>
						</td>
					</tr>


				</tbody>
			</table>
		</div>
	</main>
</body>
</html>