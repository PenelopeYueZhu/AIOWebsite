<?php
// signin.php
include 'connect.php'
include 'header.php'

echo '<h3>Sign in</h3>';

// First check if the user has already signed in;
if( isset ($_SESSION['signed_in'] ) ){
  echo 'You are already signed in.';
}
else {
  if( $_SERVER['REQUEST_METHOD'] != 'POST'){
    
  }
}
 ?>
