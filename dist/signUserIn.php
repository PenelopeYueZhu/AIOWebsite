<?php
/**
 * Script to sign a user in
 * Only used for admins
 * UCSD AIO
 */

require_once '../vendor/autoload.php';

// Get $id_token via HTTPS POST.
$id_token = $_POST['idtoken'];
$CLIENT_ID = '710662674500' .
             '-futrtlk6umv4lnv0au4iqr4q70h107p3.apps.googleusercontent.com';
if( !$id_token ) echo json_encode( 'failed to pass in the token');

// Specify the CLIENT_ID of the app that accesses the backend
$client = new Google_Client(['client_id' => $CLIENT_ID ]);
$payload = $client->verifyIdToken($id_token); // Verify ID token
if ($payload) {
	$userid = $payload['sub'];
	echo json_encode( $userid );

	// If request specified a G Suite domain:
	//$domain = $payload['hd'];



} else {
	echo 'You are not an authorized user. Please continue as a guest.';
}

/*
include 'connect.php';
// Array of errors
$errors = array();

if( !isset( $_POST['user_name'] ) ) // If the user did not enter username
  $errors[] = 'The username field must not be empty';
if( !isset( $_POST['user_pw'] ) ) // If the user did not enter password
  $errors[] = 'The password cannot be empty';

// If there is any kinda of error when user is inputting their information
if( !empty($errors) ) {
	// Echo and done
  echo 'error logging in.';
} else {
  // Try to get the user data entry with the same username
  $sql = "SELECT
              user_name
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

  // If the result is null, we failed to grab anything from database
  if( !$result ) {
    $errors[] = 'Did not get anything from MySQL';
  } else {
    // If the result is not null but is empty
    if( mysqli_num_rows ( $result ) == 0 ) {
      $errors[] = 'Incorrect username or password. Please try again.';
    } else {
      // We successfully found a match in the database
      $_SESSION['signed_in'] = true;

      // Log the user information into window sessoin
      while( $row = mysqli_fetch_assoc( $result ) ) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['user_permission'] = $row['permission'];
      }
      echo 'Welcome, ' . $_SESSION['user_name'] .
         '. <a href="index.html">Home</a>';
    }
  }
}*/
?>
