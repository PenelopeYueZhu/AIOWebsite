<?php
// File that gets all the questions and display them based on the sorting
// And filtering requirement

include 'connect.php';

// arrays to store different kinds of information we need
$error = '';
$categories = array();
$qTimes = array();
$qId = array();
$qTitles = array();
$qContent = array();
$allQ = array();

// only used for admins
$privateQTimes = array();
$privateQId = array();
$privateQTitle = array();
$privateCat = array();
$privateContent = array();

// Only for admins
if( isset($_SESSION['user_permission'] ) &&
     $_SESSION['user_permission'] < 2 ) {

  // Get private quesitons that are not published yet
  $sql_private = getPrivateQuestions( );
  $result_private =  mysqli_query( $_SESSION['link'], $sql_private );

  // Get all the entries into the array
  while( $row_private = mysqli_fetch_assoc( $result_private) ) {
    array_push( $privateQTimes, $row_private['q_date'] );
    array_push( $privateQId, $row_private['q_id']);
    array_push( $privateQTitle, $row_private['q_subject'] );
    array_push( $privateContent, $row_private['q_content'] );
    array_push($privateCat,
               $_SESSION["categories"][intval($row_private['q_cat']) - 1]);
  }

  // Push them into allQ for return
  $allQ['privateQTimes'] = $privateQTimes;
  $allQ['privateQId'] = $privateQId;
  $allQ['privateQTitle'] = $privateQTitle;
  $allQ['privateContent'] = $privateContent;
  $allQ['privateCat'] = $privateCat;
}

// Get the all published questions for everyone
$sql_allQ = getPublishedQuestions(  );

$result_allQ = mysqli_query( $_SESSION['link'], $sql_allQ );

// If the result is null
if( !$result_allQ ) {
  $error = $error . 'Fail to load all the posts.';
}
else { // Get all the questions into the array
  while( $row = mysqli_fetch_assoc( $result_allQ ) ){
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

/* Function to grab questions based on filter and sorting options */
function getPublishedQuestions(  ) {
  $sql_return = null;
  $sql_return = "SELECT
                      q_id, q_subject, q_content, q_date, q_by, q_cat,
                      user_id, user_name, publish_status
                  FROM
                      questions
                  LEFT JOIN
                      users
                  ON questions.q_by = users.user_id
                  WHERE questions.publish_status = 1
                  ORDER BY
                      q_id DESC
                ";

  return $sql_return;
}

function getPrivateQuestions(  ) {
  $sql = null;
  $sql = "SELECT
              q_id, q_subject, q_content, q_date, q_by, q_cat,
              user_id, user_name, publish_status
          FROM
              questions
          LEFT JOIN
              users
          ON questions.q_by = users.user_id
          WHERE questions.publish_status = 0
          ORDER BY
              q_id DESC
        ";
  return $sql;
}
?>
