<?php
include 'connect.php';

$cat_id = $_GET['id'];
if( !$user_id ) echo 'Problem finding the user, please try again later.';
else {
  $sql_cat = "DELETE from categories WHERE cat_id = $cat_id";
  $result_delete = mysqli_query( $_SESSION['link'], $sql_cat );

  if( !$result_delete ) echo 'Failed to connect to the database.';
  else {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
  }
}
 ?>
