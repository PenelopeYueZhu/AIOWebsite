<?php
/**
 * script to push a reply to the database
 */

include 'connect.php';

// Get the id of the question we are replying to
$q_id = $_GET['id'];

// begin work!!
$query = "BEGIN WORK;";
$result_query = mysqli_query( $_SESSION['link'], $query );

// If we can't insert/ change value of the data, we error out
if( !$result_query ) echo 'Failed to load the database when trying to
                         display box.';
else {
	// Otherise we push the reply, the time it is posted and which question it
	// is for
  $sql_new_reply = "INSERT INTO
                        replies(reply_date, reply_q_id, reply_content)
                    VALUES(NOW(),
                           '" . $q_id . "',
                           '" . mysqli_real_escape_string( $_SESSION['link'] ,
                                                        $_POST['q_reply']) . "'
                          )
                   "; //'

	// Now query into the database
  $result_reply = mysqli_query( $_SESSION['link'], $sql_new_reply );

  // When there is an error that occured in the middle of insertion
  // Then we can't write it to database
  if( !$result_reply ) {
    echo 'Failed to load the database.';
    $query = "ROLLBACK;";
    $result_query = mysqli_query( $_SESSION['link'], $query );
  } else { // Otherwise we keep workingggg
    $query = "COMMIT;";
    $result_query = mysqli_query( $_SESSION['link'], $query );
    echo 'Your reply has been posted.';
    echo '<a href="index.html">Home</a>';
  }
}
 ?>
