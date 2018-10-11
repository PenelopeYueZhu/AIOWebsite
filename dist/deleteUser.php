<?php
include 'connect.php';

$user_id = $_GET['id'];
if( !$user_id ) echo 'Problem finding the user, please try again later.';
else {
  $sql_user = "DELETE from users WHERE user_id = $user_id";
  $result_delete = mysqli_query( $_SESSION['link'], $sql_user );

  if( !$result_delete ) echo 'Failed to connect to the database.';
  else {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
  }
}
 ?>
