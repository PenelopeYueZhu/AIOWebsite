<?php
/**
 * Push the question into the database when a user submits a new question
 * Recording the subject, content, data of the question
 */
include 'connect.php';

$query = "BEGIN WORK;";

// Try to begin work
$result = mysqli_query( $_SESSION['link'], $query );
// If the connection has failed
if( !$result )
  echo 'An error has occured when trying to load the database.?Begin work';
// else we start to process the form
else { // The question defaults to the first category, unpublished(private )
  $sql = "INSERT INTO
              questions(q_subject, q_date, q_cat,q_content,
                        publish_status)
          VALUES( '" . mysqli_real_escape_string($_SESSION['link'],
                                             $_POST['q_subject']) . "',
                 NOW(),
                 1,
                 '" . mysqli_real_escape_string($_SESSION['link'],
                                             $_POST['q_content']) . "',
                 0
                )
          "; //'

	// Try to connect to the query
  $result = mysqli_query( $_SESSION['link'], $sql );

  if( !$result ) { // If we can't somehow write to the database, revert
    echo "An error has occured when trying to load the database";
    $sql = "ROLLBACK;";
    $result = mysqli_query( $_SESSION['link'], $sql );
  }
  else { // Commit the changes to the database
    $sql = "COMMIT;";
    $result = mysqli_query( $_SESSION['link'], $sql );
    echo 'Your question has been posted!';
    echo '<a href="index.html">Home</a>';
  } // If insert worked
} // If "BEGIN WORK;" succeeded
?>
