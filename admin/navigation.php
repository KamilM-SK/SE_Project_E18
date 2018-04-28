<nav id="sitelink__button">
	

	<a href="index.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '/tripit/admin/index.php') echo('active'); ?>"> 
			 Magazines  </div> </a>

	<div class="nav__headline"> Articles </div>
	
	<a href="admin/allarticles.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  All Articles  </div> </a>
	
	<div class="nav__headline"> Account </div>
	
	<a href="admin/register.php"> <div class="nav__link <?php if ($_SERVER["SCRIPT_NAME"] == '') echo('active'); ?>"> 
			  Add New Member  </div> </a>

</nav>