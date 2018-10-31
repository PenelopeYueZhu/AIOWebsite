<?php
//connect.php

/*
 * Guide to timeout the sessoin in a very insecure way -- please be mindful
 * https://stackoverflow.com/questions/8311320/how-to-change-the-session-timeout-in-php
 */

$FINAL_SESSION_TIME_IN_MS = 216000;
$FINAL_SESSION_TIME_IN_S = 3600;

// Server should keep session data for at least 1 hour
//ini_set( 'session.gc_maxlifetime', $FINAL_SESSION_TIME_IN_MS );

// Each client should remember their session id for at least an hour
//session_set_cookie_params( $FINAL_SESSION_TIME_IN_S );

//if( !isset( $_SESSION['connected'] )|| !$_SESSION['connected'] ) {
  session_start();
  //$_SESSION['connected'] = true;
//}

// Now set an upper bound for session
$now = time();
if( isset( $_SESSION['discard_after'] ) && $now > $_SESSION['discard_after'] ) {
  session_unset();
  session_destroy();
  session_start();
}

$_SESSION['discard_after'] = $now + $FINAL_SESSION_TIME_IN_S;


// Avoid connection more than once
//if( !isset( $_SESSION['link'] ) || $_SESSION['link'] == null ) {

  // Get the information we need from the private folder
  $config = parse_ini_file( '../../private/connection.ini');
  $server = $config['servername'];
  $username   = $config['username'];
  $password   = $config['password'];
  $database   = 'test_forum_db';

  $_SESSION['link'] = mysqli_connect($server, $username,$password, $database);
  if( !$_SESSION['link'] ){
      exit('Error: could not establish database connection');
  }
  else if( !mysqli_select_db( $_SESSION['link'], $database ) ){
      exit('Error: could not select the database');
  }
  else {
    // Now we get all the categories and store them as a constant
    $sql = " SELECT
                 cat_id,
                 cat_name
             FROM
                 categories
           ";

    $result = mysqli_query( $_SESSION['link'], $sql );

    // If we cannot get any result
    if( !$result ){
      echo 'The categories fail to load. Please try again later.';
    }
    else {
      $categories = array();
      while( $row = mysqli_fetch_assoc( $result ) ) {
        $categories[] = $row['cat_name'];
      }
      // Now put this array as a constant in session
      $_SESSION['categories'] = $categories;
      //echo 'TO BE DELETED: ConNECted';
    }
  }
//}
?>
