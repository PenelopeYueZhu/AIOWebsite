<?php
/**
 * A php file that gets the detail of the question clicked on by the user
 */
include 'connect.php';
include 'GetPublishStatusFunc.php';

// Get the id of the question
$q_id = $_GET['id'];

$question_details; // The array for question details
// Other bunch of variables storing the information of the question
$title; $category; $content; $numReplies = 0;
$replyContent = array();
$replyTime = array();
$error;
$publish_status;

// First check if we have the permission to browse all the questions
// If not, then we need to check the publish_status of the question
if( !isset($_SESSION['signed_in'] ) || $_SESSION['signed_in'] != 1 ) {

	// If the question if not published
	if( getPublishStatus( $q_id ) != 1 ){
		// Then return null to indicate an error
		echo json_encode( NULL );
		exit( 0 );
	}
}
// Otherwise we can go ahead and fetch the question
// The query string
$sql = "SELECT
						q_subject,
						q_date,
						q_cat,
						q_content,
						publish_status
				FROM
						questions
				WHERE q_id = $q_id
			 ";

// Try to query into the datbase
$result_question = mysqli_query( $_SESSION['link'], $sql );

// If we did not get a result, then the query has failed
if( !$result_question ){
	echo json_encode( NULL );
	exit( 1 );
}
else { // Otherwise we can display the question
	// Store all the information into the arrays
	$row_question = mysqli_fetch_assoc( $result_question );
	$title = $row_question['q_subject'];
	$category = intval( $row_question['q_cat'] ) - 1;
	$content = $row_question['q_content'];
	$publish_status = $row_question['publish_status'];
}

// Get the replies, if there are any
$sql_reply = "SELECT
									reply_id,
									DATE(reply_date),
									reply_content
							 FROM
									 replies
							 WHERE reply_q_id = $q_id
						 ";
// Try to query into the database and fetch all the replies
$result_reply = mysqli_query( $_SESSION['link'], $sql_reply );

// If we do not have a result, then we didn't connect to the database
if( !$result_reply ){
	echo json_encode( NULL );
	exit( 1 );
}
// When there is a reply (there could be no reply yet)
else if( mysqli_num_rows( $result_reply ) > 0 ) {
	// Store all the replies in an array
	while( $row_reply = mysqli_fetch_assoc( $result_reply ) ){
		array_push( $replyTime, $row_reply['DATE(reply_date)']);
		array_push( $replyContent, $row_reply['reply_content']);
		$numReplies++;
	}
}

// Encode all the answers and send it to the caller
$question_details["category"] = $_SESSION["categories"][$category];
$question_details["title"] = $title;
$question_details["content"] = $content;
$question_details["numReplies"] = $numReplies;
$question_details["replyTime"] = $replyTime;
$question_details["replies"] = $replyContent;
$question_details["publish_status"] = $publish_status;

echo json_encode( $question_details );

?>
