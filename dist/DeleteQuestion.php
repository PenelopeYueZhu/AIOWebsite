<?php
/**
 * File that allows admin to delete a question
 * Only allowed if you are logged in as the office
 */

include 'connect.php';

// First get the question id that we are deleting
$qId = $_GET['id'];

// Then we delete the replies and questions
$resultDelete = deleteReplies($qId) && deleteQuestion($qId);
if( !$resultDelete ) {
	echo 'The question has been deleted.';
	echo '<a href="index.html">Home</a>';
}

/**
 * Function that first deletes the replies of the question
 *
 * @param qId - the id of the question whose reply we are deleting
 * @return 1 - if the deletion is not successful
 *				 0 - if the deletion is successful
 */
function deleteReplies( $qId ){
	$replySql = "DELETE FROM replies WHERE reply_q_id = $qId";

	$resultDelete = mysqli_query( $_SESSION['link'], $replySql );

	// When we don't get a successful result from query
	// Then we record an error
	if( !$resultDelete ) {
		echo mysqli_error( $_SESSION['link']);
		return 1;
	}
	else { // Otherwise we have successfully deleted the question
		return 0;
	}
}

/**
 * Function that will delete the question itself
 *
 * @param qId - the id of the question whose reply we are deleting
 * @return 1 - if the deletion is not successful
 *         0 - if the deletion is successful
 */
function deleteQuestion($qId){
	$questionSql = "DELETE FROM questions WHERE q_id = $qId";

	$resultDelete = mysqli_query( $_SESSION['link'], $questionSql );

	// When we don't get a successful result from query
	// Then we record an error
	if( !$resultDelete ) {
		echo mysqli_error( $_SESSION['link']);
		return 1;
	}
	else { // Otherwise we have successfully deleted the question
		return 0;
	}
}
?>
