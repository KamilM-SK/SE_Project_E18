<?php

if ( !isset( $_GET[ 'id' ] ) || $_GET[ 'id' ] == '' ) {
	header( 'location: ../../admin/suggestarticles.php' );
}



include_once( '../Database.php' );
include_once( '../../classes/Article.php' );
include_once( '../../classes/UserByArticle.php' );
include_once( '../../classes/Notification.php' );


$article = new Article( $conn );
$articleExists = $article->checkIfArticleExists( $_GET[ 'id' ], $conn );

if ( $articleExists == 1 ) {
	$userByArticle = new UserByArticle( $conn );
	if ( isset( $_GET[ 'token' ] ) ) {
		$removeUser = $userByArticle->deleteUserFromSuggestedArticle( $_GET[ 'token' ], $conn, $_GET[ 'id' ], $_GET[ 'user' ] );
	}

	if ( isset( $_GET[ 'id' ] ) && isset( $_GET[ 'user' ] ) ) {
		$notification = new Notification( $conn );
		$sendNotification = $notification->sendNotificationToMemberAboutDeclinedArticle( $_GET[ 'id' ], $_GET[ 'user' ], $conn );
	}

	if ( isset( $_GET[ 'id' ] ) && !isset( $_GET[ 'token' ] ) ) {
		$articleDecline = $article->declineSuggestedArticle( $_GET[ 'id' ], $conn );
	}
} else {
	header( 'location: ../../admin/suggestarticles.php?article=404' );
}

?>