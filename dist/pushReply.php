<?php
/* script to push a reply to the database
 */

include 'connect.php';

$q_id = $_GET['id'];
$query = "BEGIN WORK;";
$result_query = mysqli_query( $_SESSION['link'], $query );

if( !$result_query ) echo 'Failed to load the database when trying to
                         display box.';
else {
  $sql_new_reply = "INSERT INTO
                        replies(reply_date, reply_q_id, reply_by,
                              reply_content)
                    VALUES(NOW(),
                           '" . $q_id . "',
                           '" . $_SESSION['user_id'] . "',
                           '" . mysqli_real_escape_string( $_SESSION['link'] ,
                                                           $_POST['q_reply']) . "'
                          )
                   ";
  $result_reply = mysqli_query( $_SESSION['link'], $sql_new_reply );
  // When there is an error that occured in the middle of insertion
  // Then we can't write it to database
  if( !$result_reply ) {
    echo 'Failed to load the database.';
    $query = "ROLLBACK;";
    $result_query = mysqli_query( $_SESSION['link'], $query );
  } else {
    $query = "COMMIT;";
    $result_query = mysqli_query( $_SESSION['link'], $query );
    echo 'Your reply has been posted.';
    echo '<a href="index.php">Home</a>';
  }
}
 ?>
