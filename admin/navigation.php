<nav id="sitelink__button">


	<a href="admin/magazine.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '/tripit/admin/index.php') echo('active'); ?>"> 
			 Magazine  </div> </a>

	<div class="nav__headline"> Articles </div>

	<a href="admin/allarticles.php?magazine=<?php echo($lastMagazineID['ID']) ?>"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  All Articles  </div> </a>

	<a href="admin/suggestarticles.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Suggest Articles  </div> </a>
	
	
	<?php if ($_SESSION['user_type'] == 1 ) { ?>
	<a href="admin/assignarticle.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Assign Article  </div> </a>
	
	<?php } ?>
	
	<?php if ($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 4) { ?>
	<a href="admin/reviewarticles.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Revise Articles  </div> </a>
	
	<?php } ?>
	
	<?php if ($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 2) { ?>
	<a href="admin/designarticles.php?user=<?php echo($_SESSION['user_id']) ?>"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Design Articles  </div> </a>
	
	<?php } ?>

	<a href="admin/myarticles.php?user=<?php echo($_SESSION['user_id']) ?>"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  My Articles  </div> </a>
	
	<div class="nav__headline"> Meetings </div>
        
        <?php if ($_SESSION['user_type'] == 1 ) { ?>
        
	<a href="admin/organizemeeting.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Organize  </div> </a>
	
	<?php } ?>
        
        
        <a href="admin/vote.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Vote  </div> </a>

	<div class="nav__headline"> Account </div>
	
	<a href="admin/myaccount.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  My Account  </div> </a>
	
	<a href="admin/messages.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Messages  
		<?php 
		
			if ($numberOfUnseenMessages > 0) echo('<span class="badge badge-pill badge-info">'.$numberOfUnseenMessages.'</span>');
		
		?>
		
		</div> </a>

	<?php if ($_SESSION['user_type'] == 1 ) { ?>
	
	<a href="admin/register.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Add New Member  </div> </a>
	
	<?php } ?>

</nav>