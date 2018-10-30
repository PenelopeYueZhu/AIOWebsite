<?php
/* A php file that gets the detail of the question with the choice of the
 * users
 */
include 'connect.php';

// Get the id of the question
$q_id = $_GET['id'];
$publish_status;

if( !$q_id )
  $question_details["error"] =
    'Problem loading the question, please try again later.';
else { // Get all the information about that question
  $sql = "SELECT
              publish_status
          FROM
              questions
          WHERE q_id = $q_id
         ";

  $result_question = mysqli_query( $_SESSION['link'], $sql );

  if( !$result_question )
    $question_details["error"] =
      'Problem loading the question, please try again later.';
  else { // Return the publish
    $row_question = mysqli_fetch_assoc( $result_question );
    echo json_encode( $row_question['publish_status'] );
  }
}
