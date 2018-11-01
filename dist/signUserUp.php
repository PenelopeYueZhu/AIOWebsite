<?php
include 'connect.php';
  // Check for errors
  $errors = array();

  if( !isset( $_POST['user_name'] ) )
    echo 'The username field must not be empty';
  else if( !isset( $_POST['user_pw'] || !isset( $_POST['user_pw_check']) ) )
    echo 'The password cannot be empty';
  else if( $_POST['user_pw'] !=  $_POST['user_pw_check'] )
    echo 'Password does not match';
  else {
    $sql = "INSERT INTO
                users( permission, user_name, user_pw, user_email,
                       user_create_on, user_last_login, user_level)
                VALUES( 2,
                        '" . mysqli_real_escape_string( $_SESSION['link'],
                                                  $_POST['user_name']) . "',
                        '" . sha1($_POST['user_pw']) . "',
                        '" . mysqli_real_escape_string( $_SESSION['link'],
                                                  $_POST['user_email']) . "',
                        NOW(), null, 0)
            ";//'
  $result = mysqli_query($_SESSION['link'], $sql);

  if( !$result ) {
    echo '<p class="error">Something went wrong when loading the server</p>';
  } else {
    echo 'Registered successfully!' . 
          '<a href="signin.php">Home</a>';
 }
}
?>
