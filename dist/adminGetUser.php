<?php
include 'connect.php';

// First verify that we are dealing with admin
if( $_SESSION['user_permission'] == 0 ) {

  $permission = $_GET['sort'];
  // Arrays used to return the informatoin
  $userId = array();
  $userName = array();
  $userEmail= array();
  $userCreatedOn = array();
  $userInfo = array();

  // First get all the peers from the database
  $sql_users = "SELECT
                   user_id,
                   user_name,
                   user_email,
                   user_create_on
                FROM
                    users
                WHERE
                    permission = $permission
                ORDER BY
                    user_create_on
               ";
  $result_users = mysqli_query( $_SESSION['link'], $sql_users );

  if( !$result_users ) {
    echo 'Something went wrong when loading the database';
  }
  else {
    // Retrieve and display all rows
    while( $row_users = mysqli_fetch_assoc( $result_users ) ) {
      array_push( $userId, $row_users['user_id']);
      array_push( $userName, $row_users['user_name']);
      array_push( $userEmail, $row_users['user_email']);
      array_push( $userCreatedOn, $row_users['user_create_on']);
    }

  } // End control for retrieving users

  $userInfo['userId'] = $userId;
  $userInfo['userName'] = $userName;
  $userInfo['userEmail'] = $userEmail;
  $userInfo['userCreatedOn'] = $userCreatedOn;

  echo json_encode( $userInfo );
}
?>
