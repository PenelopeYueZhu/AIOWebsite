<?php
include 'connect.php';

$query = "BEGIN WORK;";

$result = mysqli_query( $_SESSION['link'], $query );
// If the connection has failed
if( !$result )
  echo 'An error has occured when trying to load the database.?Begin work';
// else we start to process the form
else {
  $sql = "INSERT INTO
              questions(q_subject, q_date, q_cat, q_by, q_content)
          VALUES( '" . mysqli_real_escape_string($_SESSION['link'],
                                             $_POST['q_subject']) . "',
                 NOW(),
                 '" . mysqli_real_escape_string($_SESSION['link'],
                                             $_POST['q_cat']) . "',
                 '" . $_SESSION['user_id'] . "',
                 '" . mysqli_real_escape_string($_SESSION['link'],
                                             $_POST['q_content']) . "'
                )
          ";
  $result = mysqli_query( $_SESSION['link'], $sql );
  if( !$result ) {
    echo "An error has occured when trying to load the database";
    $sql = "ROLLBACK;";
    $result = mysqli_query( $_SESSION['link'], $sql );
  }
  else {
    $sql = "COMMIT;";
    $result = mysqli_query( $_SESSION['link'], $sql );
    echo 'Your question has been posted!';
    echo '<a href="index.php">Home</a>';
  } // If insert worked
} // If "BEGIN WORK;" succeeded
?>
