<?php
/**
 * php file to publish a question
 */

include 'connect.php';

$q_id = $_GET['id'];
$new_status = 0;

// Get the publish status from the databse
$sql_get = "SELECT
                publish_status
            FROM
                questions
            WHERE
                q_id = $q_id
           ";
$result_get = mysqli_query( $_SESSION['link'], $sql_get );

if( !$result_get ){
  echo 'Error getting the question\' publish status.';
}
else {
  $row_get = mysqli_fetch_assoc( $result_get );
  $old_status = $row_get['publish_status'];
  $new_status = ($old_status == '0') ? 1 : 0;
}

// Set the new publish status
$sql_update = "UPDATE
                   questions
               SET
                  publish_status = $new_status
               WHERE
                  q_id = $q_id
              ";

$result_update = mysqli_query( $_SESSION['link'], $sql_update );

if( !$result_update ){
  echo 'Error publishing the question.';
}
else {
  // Return to the previous page
  header("Location: {$_SERVER['HTTP_REFERER']}");
  exit;
}

 ?>
