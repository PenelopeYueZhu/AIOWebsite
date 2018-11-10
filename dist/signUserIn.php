<?php
include 'connect.php';
  // Check for errors
  $errors = array();

  if( !isset( $_POST['user_name'] ) )
    $errors[] = 'The username field must not be empty';
  if( !isset( $_POST['user_pw'] ) )
    $errors[] = 'The password cannot be empty';

    if( !empty($errors) ) {
      echo 'error logging in.';
    } else {
      $sql = "SELECT
                  user_id,
                  permission,
                  user_name,
                  user_level
              FROM
                  users
              WHERE
                  user_name =
                  '" . mysqli_real_escape_string( $_SESSION['link'],
                                                  $_POST['user_name']) . "'
              AND
                  user_pw =
                    '" . sha1( $_POST['user_pw'] ) . "'
             "; //'
      $result = mysqli_query( $_SESSION['link'], $sql );

      if( !$result ) {
        echo 'Something went wrong...';
      } else {
        if( mysqli_num_rows ( $result ) == 0 ) {
          echo 'Incorrect username or password.
                Please try again.';
        } else {
          $_SESSION['signed_in'] = true;

          while( $row = mysqli_fetch_assoc( $result ) ) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['user_permission'] = $row['permission'];
          }
          echo 'Welcome, ' . $_SESSION['user_name'] .
               '. <a href="index.html">Home</a>';
        }
      }
    }
?>
