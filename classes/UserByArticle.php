<?php

class UserByArticle {
	private $tableName = 'user_by_article';
	private $ID;
	private $user;
	private $article;
	private $workingType;
	private $db;

	function __construct( $db ) {
		$this->db = $db;

		if ( isset( $_GET[ 'article' ] ) && isset( $_GET[ 'user' ] ) ) {
			$this->setUserByArticleAsSuggestor();
		}
	}

	private
	function setUserByArticleAsSuggestor() {
		$this->user = $_GET[ 'user' ];
		$this->article = $_GET[ 'article' ];
		$this->workingType = 1;

		$stmt = $this->db->prepare( 'INSERT INTO user_by_article(user, article, working_type) VALUES(?, ?, ?)' );
		$stmt->bind_param( "iii", $this->user, $this->article, $this->workingType );

		if ( $stmt->execute() ) {
			header( 'location: suggestarticles.php?notifyaboutsuggestedarticle=true&user=' . $this->user );
		} else {
			header( 'location: suggestarticles.php?status=999' );
		}
	}

	public
	function fetchAllSuggestedArticles( $db ) {
		$this->db = $db;
		$sql = 'SELECT article.ID AS "article_ID", user.ID AS "user_ID", article.title, user.first_name, 
				user.last_name, article.description, article.section, article.page_number, user_by_article.ID AS "user_by_article" 
				FROM user_by_article JOIN user ON user_by_article.user = user.ID 
				JOIN article ON user_by_article.article = article.ID 
				JOIN working_type ON user_by_article.working_type = working_type.ID 
				WHERE article.status = 1 ORDER BY 9 ASC';
		return $this->db->query( $sql );
	}

	public
	function deleteUserFromSuggestedArticle( $ID, $db, $article, $user ) {
		$this->ID = $ID;
		$this->db = $db;
		$this->article = $article;
		$this->user = $user;

		$sql = 'DELETE FROM user_by_article WHERE ID = ' . $this->ID;

		if ( $this->db->query( $sql ) ) {
			header( 'location: decline.php?id=' . $this->article . '&user=' . $this->user );
		}
	}

	public
	function fetchAllCurrentArticlesGivenToJournalist( $ID, $db ) {
		$this->ID = $ID;
		$this->db = $db;

		$sql = 'SELECT
					article.ID,
					article.title,
					article.section,
					magazine.writing_deadline,
					article.page_number
				FROM
					user_by_article
				JOIN user ON user_by_article.user = user.ID
				JOIN article ON user_by_article.article = article.ID
				JOIN magazine ON article.magazine = magazine.ID
				WHERE
					user.ID = ' . $this->ID . ' AND article.status = 3 OR article.status = 7
				GROUP BY 1, 2, 3, 4, 5';

		return $this->db->query( $sql );
	}
	
	public
	function fetchFinalArticles( $ID, $db ) {
		$this->ID = $ID;
		$this->db = $db;

		$sql = 'SELECT
					article.ID,
					article.title,
					article.section,
					magazine.writing_deadline,
					article.page_number,
					article.magazine
				FROM
					user_by_article
				JOIN user ON user_by_article.user = user.ID
				JOIN article ON user_by_article.article = article.ID
				JOIN magazine ON article.magazine = magazine.ID
				WHERE
					user.ID = ' . $this->ID . ' AND article.status BETWEEN 4 AND 6
				GROUP BY 1, 2, 3, 4, 5';

		return $this->db->query( $sql );
	}

	public
	function getArticleAuthor( $article, $db ) {
		$this->article = $article;
		$this->db = $db;

		$sql = 'SELECT
					user.first_name,
					user.last_name
				FROM
					user_by_article
				JOIN user ON user_by_article.user = user.ID
				JOIN article ON user_by_article.article = article.ID
				WHERE
					user_by_article.article = ' . $this->article . ' AND
					working_type = 2';

		$result = $this->db->query( $sql );
		$result = $result->fetch_assoc();
		return $result[ 'first_name' ] . ' ' . $result[ 'last_name' ];
	}


