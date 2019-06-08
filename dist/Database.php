<?php
/**
 * Connection script that will establish a connection to the database with
 * privilege of only reading.
 * It is not a persistent link, so you have to include this in every
 * php file that needs access to the database
 */

/**
 * The conenction class
 * Creates and uses the conenction to the database
 */
class Database {

	/**
	 * server connection param
	 * @const string
	 */
	const SERVER_PARAM = 'mysql:host=' .
											 'aioforumdb.cvcwqiayiang.us-east-2.rds.amazonaws.com;' .
											 'dbname=' .
											 'ebdb';

	/**
	 * Read only privilege user's user name
	 * @const string
	 */
	const SELECT_USER_NAME = 'RO';

	/**
	 * Read only privilege user's password
	 * @const string
	 */
	const SELECT_USER_PW = 'anyguest121!';

	/**
	 * Post a question user's username
	 * @const string
	 */
	const INSERT_USER_NAME = 'W';

	/**
	 * Post a question user's pw
	 * @const string
	 */
	const INSERT_USER_PW = 'postguest131!';

	/**
	 * Admin username
	 * @const string
	 */
	const ADMIN_NAME = 'aioadmin';

	/**
	 * Admin password
	 * @const string
	 */
	const ADMIN_PW = 'Zy100,.,.';

	/**
	 * Three constants to indicate which connection it is
	 * @const int
	 */
	const ADMIN_CONNECTION = 0;
	const RO_CONNECTION = 1;
	const W_CONNECTION = 2;

	/**
	 * Longest length of the content of a question or reply, and its subject
	 * @const int
	 */
	const TITLE_LEN = 100;
	const CONTENT_LEN = 5000;

	/**
	 * The connection object in this class
	 * @var PDO
	 */
	private $dbConnection;

	/**
	 * The number to indicate the type of connection
	 * @var int
	 */
	private $connectionType = -1;

	/**
	 * The variable that stores the categories for easy access
	 * @var array
	 */
	private $categories;

	/**
	 * Create a new read only connection
	 */
	public function initROConnection(){
		try{
			// Connecting to the database with only select permission
			$this->dbConnection = new PDO( self::SERVER_PARAM, self::SELECT_USER_NAME,
			 															self::SELECT_USER_PW );
			$this->connectionType = self::RO_CONNECTION;

			// Set the error responding attribute
			$this->dbConnection->setAttribute(PDO::ATTR_ERRMODE,
																				PDO::ERRMODE_EXCEPTION);

		} catch( PDOException $e ) {
			print 'Error: ' . $e->getMessage() . '<br/>';
		}
	}

	/**
	 * Create a new write connection
	 */
	public function initWConnection(){
		try{
			// Connecting to the database using user with insert permission
			$this->dbConnection = new PDO( self::SERVER_PARAM, self::INSERT_USER_NAME,
			 															self::INSERT_USER_PW );
			$this->connectionType = self::W_CONNECTION;

			// Set the error responding attribute
			$this->dbConnection->setAttribute(PDO::ATTR_ERRMODE,
																				PDO::ERRMODE_EXCEPTION);
		} catch( PDOException $e ) {
			print 'Error: ' . $e->getMessage() . '<br/>';
		}
	}

	/**
	 * Create a new admin connection
	 */
	public function initAdminConnection(){
		try{
			// Conenct to the database with admin access
			$this->dbConnection = new PDO( self::SERVER_PARAM, self::ADMIN_NAME,
			 															self::ADMIN_PW );
			$this->connectionType = self::ADMIN_CONNECTION; // Admin connection

			// Set the error responding attribute
			$this->dbConnection->setAttribute(PDO::ATTR_ERRMODE,
																				PDO::ERRMODE_EXCEPTION);
		} catch( PDOException $e ) {
			print 'Error: ' . $e->getMessage() . '<br/>';
		}
	}

	/**
	 * End the connection
	 */
	public function endConnection(){
		$this->dbConnection = null; // Set the connection to null
		$this->connectionType = null; // Set the connection type to null
	}

