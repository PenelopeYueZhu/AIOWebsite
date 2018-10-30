<?php
/* A php file that gets the detail of the question with the choice of the
 * users
 */
include 'connect.php';

// Get the id of the question
$q_id = $_GET['id'];
$question_details; // The array for question details
$title; $content; $numReplies = 0; $replyContent = array();
$replyTime = array();
$error;
$publish_status;

if( !$q_id )
  $question_details["error"] =
    'Problem loading the question, please try again later.';
else { // Get all the information about that question
  $sql = "SELECT
              q_subject,
              q_date,
              q_cat,
              q_by,
              q_content,
              publish_status
          FROM
              questions
          WHERE q_id = $q_id
         ";

  $result_question = mysqli_query( $_SESSION['link'], $sql );
  if( !$result_question )
    $question_details["error"] =
      'Problem loading the question, please try again later.';
  else { // Display the question
    $row_question = mysqli_fetch_assoc( $result_question );
    $title = $row_question['q_subject'];
    $content = $row_question['q_content'];
    $publish_status = $row_question['publish_status'];
  }
}

// Get the replies, if there are any
$sql_reply = "SELECT
                  reply_id,
                  DATE(reply_date),
                  reply_by,
                  reply_content
               FROM
                   replies
               WHERE reply_q_id = $q_id
             ";
$result_reply = mysqli_query( $_SESSION['link'], $sql_reply );
if( !$result_reply )
  $question_details["error"] =
    'Problem loading the question, please try again later.';
// When there is a reply
else if( mysqli_num_rows( $result_reply ) > 0 ) {
  // Store all the replies in an array
  while( $row_reply = mysqli_fetch_assoc( $result_reply ) ){
    array_push( $replyTime, $row_reply['DATE(reply_date)']);
    array_push( $replyContent, $row_reply['reply_content']);
    $numReplies++;
  }
}

// Encode all the answers and send it to
$question_details["title"] = $title;
$question_details["content"] = $content;
$question_details["numReplies"] = $numReplies;
$question_details["replyTime"] = $replyTime;
$question_details["replies"] = $replyContent;
$question_details["publish_status"] = $publish_status;

echo json_encode( $question_details );

?>
