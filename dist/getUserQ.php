<?php
// File that gets all the questions and display them based on the sorting
// And filtering requirement

include 'connect.php';

// The return values
$error = '';
$categories = array();
$qTimes = array();
$qId = array();
$qTitles = array();
$qContent = array();
$allQ = array();

$user_id = $_SESSION['user_id'];

// Getting all the qs where the user id is the same
$sql_return = "SELECT
                    q_id, q_subject, q_content, q_date, q_by, q_cat,
                    publish_status
                FROM
                    questions
                WHERE q_by = $user_id
                ORDER BY
                    q_id DESC
              ";

$result_return = mysqli_query( $_SESSION['link'], $sql_return );

// If the result is null
if( !$result_return ) {
  $error = $error . 'Fail to load all the posts.';
}
else { // Get all the questions into the array
  while( $row = mysqli_fetch_assoc( $result_return ) ){
    array_push( $qTimes, $row['q_date'] );
    array_push( $qId, $row['q_id']);
    array_push( $qTitles, $row['q_subject'] );
    array_push( $qContent, $row['q_content'] );
    array_push($categories, $_SESSION["categories"][intval($row['q_cat']) - 1]);
  }
}

// Then we put all the information into the return array
$allQ['qTimes'] = $qTimes;
$allQ['qId'] = $qId;
$allQ['qTitles'] = $qTitles;
$allQ['qContent'] = $qContent;
$allQ['category'] = $categories;
$allQ['error'] = $error;

echo json_encode($allQ);

?>
