<?php
include_once( 'sessioncheck.php' );

if (isset($_POST['upload'])) {
	$targetfolder = "../articledesign/";

	$targetfolder = $targetfolder . basename( $_FILES['file']['name']) ;

	$ok=1;

	$file_type=$_FILES['file']['type'];

	if ($file_type=="application/pdf") {

	 if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder))

	 {
	 if (isset($_GET['final'])) {
		 header('location: magazine.php?user='.$_SESSION['user_id'].'&status=1000&magazine='.$_GET['design'].'&name='.$_FILES['file']['name']);
	 }
	 else {
		 header('location: designarticles.php?user='.$_SESSION['user_id'].'&status=1000&design='.$_GET['design'].'&name='.$_FILES['file']['name']);
	 }
	 echo "The file ". basename( $_FILES['file']['name']). " is uploaded";

	 }

	 else {
	 header('location: designarticles.php?user='.$_SESSION['user_id'].'&status=999');
	 echo "Problem uploading file";

	 }

	}

	else {
	 header('location: designarticles.php?user='.$_SESSION['user_id'].'&status=998');
	 echo "You may only upload PDFs, JPEGs or GIF files.<br>";

	}
}

?>