<?php
/**
 * Connection script that will establish a connection to the database.
 * It is not a persistent link, so you have to include this in every
 * php file that needs access to the database
 */

session_start();

// Get the information we need from the private folder
// $config = parse_ini_file( '../../private/connection.ini');
$server =  'aioforumdb.cvcwqiayiang.us-east-2.rds.amazonaws.com';
$username   = 'aioadmin';
$password   = 'Zy100,.,.';
$database   = 'ebdb';

// Connect to the database and store it in a $_SESSION obect
// Will be disconnect later
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
