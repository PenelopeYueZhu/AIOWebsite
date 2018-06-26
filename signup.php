<?php
// Sign up php script
include 'connect.php';
include 'header.php';

echo '<h3>Sign up</h3>';

if( $_SERVER ['REQUEST_METHOD'] != 'POST' ) {
  /* The form hasn't been posted yet, just display it
  action = "" will cause the form to post to the same page it is on */

  echo '<form method="post" action="" >
  Username: <input type = "text" name = "user_name" />
  Password: <input type = "password" name = "user_pw" />
  Password again: <input type="password" name = "user_pw_check" />
  Email: <input type = "email" name = "user_email" />
  <input type = "submit" value = "Add category" />
  </form>
  ';
}
else { // So the form has been posted
  // Check the data
  $errors = array();

  // If the username is inputted
  if( isset( $_POST['user_name'] ) ){
    if( !ctype_alnum( $_POST['user_name'] ) ){
      $errorsp[] = 'The username can only contain letters and digits.';
    }
    if( strlen( $_POST['user_name'] ) > 30){
      $errors[] = 'The username cannot be longer than 30 characters.';
    }
  } // end isset username
  else {
    $errors[] = 'The username must not be empty.';
  }

  // If the passwork is inputted
  if( isset( $_POST['user_pw'] ) ) {
    if( $_POST['user_pw'] != $_POST['user_pw_check'] ) {
      $errors[] = 'Password does not match.';
    }
  } // end isset password
  else $errors[] = 'The password must not be empty.';

  // If we encounter some errors
  if( !empty($errors) ) {
    echo 'Mmmmm seems like we ran into a problem here...';
    echo '<ul>';
    foreach( $errors as $key => $value ) {
      echo '<li>' . $value . '</li>'; // List the errors
    }
    echo '</ul>';
  }
  else { // When there are no errors and we are good to go
    /*$sql = "INSERT INTO
                users( user_name, user_pw, user_email, user_date,
                       user_level)
                VALUES( '" . mysqli_real_escape_string( $link,
                                                        $_POST['user_name']) . "',
                        '" . sha1($_POST['user_pw']) . "',
                        '" . mysqli_real_escape_string( $link,
                                                        $_POST['user_email']) . "',
                        NOW(),
                        0)
            ";*/
      $sql = "INSERT INTO
                  users( user_name, user_pw, user_email, user_create_on,
                          user_last_login, user_level)
                  VALUES( '" . mysqli_real_escape_string( $_SESSION['link'],
                                                          $_POST['user_name']) . "',
                          '" . sha1($_POST['user_pw']) . "',
                          '" . mysqli_real_escape_string( $_SESSION['link'],
                                                          $_POST['user_email']) . "',
                          NOW(), null, 0)
                    ";
    $result = mysqli_query($_SESSION['link'], $sql);

    if( !$result ){
      echo 'Something went wrong while registering. Please try again later.';
    }
    else {
      echo 'Registered successfully!';
    }
  }
}
include 'footer.php';
?>
