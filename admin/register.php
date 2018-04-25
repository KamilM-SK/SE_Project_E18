<?php

session_start();

if ($_SESSION['user_type'] != 1) {
	header('location: 403.php');
}

include( '../api/Database.php' );
include( '../classes/UserType.php' );
include( '../classes/User.php' );

$userType = new UserType();
$result = $userType->getAllRoles($conn);

$user = new User($conn);

include( 'header.php' )

?>

<main>
	<div class="container-fluid">
		<h1 class="display-4">Register New Account</h1>

		<form method="post" action="<?php echo($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
			<div class="col-sm-5">
				<div class="form-group">
					<input type="text" name="first_name" placeholder="First Name..." required class="form-control">
				</div>

				<div class="form-group">
					<input type="text" name="last_name" placeholder="Last Name..." required class="form-control">
				</div>

				<div class="form-group">
					<input type="text" name="username" placeholder="Username..." required class="form-control">
				</div>

				<div class="form-group">
					<input type="email" name="email" placeholder="Email..." required class="form-control">
				</div>

				<div class="form-group">
					<label for="sel1">Select Role for New Member:</label>
						<select name="user_type" class="form-control" id="sel1">
							<?php 
								
								while ($row = $result->fetch_assoc()) {
									echo('<option value="'.$row['ID'].'">'.$row['description'].'</option>');
								}

							?>
						</select>
				</div>
				
				<button type="submit" name="register">Register</button>

			</div>
		</form>
	</div>

</main>