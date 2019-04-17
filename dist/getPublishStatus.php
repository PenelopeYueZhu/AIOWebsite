<?php
/**
 * A php file that gets the detail of the question with the choice of the
 * users
 */
include 'connect.php';

// Get the id of the question
$q_id = $_GET['id'];
$publish_status;

// If we did not get the right id, then we won't be able to get the
// Published status of a specific question
if( !$q_id )
  $question_details["error"] =
    'Problem loading the question, please try again later.';
else { // Get all the information about that question when we do have a question
  $sql = "SELECT
              publish_status
          FROM
              questions
          WHERE q_id = $q_id
         ";

	// Query
  $result_question = mysqli_query( $_SESSION['link'], $sql );

	// If we do not have a result, return an error 
  if( !$result_question )
    $question_details["error"] =
      'Problem loading the question, please try again later.';
  else { // Return the publish
    $row_question = mysqli_fetch_assoc( $result_question );
    echo json_encode( $row_question['publish_status'] );
  }
}
