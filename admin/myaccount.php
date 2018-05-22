<?php

include_once( 'sessioncheck.php' );

if ( !isset( $_GET[ 'id' ] ) ) {
	header( 'location: myaccount.php?id=' . $_SESSION[ 'user_id' ] );
}


include_once( '../api/Database.php' );
include_once( '../classes/UserType.php' );
include_once( '../classes/User.php' );

$user = new User( $conn );
$userType = new UserType( $conn );
$result = $userType->getAllRoles( $conn );

$userData = $user->fetchAccountData( $_GET[ 'id' ], $conn );

if ($userData['user_type'] == 6) {
	header( 'location: myaccount.php?id=' . $_SESSION[ 'user_id' ] );
}

$userTypeData = $user->fetchUserType( $_GET[ 'id' ], $conn );
$userRegistrationDate = $user->fetchRegistrationDate( $_GET[ 'id' ], $conn );

include( 'uploadavatar.php' );
include( 'header.php' )

?>

<main>
	<div class="container-fluid">

		<h1 class="display-4" align="center">
		
		<form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?id='.$_GET['id']) ?>" enctype="multipart/form-data">
					  <div class="form-group"><?php 
							echo($userData['first_name']);?>
							
						  <?php
							echo($userData['last_name']); ?>
			</div></form>  
			
		</h1>

	



		<div align="center">
			<span class="badge badge-danger">
				<?php echo($userTypeData['description']) ?>
			</span>
		</div>

		<div align="center">

			<form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?id='.$_GET['id']) ?>" enctype="multipart/form-data">
				<div class="form-group">
					Member since
					<?php
					echo date( 'M', strtotime( $userRegistrationDate[ 'registration_date' ] ) );
					echo( ',' );
					?>
					<?php 
						  echo date('Y', strtotime($userRegistrationDate['registration_date']));
							?>
				</div>
			</form>

		</div>
		<div class="row">
				<div class="col-sm-6">
		<p>Profile picture</p>
					
					<center><img class="profile_photo" 
								 <?php 
								 
								 if ($userData['avatar']) echo('src="admin/members/'.$userData['avatar'].'"');
								 else echo('src="admin/members/default.jpg"');
								 
								 ?>></center>
					<br>
				<?php if ($_SESSION['user_type'] == 1 || $_GET['id'] == $_SESSION['user_id']) { ?>	
					 <form method="post" enctype="multipart/form-data" action="">
						<input type="file" name="file[]" id="file" class="selectphoto"  /><br>

						<input type="submit" value="Upload File" name="submit" id="upload" class="upload  btn btn-primary"/>
			</form><br>
<?php } ?>


		<?php if ($_SESSION['user_type'] == 1 || $_GET['id'] == $_SESSION['user_id']) { ?>
		<form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?id='.$_GET['id']) ?>" enctype="multipart/form-data">

						<div class="form-group">
							<label for="pwd">Change password</label>
							<input type="password" name="password" class="form-control" id="txtNewPassword" placeholder="New password">
						</div>

						<div class="form-group">
							<input type="password" name="confirm_password" class="form-control" id="txtConfirmPassword" onChange="checkPasswordMatch()" placeholder="Confirm password">
							<div id="divCheckPasswordMatch"></div>
						</div>

						<div class="form-group">
							<button id="sender" type="submit" name="update_password" class="btn btn-primary">Update Password</button>
						</div>
		</form>
					<?php } if ($_SESSION['user_type'] == 1 && $_SESSION['user_id'] != $_GET['id']) { ?>
					<br><p>Change User Type</p>
					
					<form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?id='.$_GET['id']) ?>" enctype="multipart/form-data">
					<div class="form-group">
					<select name="user_type" class="form-control" id="sel1">
						<?php 
								
								while ($row = $result->fetch_assoc()) {
									echo('<option value="'.$row['ID'].'">'.$row['description'].'</option>');
								}

							?>
					</select>
						<br>
						<button type="submit" name="update_role" class="btn btn-primary">Update Role</button>
				</div>
					</form><?php } ?>

		</div>


		<div class="col-sm-6">
			<form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&type='.$_SESSION['user_id'])
											?>" enctype="multipart/form-data">
				<div class="form-group">
					<label for="sel1">Username</label>
					<input <?php if($_SESSION[ 'user_type']> 1) echo('disabled') ?> type="text" name="username" required class="form-control" value=
					<?php 
					echo($userData['username']); 
					  ?> >
				</div>


				<div class="form-group">
					<label for="sel1">Email</label>
					<input <?php if($_SESSION[ 'user_type']> 1) echo('disabled') ?> type="text" name="email" required class="form-control" value=
					<?php 
						echo($userData['email']);
	
					?> >
				</div>

				<div class="form-group">
					<label for="sel1">Date of birth</label>
					<input <?php if($_SESSION[ 'user_type']> 1) echo('disabled') ?> type="text" name="date_of_birth" required class="form-control" value=
					<?php 
						echo($userData['date_of_birth']);
	
					?> >
				</div>

				<div class="form-group">
					<label for="sel1">Telephone number</label>
					<input type="text" name="telephone_number" class="form-control" value="<?php echo($userData[ 'telephone_number']); ?>">
				</div>

				<div class="form-group">
					<label for="sel1">First name</label>
					<input <?php if($_SESSION[ 'user_type']> 1) echo('disabled') ?> type="text" name="first_name" required class="form-control" value=
					<?php 
						echo($userData['first_name']);
					?>>
				</div>

				<div class="form-group">
					<label for="sel1">Last name</label>
					<input <?php if($_SESSION[ 'user_type']> 1) echo('disabled') ?> type="text" name="last_name" required class="form-control" value=
					<?php 
						echo($userData['last_name']);
					?>>
				</div>

				<div class="form-group">
					<label for="sel1">Study area</label>
					<select <?php if($_SESSION[ 'user_type']> 1) echo('disabled') ?> name="study_area" class="form-control" id="sel1" value="<?php 
						echo($userData['study_area']);
					?>">
						<option <?php if($userData[ 'study_area'] == 'Information Systems') echo 'selected' ?> value="Information Systems">Information Systems</option>
						<option <?php if($userData[ 'study_area'] == 'Business Systems') echo 'selected' ?> value="Business Systems">Business Systems</option>
						<option <?php if($userData[ 'study_area'] == 'Economics of Entrepreneurship') echo 'selected' ?> value="Economics of Entrepreneurship">Economics of Entrepreneurship</option>
						<option <?php if($userData[ 'study_area'] == 'Master Study of Informatics') echo 'selected' ?> value="Master Study of Informatics">Master Study of Informatics</option>
						<option <?php if($userData[ 'study_area'] == 'Master Study of Economics of Entrepreneurship') echo 'selected' ?> value="Master Study of Economics of Entrepreneurship">Master Study of Economics of Entrepreneurship</option>
						<option <?php if($userData[ 'study_area'] == 'Applied IT Techonogies in Bussiness') echo 'selected' ?> value="Applied IT Techonogies in Bussiness">Applied IT Techonogies in Bussiness</option>
					</select>
				</div>

				<div class="form-group">
					<label for="sel1">Private email address</label>
					<input type="text" name="email_private" class="form-control" value="<?php echo($userData[ 'email_private']); ?> ">
				</div>
				
				
		<?php if ($_SESSION['user_type'] == 1 || $_GET['id'] == $_SESSION['user_id']) { ?>

				<button type="submit" class="btn btn-primary" name="update">Save changes</button><?php } ?>
				
				<?php
				
					if ($_SESSION['user_type'] == 1) { ?>
				
				<a href="admin/myaccount.php?id=<?php echo($_GET['id'].'&remove=true')?>" role="button" class="btn btn-danger" name="remove_account" >Remove account</a>
				
				<?php }
				
				if ($_SESSION['user_type'] == 1 && ($_GET['id'] != $_SESSION['user_id'])) { ?>
				
				<a href="admin/myaccount.php?id=<?php echo($_GET['id'].'&editor=true')?>" role="button" class="btn btn-dark" name="remove_account" >Make Editor</a>
				
				<?php } ?>


			</form>
		</div>
		</div>
	</div>
</main>