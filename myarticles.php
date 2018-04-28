<?php

session_start();

if (!isset($_GET['user'])) {
	header ('location: admin/404.php');
}

if (isset($_GET['user'])) {
	if ($_GET['user'] != $_SESSION['user_id']) {
		header ('location: admin/404.php');
	}
}


include( '../api/Database.php' );

include( '../classes/UserType.php' );

include( '../classes/User.php' );

include( 'header.php' );

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
        <th>Pages</th>
		<th>Section</th>
		<th>Designer</th>
		<th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		<td>mary@example.com</td>
		<td>mary@example.com</td>
		<td>
		  <a href = "#" class="btn btn-primary btn-sm" role="button">Modify</a>
		  <a href = "#" class="btn btn-primary btn-sm" role="button">Upload Photo</a>
		  <a href = "#" class="btn btn-danger btn-sm" role="button">Send for revision</a>
		</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
		<td>mary@example.com</td>
		<td>mary@example.com</td>
		<td>
		  <a href = "#" class="btn btn-primary btn-sm" role="button">Modify</a>
		  <a href = "#" class="btn btn-primary btn-sm" role="button">Upload Photo</a>
		  <a href = "#" class="btn btn-danger btn-sm" role="button">Send for revision</a>
		</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
		<td>july@example.com</td>
		<td>july@example.com</td>
		<td>
		  <a href = "#" class="btn btn-primary btn-sm" role="button">Modify</a>
		  <a href = "#" class="btn btn-primary btn-sm" role="button">Upload Photo</a>
		  <a href = "#" class="btn btn-danger btn-sm" role="button">Send for revision</a>
		</td>
      </tr>
    </tbody>
  </table>
			</div>
			<div id="menu1" class="container tab-pane fade"><br>
				<h3>Menu 1</h3>
				<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			</div>
			<div id="menu2" class="container tab-pane fade"><br>
				<h3>Menu 2</h3>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
			</div>
		</div>

	</div>

</main>