	public
	function checkIfUserIsSpecificArticleJournalist( $user, $article, $db ) {
		$this->db = $db;
		$this->user = $user;
		$this->article = $article;

		$sql = 'SELECT ID FROM user_by_article WHERE user = ' . $this->user . ' AND article = ' . $this->article . ' AND working_type = 2 ';
		$result = $this->db->query( $sql );

		if ( $result->num_rows == 1 ) {
			return 1;
		} else {
			return 0;
		}
	}

	public
	function getArticleDesigner( $article, $db ) {
		$this->article = $article;
		$this->db = $db;

		$sql = 'SELECT
					user.first_name,
					user.last_name
				FROM
					user_by_article
				JOIN user ON user.ID = user_by_article.user
				JOIN article ON article.ID = user_by_article.article
				WHERE
					user_by_article.article = ' . $this->article . ' AND user_by_article.working_type = 3';

		$result = $this->db->query( $sql );
		$result = $result->fetch_assoc();
		return $result[ 'first_name' ] . ' ' . $result[ 'last_name' ];
	}


	public
	function fetchAllCurrentArticlesGivenToDesigner( $ID, $db ) {
		$this->ID = $ID;
		$this->db = $db;

		$sql = 'SELECT
					article.ID,
					article.title,
					article.section,
					magazine.design_deadline,
					article.page_number
				FROM
					user_by_article
				JOIN user ON user_by_article.user = user.ID
				JOIN article ON user_by_article.article = article.ID
				JOIN magazine ON article.magazine = magazine.ID
				WHERE
					user.ID = ' . $this->ID . ' AND article.status = 5
				GROUP BY 1, 2, 3, 4, 5';

		return $this->db->query( $sql );
	}

	public
	function cancelButtonArticle( $user, $ID, $db ) {
		$this->db = $db;
		$this->ID = $ID;

		$sql = 'SELECT user_by_article.cancel_date FROM user_by_article
		WHERE user_by_article.user = ' . $user . ' AND user_by_article.article = ' . $this->ID . ' AND user_by_article.working_type = (SELECT user_type FROM user
		WHERE user.ID = ' . $user . ')';


		$cancelTime = $this->db->query( $sql );
		$cancelTime = $cancelTime->fetch_assoc();



		$sql2 = 'SELECT magazine.writing_deadline FROM magazine
		WHERE magazine.ID = (SELECT article.magazine FROM article
		WHERE article.ID = ' . $this->ID . ')';


		$magazineDeadline = $this->db->query( $sql2 );
		$magazineDeadline = $magazineDeadline->fetch_assoc();

		if ( ( time() - strtotime( $cancelTime[ 'cancel_date' ] ) <= 172800 ) && ( strtotime( $magazineDeadline[ 'writing_deadline' ] ) - time() > 172800 ) ) {
			return 1;
		} else {
			return 0;
		}
	}


	public
	function cancelButtonDesign( $user, $ID, $db ) {
		$this->db = $db;
		$this->ID = $ID;


		$sql = 'SELECT user_by_article.cancel_date FROM user_by_article
		WHERE user_by_article.user = ' . $user . ' AND user_by_article.article = ' . $this->ID . ' AND user_by_article.working_type = (SELECT user_type FROM user
		WHERE user.ID = ' . $user . ')';


		$cancelTime = $this->db->query( $sql );
		$cancelTime = $cancelTime->fetch_assoc();



		$sql = 'SELECT magazine.design_deadline FROM magazine
		WHERE magazine.ID = (SELECT article.magazine FROM article
		WHERE article.ID = ' . $this->ID . ')';


		$magazineDeadline = $this->db->query( $sql );
		$magazineDeadline = $magazineDeadline->fetch_assoc();

		if ( ( time() - strtotime( $cancelTime[ 'cancel_date' ] ) <= 172800 ) && ( strtotime( $magazineDeadline[ 'design_deadline' ] ) - time() > 172800 ) ) {
			return 1;
		} else {
			return 0;
		}
	}


}

?>