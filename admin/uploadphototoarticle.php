<?php

if (isset($_POST['submit'])) {
	$j = 0;    
	 
	for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
		$target_path = "../uploads/";   
		$validextensions = array("jpeg", "jpg", "png", "JPEG", "JPG", "PNG"); 
		
		$ext = explode('.', basename($_FILES['file']['name'][$i]));   
		$file_extension = end($ext);
		
		$new_file_name = md5(uniqid()) . "." . $ext[count($ext) - 1];
	
		$target_path = $target_path . $new_file_name;    
		$j = $j + 1;   
		
		if (($_FILES["file"]["size"][$i] < 100000000000000) && in_array($file_extension, $validextensions)) {

			if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
				$photo->addPhotoToDatabase($_GET['userid'], $new_file_name, $_GET['articleid'], $conn);

			} else {  
				echo $j. ').<span id="error">please try again!.</span><br/><br/>';
			}
		} else {    
			echo $j. ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
		}
	}
	
	header('location: writearticle.php?userid='.$_GET['userid'].'&articleid='.$_GET['articleid']);
}
?>