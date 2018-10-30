<?php
// File that gets all the questions and display them based on the sorting
// And filtering requirement

include 'connect.php';

// First function that displays the

// The return values
$error = '';
$qTimes = array();
$qId = array();
$qTitles = array();
$allQ = array();

// only used for admins
$privateQTimes = array();
$privateQId = array();
$privateQTitle = array();

// Only for admins
if( isset($_SESSION['user_permission'] ) &&
     $_SESSION['user_permission'] < 2 ) {

  $sql_private = getPrivateQuestions();
  $result_private =  mysqli_query( $_SESSION['link'], $sql_private );

  // Get all the entries into the array
  while( $row_private = mysqli_fetch_assoc( $result_private) ) {
    array_push( $privateQTimes, $row_private['q_date'] );
    array_push( $privateQId, $row_private['q_id']);
    array_push( $privateQTitle, $row_private['q_subject'] );
  }

  // Push them into allQ for return
  $allQ['privateQTimes'] = $privateQTimes;
  $allQ['privateQId'] = $privateQId;
  $allQ['privateQTitle'] = $privateQTitle;
}

// Default values for the form
$sorting_option = "qNTO";
$filter_option = 0;
// When we have applied the sorting and filter options
if( isset( $_POST['sort_by'] ) ) {
  $sorting_option = $_POST['sort_by'];
  $filter_option = $_POST['filter_by'];
}

$sql_allQ = getPublishedQuestions( $sorting_option, $filter_option );

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
  }
}

// Then we put all the information into the return array
$allQ['qTimes'] = $qTimes;
$allQ['qId'] = $qId;
$allQ['qTitles'] = $qTitles;
$allQ['error'] = $error;

echo json_encode($allQ);

/* Function to grab questions based on filter and sorting options */
function getPublishedQuestions( $sort_by, $filter_by ) {
  $sql_return = null;
  if( $filter_by == 0 ) {
    switch( $sort_by ) {
      case "qNTO": // Newest first
        $sql_return = "SELECT
                            q_id, q_subject, q_content, q_date, q_by,
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
        break;
      case "qOTN": // Oldest first
        $sql_return = "SELECT
                            q_id, q_subject, q_content, q_date, q_by,
                            user_id, user_name, publish_status
                        FROM
                            questions
                        LEFT JOIN
                            users
                        ON questions.q_by = users.user_id
                        WHERE publish_status = 1

                        ORDER BY
                            q_id ASC
                      ";
        break;
      default: // Default is newest first:
        $sql_return = "SELECT
                            q_id, q_subject, q_content, q_date, q_by,
                            user_id, user_name, publish_status
                        FROM
                            questions
                        LEFT JOIN
                            users
                        ON questions.q_by = users.user_id
                        WHERE publish_status = 1

                        ORDER BY
                            q_id DESC
                      ";
        break;
    }
  } else {
    // Now get all the qeustions based on the filter option
    switch( $sort_by ) {
      case "qNTO":
        $sql_return = "SELECT
                            q_id, q_subject, q_content, q_date, q_by,
                            user_id, user_name, publish_status
                        FROM
                            questions
                        LEFT JOIN
                            users
                        ON questions.q_by = users.user_id
                        WHERE q_cat = $filter_by AND publish_status = 1
                        ORDER BY
                           q_id DESC
                      ";
          break;
      case "qOTN":
        $sql_return = "SELECT
                           q_id, q_subject, q_content, q_date, q_by,
                           user_id, user_name, publish_status
                        FROM
                            questions
                        LEFT JOIN
                            users
                        ON questions.q_by = users.user_id
                        WHERE q_cat = $filter_by AND publish_status = 1
                        ORDER BY
                            q_id ASC
                       ";
        break;
    } // filter_by has a category
  }

  return $sql_return;
}

function getPrivateQuestions() {
  $sql = "SELECT
              q_id, q_subject, q_content, q_date, q_by,
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
