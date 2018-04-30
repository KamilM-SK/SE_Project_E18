<nav id="sitelink__button">
<<<<<<< HEAD

=======
	
>>>>>>> ddfa8218943ceff305492005839affb1a310cb2b

	<a href="index.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '/tripit/admin/index.php') echo('active'); ?>"> 
			 Magazines  </div> </a>

	<div class="nav__headline"> Articles </div>
<<<<<<< HEAD

	<a href="admin/allarticles.php?magazine=<?php echo($lastMagazineID['ID']) ?>"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  All Articles  </div> </a>

	<a href="admin/suggestarticles.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Suggest Articles  </div> </a>
	
	
	<?php if ($_SESSION['user_id'] == 1 ) { ?>
	<a href="admin/assignarticle.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Assign Article  </div> </a>
	
	<?php } ?>

	<a href="admin/myarticles.php?user=<?php echo($_SESSION['user_id']) ?>"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  My Articles  </div> </a>

	<div class="nav__headline"> Account </div>

	<?php if ($_SESSION['user_id'] == 1 ) { ?>
	
	<a href="admin/register.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Add New Member  </div> </a>
	
	<?php } ?>
=======
	
	<a href=""> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  All Articles  </div> </a>
	
	<a href="admin/suggestarticles.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Suggest Articles  </div> </a>
	
	<div class="nav__headline"> Account </div>
	
	<a href="admin/register.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Add New Member  </div> </a>
>>>>>>> ddfa8218943ceff305492005839affb1a310cb2b

</nav>