	/**
	 * Add a row of question in to the database
	 * @param string $subject The subject of user's question
	 * @param string $content The content of user's question
	 *
	 * @return true if everything got executed
	 *				 false otherwise
	 */
	public function postQuestion( $subject, $content ){

		// If the user does not have w or admin connectoin, refuse this action
		if( $this->connectionType == self::RO_CONNECTION ) {
			echo "You do not have permission to post a question.";
			return false;
		}

		try{
			$this->dbConnection->beginTransaction();

			// Prepare the statement to insert
			$queryStmt = $this->dbConnection->prepare(
				'INSERT INTO
		         questions(q_subject, q_date, q_cat,q_content, publish_status)
		    VALUES( :subject, NOW(), 1; :content, 0 );');

			// Bind the variable from parameter to the statement
			$queryStmt->bindParam( ':subject', $subject,
														PDO::PARAM_STR, self::TITLE_LEN );
			$queryStmt->bindParam( ':content', $content,
														PDO::PARAM_STR, self::CONTENT_LEN );

			// Now execute and commit
			if( !$queryStmt->execute() ) {
				// If the function returns false, then we know execution failed
				$this->dbConnection->rollback();
				echo "Failed to post the question.";

				return fasle;
			}
			$this->dbConnection->commit();

			return true;

		} catch( Exception $e ) {
			// If there is an error of any kind, we first rollback any changes made
			$this->dbConnection->rollback();
			// Now pring out the error
			echo "Failed in commit your question: " . $e->getMessage();
		}

		return false;
	}

	/**
	 * Get the details of a question
	 *
	 * @param int $id The ID of the question we are trying to get
	 * @param array $resultArray the array storing all the info about the question
	 */
	public function getQuestion( $id, &$resultArray ) {
		// First get the status of the question we are trying to get
		$status;
		$this->getPublishStatus( $id, $status );

		// If the status is not yet published, then check if the user is admin
		if( $status == 0 ) {
			// If not an admin, refuse the access
			if( $this->connectionType != self::ADMIN_CONNECTION ) {
				echo "You do not have permission to view this question.";
				return false;
			}
		}

		// Otherwise, we now prepare the statement to fetch the question
		$queryStmt = $this->dbConnection->prepare('SELECT
																										questions.q_subject,
																										questions.q_date,
																										questions.q_cat,
																										questions.q_content,
																										questions.publish_status,
																										replies.reply_content
																								FROM questions, replies
																								WHERE q_id = :id
																								AND reply_q_id = :id');

		// Bind the question's id
		$queryStmt->bindParam( ':id', $id, PDO::PARAM_INT );

		// Execute the query
		if( !$queryStmt->execute() ) {
			// If the function returns false, then we know execution failed
			echo "Failed to fetch the details of the question";

			return fasle;
		}

		// Otherwise, we know we got the detais.
		// If we have not gotten all the categories, get it
		if( empty($this->categories) ) {
			$this->categories	= array();
			$this->categories['catsId'] = array();
			$this->categories['categories'] = array();

			$this->getCategories( -1, $this->categories );
		}

		// Then we fetch the values row by row and put them into the param array
		while( $row = $queryStmt->fetch() ) {
			// Then enter the data into each array column by column
			array_push( $resultArray['subject'], $row['q_subject'] );
			array_push( $resultArray['content'], $row['q_content']);
			array_push( $resultArray['cat'],
		 							$this->categories['categories'][intval($row['q_cat']) - 1]);
			array_push( $resultArray['publishStatus'], $row['publish_status'] );
			array_push( $resultArray['reply'], $row['reply_content']);
		}

		return true;
	}

	/**
	 * Add a row of reply to the question
	 * @param int $id The id the of the question we are replying to
	 * @param string $content The actual text body of the reply
	 * @return true if successful, false if anything got interrupted
	 */
	public function postReply( $id, $content ) {

		// If the user does not have admin connection, refuse this action
		if( $this->connectionType != self::ADMIN_CONNECTION ) {
			echo "You do not have permission to post a reply.";
			return false;
		}

		try{
			$this->dbConnection->beginTransaction();

			// Prepare the statement to insert
			$queryStmt = $this->dbConnection->prepare(
				'INSERT INTO replies(reply_date, reply_q_id, reply_content)
				VALUES(NOW(), :id, :content );' );

			// Bind the variable from parameter to the statement
			$queryStmt->bindParam( ':id', $id, PDO::PARAM_INT );
			$queryStmt->bindParam( ':content', $content,
														PDO::PARAM_STR, self::CONTENT_LEN );

			// Now execute and commit
			if( !$queryStmt->execute() ) {
				// If the function returns false, then we know execution failed
				$this->dbConnection->rollback();
				echo "Failed to post the reply.";

				return fasle;
			}
			$this->dbConnection->commit();

			return true;

		} catch( Exception $e ) {
			// If there is an error of any kind, we first rollback any changes made
			$this->dbConnection->rollback();
			// Now pring out the error
			echo "Failed to commit your reply: " . $e->getMessage();
		}

		return false;
	}

	/**
	 * Delete a question. Only available for admin
	 * @param int $id The id the of the question we are deleting
	 * @return true if successful, false if anything got interrupted
	 */
	public function deleteQuestion( $id ) {
		// If the connection is not admin, refuse
		if( $this->connectionType != self::ADMIN_CONNECTION ) {
			echo "You do not have permission to delete questions.";
			return false;
		}

		try{
			$this->dbConnection->beginTransaction();

			// Prepare the statement to insert
			$queryStmt = $this->dbConnection->prepare(
				'DELETE FROM replies WHERE reply_q_id = :id;
				DELETE FROM questions WHERE q_id = :id;' ); // Then delete the question

			// Bind the variable from parameter to the statement
			$queryStmt->bindParam( ':id', $id, PDO::PARAM_INT );

			// Now execute and commit
			if( !$queryStmt->execute() ) {
				// If the function returns false, then we know execution failed
				$this->dbConnection->rollback();
				echo "Failed to delete the question.";
				return fasle;
			}
			$this->dbConnection->commit();

			return true;

		} catch( Exception $e ) {
			// If there is an error of any kind, we first rollback any changes made
			$this->dbConnection->rollback();
			// Now pring out the error
			echo "Failed to commit the deletion: " . $e->getMessage();
		}

		return false;
	}

	/**
	 * Get all the questions from the database.
	 * If the user has admin access then get all the questions
	 * Otherwise only get the public questions (published ones)
	 *
	 * @param array $resultArray Where we store the result we get from the db
	 * @return true if successfully grabbed information, false otherwise
	 */
	public function getAllQuestions( &$resultArray ){
		// If the user do not have RO permission, deny
		if( $this->connectionType != self::RO_CONNECTION
				&& $this->connectionType != self::ADMIN_CONNECTION ){
			echo "You do not have permission to view all the questions.";
			return false;
		}

		// Prepare the statement to insert
		$queryStmt = $this->dbConnection->prepare(
			'SELECT
					q_id, q_subject, q_content, q_date,q_cat, publish_status
			FROM questions
		  WHERE questions.publish_status >= :publishStatus
			ORDER BY q_id DESC;' );

		// If the user is admin access, then we get all the questions
		$publishStatus = 1;
		if( $this->connectionType == self::ADMIN_CONNECTION ) {
			// That is, publish status can be 0 and 1
			$publishStatus = 0;
		} else {
			// Otherwise we only get the RO access from normal user, then only
			// published questions
			$publishStatus = 1;
		}
		$queryStmt->bindParam( ':publishStatus', $publishStatus, PDO::PARAM_INT );

		// Now execute
		if( !($queryStmt->execute()) ) {
			// If the function returns false, then we know execution failed
			$this->dbConnection->rollback();
			echo "Failed to fetched all the questions.";

			return fasle;
		}

		// Then we fetch the values row by row and put them into the param array
		while( $row = $queryStmt->fetch() ) {
			// Then enter the data into each array column by column
			array_push( $resultArray['qTimes'], $row['q_date'] );
			array_push( $resultArray['qId'], $row['q_id']);
			array_push( $resultArray['qTitles'], $row['q_subject'] );
			array_push( $resultArray['qContent'], $row['q_content'] ); /*************************************************** category still using old conectoin ********/
			array_push( $resultArray['qCats'],
								  $_SESSION["categories"][intval($row['q_cat']) - 1]);
			array_push( $resultArray['qPublished'], $row['publish_status'] );
		}

		return true;
	}

	/**
	 * Grab categories from the database
	 * @param int @id the id of the question whose category we are getting
	 * 								however, if the id is -1, that means we are getting all
	 * 								the categories
	 * @param array $resultArray The place to store the id and the name of the
	 * 													category(ies)
	 */
	public function getCategories( $id, &$resultArray ){
		// If the user do not have RO permission, deny
		if( $this->connectionType != self::RO_CONNECTION
				&& $this->connectionType != self::ADMIN_CONNECTION ){
			echo "You do not have the permission to get all the categories.";
			return false;
		}

		// Prepare the statement to insert
		$queryStmt;
		if( $id == -1 ) { // When we have -1 as id, we are getting all the cats
			$queryStmt = $this->dbConnection->prepare( 'SELECT cat_name, cat_id
		   																						FROM categories;');
		} else { // else we have a specific question whose category we want toget
			$queryStmt = $this->dbConnection->prepare( 'SELECT q_cat
		   																					FROM questions
																								WHERE q_id = :id;');
			// Bind the id variable to the statment
			$queryStmt->bindParam( ':id', $id, PDO::PARAM_INT );
		}

		// Now execute
		if( !($queryStmt->execute()) ) {
			// If the function returns false, then we know execution failed
			$this->dbConnection->rollback();
			echo "Failed to fetched the category(ies).";

			return fasle;
		}

		// Then we fetch the values row by row and put them into the param array
		while( $row = $queryStmt->fetch() ) {
			// Then enter the data into each array column by column
			array_push( $resultArray['catsId'], strval($row['cat_id']) );
			array_push( $resultArray['categories'], $row['cat_name']);
		}

		return true;
	}

	/**
	 * Set the category of a specific question
	 *
	 * @param int @qId ID of the question we are setting category on
	 * @param int @catId ID of the category
	 */
	public function setCategory( $qId, $catId ){

		// If the connection is not admin, refuse
		if( $this->connectionType != self::ADMIN_CONNECTION ) {
			echo "You do not have permission to delete questions.";
			return false;
		}

		try{
			$this->dbConnection->beginTransaction();

			// Prepare the statement to insert
			$queryStmt = $this->dbConnection->prepare( 'UPDATE questions
																									SET q_cat = :catId
																									WHERE q_id = :qId');

			// Bind the variable from parameter to the statement
			$queryStmt->bindParam( ':qId', $qId, PDO::PARAM_INT );
			$queryStmt->bindParam( ':catId', $catId, PDO::PARAM_INT );

			// Now execute and commit
			if( !$queryStmt->execute() ) {
				// If the function returns false, then we know execution failed
				$this->dbConnection->rollback();
				echo "Failed to set the category for this question.";
				return fasle;
			}
			$this->dbConnection->commit();

			return true;

		} catch( Exception $e ) {
			// If there is an error of any kind, we first rollback any changes made
			$this->dbConnection->rollback();
			// Now pring out the error
			echo "Failed to commit the new category: " . $e->getMessage();
		}

		return false;
	}

	/**
	 * Get the publish status of a specific question
	 * @param int @id ID of the question that we are getting the status on
	 * @param int @status Int to hold the status of the question
	 */
	public function getPublishStatus( $id, &$status ){

		// If the user do not have admin or RO permission, deny
		if( $this->connectionType != self::RO_CONNECTION
				&& $this->connectionType != self::ADMIN_CONNECTION ){
			echo "You do not have the permission to get the publish status.";
			return false;
		}

		// Prepare the statement to insert
		$queryStmt = $this->dbConnection->prepare( 'SELECT publish_status
																								FROM questions
																								WHERE q_id = :id');
		// Bind variable id
		$queryStmt->bindParam( ':id', $id, PDO::PARAM_INT );

		// Now execute
		if( !($queryStmt->execute()) ) {
			// If the function returns false, then we know execution failed
			$this->dbConnection->rollback();
			echo "Failed to fetched the status.";

			return fasle;
		}

		// Assign the status into the reference passed in
		$status = ($queryStmt->fetch())['publish_status'];

		return true;
	}

	/**
	 * Get the publish status of a specific question
	 * @param int @id ID of the question that we are getting the status on
	 * @param int @status Int to hold the status of the question
	 */
	public function setPublishStatus( $id, $status ){

		// If the user do not have admin permission, deny
		if( $this->connectionType != self::ADMIN_CONNECTION ){
			echo "You do not have the permission to get the publish status.";
			return false;
		}

		try{
			$this->dbConnection->beginTransaction();

			// Prepare the statement to insert
			$queryStmt = $this->dbConnection->prepare( 'UPDATE questions
																									SET publish_status = :status
																									WHERE q_id = :id');
			// Bind variables
			$queryStmt->bindParam( ':id', $id, PDO::PARAM_INT );
			$queryStmt->bindParam( ':status', $status, PDO::PARAM_INT );

			// Now execute
			if( !($queryStmt->execute()) ) {
				// If the function returns false, then we know execution failed
				$this->dbConnection->rollback();
				echo "Failed to set the status.";

				return fasle;
			}

			// Commit the change to publish status
			$this->dbConnection->commit();

			return true;

		} catch( Exception $e ) {
			// If there is an error of any kind, we first rollback any changes made
			$this->dbConnection->rollback();
			// Now pring out the error
			echo "Failed to commit the new publish status: " . $e->getMessage();
		}

		return false;
	}
}
