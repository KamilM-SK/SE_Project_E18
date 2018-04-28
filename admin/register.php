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
					<label for="sel1">Enter First Name:</label>
					<input type="text" name="first_name" placeholder="First Name..." required class="form-control">
				</div>

				<div class="form-group">
					<label for="sel1">Enter Last Name:</label>
					<input type="text" name="last_name" placeholder="Last Name..." required class="form-control">
				</div>

				<div class="form-group">
					<label for="sel1">Enter Username:</label>
					<input type="text" name="username" placeholder="Username..." required class="form-control">
				</div>

				<div class="form-group">
					<label for="sel1">Enter Email:</label>
					<input type="email" name="email" placeholder="Email..." required class="form-control">
				</div>
				
				<div class="form-group">
					<label for="sel1">Enter Date of Birth:</label>
					<input type="date" name="date_of_birth" required class="form-control">
				</div>
				
				<div class="form-group">
					<label for="sel1">Select Study Area:</label>
						<select name="study_area" class="form-control" id="sel1">
							<option value="Information Systems">Information Systems</option>
							<option value="Business Systems">Business Systems</option>
							<option value="Economics of Entrepreneurship">Economics of Entrepreneurship</option>
							<option value="Master Study of Informatics">Master Study of Informatics</option>
							<option value="Master Study of Economics of Entrepreneurship">Master Study of Economics of Entrepreneurship</option>
							<option value="Applied IT Techonogies in Bussiness">Applied IT Techonogies in Bussiness</option>
						</select>
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