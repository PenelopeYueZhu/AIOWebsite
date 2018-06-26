<?php
// signin.php
include 'connect.php';
include 'header.php';

echo '<h3>Sign in</h3>';

// First check if the user has already signed in;
if( isset ($_SESSION['signed_in'] ) ){
  echo 'You are already signed in.';
}
else {
  // If the form hasn't been posted yet, display it
  if( $_SERVER['REQUEST_METHOD'] != 'POST'){
    echo '<form method="post" action="">
    Username: <input type="text" name="user_name" />
    Password: <input type="password" name="user_pw" />
    <input type="submit" value="sign in" />
    </form>
    ';
  }
  else {
    // The form has been posted, we will process the data
    $errors = array();

    if( !isset( $_POST['user_name'] ) )
      $errors[] = 'The username field must not be empty';
    if( !isset( $_POST['user_pw'] ) )
      $errors[] = 'The password cannot be empty';

    // CHeck if we ran into errors
    if( !empty($errors) ) {
      echo 'Uhmmmm...';
      echo '<ul>';
      foreach( $errors as $key => $value ) {
        echo '<li>' . $value . '</li>';
      }
      echo '</ul>';
    } // end of if empty errors
    else {
      // Try to check with the database
      $sql = "SELECT
                  user_id,
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
             ";
      $result = mysqli_query( $_SESSION['link'], $sql );

      // If we fail to get a connection
      if( !$result ) echo 'Something went wrong...';
      else {
        // When we can't get the username and password pair
        if( mysqli_num_rows ( $result ) == 0 ) {
          echo $_POST['user_name'];
                         echo $_POST['user_pw'];
          echo 'Incorrect username or password. Please try again.';
        }
        else {
          $_SESSION['signed_in'] = true;

          while( $row = mysqli_fetch_assoc( $result ) ) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['user_level'] = $row['user_level'];
          }

          echo 'Welcome, ' . $_SESSION['user_name'] . '. <a href="index.php">Home</a>';
        }
      }
    }
  }
}

include 'footer.php'
 ?>
