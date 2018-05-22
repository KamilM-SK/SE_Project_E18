<?php

if (isset($_GET['article'])) {
	include_once( '../api/Database.php' );
	include_once( '../classes/Photo.php' );
	
	$photo = new Photo($conn);
	
	$photos = $photo->getAllPhotosForArticle($_GET['article'], $conn);
	
	$fileNames = array();
	
	while ($photoName = $photos->fetch_assoc()) {
		array_push($fileNames, $photoName['name']);
	}
	
	//Archive name
	$archive_file_name = './photos.zip';

	//Download Files path
	$file_path = '../uploads/';


	//zipFilesAndDownload( $fileNames, $archive_file_name, $file_path );

	//function zipFilesAndDownload( $fileNames, $archive_file_name, $file_path ) {
		//echo $file_path;die;

		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ( $zip->open( $archive_file_name, ZIPARCHIVE::CREATE ) !== TRUE ) {

			exit( "cannot open <$archive_file_name>\n" );
		}
		//add each files of $file_name array to archive
		foreach ( $fileNames as $files ) {
			$zip->addFile( $file_path . $files, $files );
			#echo $file_path.$files;
		}

		$zip->close();
		//then send the headers to force download the zip file
		header( "Content-type: application/zip" );
		header( "Content-Disposition: attachment; filename=$archive_file_name" );
		header( "Content-length: " . filesize( $archive_file_name ) );
		header( "Pragma: no-cache" );
		header( "Expires: 0" );
		flush();
		readfile( "$archive_file_name" );
		unlink($archive_file_name);
		exit;
//}

}



?>