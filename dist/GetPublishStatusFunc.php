<?php
/**
 * A php file defined that actual function that
 * returns if a specific question has been published
 */

/**
 * Function that gets the publish_status of a specified question
 * @param id the id of the question
 * @return publishedStatus the publish_status of the question
 */
function getPublishStatus ( $id ){
	// First get the sql information
	$sql = "SELECT
              publish_status
          FROM
              questions
          WHERE q_id = $id
         ";

	// Query
  $result_question = mysqli_query( $_SESSION['link'], $sql );

	// If we do not have a result, return an error
  if( !$result_question )
    $question_details["error"] =
      'Problem loading the question, please try again later.';
  else { // Return the publish
    $row_question = mysqli_fetch_assoc( $result_question );
    return $row_question['publish_status'];
  }
}
