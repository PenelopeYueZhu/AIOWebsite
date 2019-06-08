<?php
/**
 * Assign new categories to the question
 * Only avaialbe to the admin
 */

//include 'connect.php';
include 'Database.php';

// First establish a conenction
// Only proceed if we are signed in as admin
if( !(isset($_SESSION['signed_in'])) || !$_SESSION['signed_in'] ) {
	echo 'You do not have permission to perform this action.';
	exit(1);
} else {
	// Establis the connection to database
	$database = new Database();
	$database.initAdminConnection();

	// Then we get the id of the question
	$qId = $_GET['id'];

	$catId;
	// Loop throught all the categories to get one that's selected by user
	for( $i = 0 ; $i < count($_SESSION['categories']) ; $i++ ){
	  if( isset( $_POST[$i]) ) {
	    $catId = $_POST[$i];
	    break;
	  }
	}

	// Then we call database function to set the category
	$result = $database.setCategory( $qId, $catId );

	// If it's true, then return to the previous page
	if( $result ){
		$database->endConnection();
		header("Location: {$_SERVER['HTTP_REFERER']}");
	}
	exit(0);
}

/*
// Build the query string to set the category
$sql_pushCat = "UPDATE
                   questions
               SET
                  q_cat = $cat
               WHERE
                  q_id = $q_id";

// Try to query it into the database
$result_pushCat = mysqli_query( $_SESSION['link'], $sql_pushCat );
if( !$result_pushCat ) { // When we cannot write into the database
  echo "error when assigning the categories ";
  $sql = "ROLLBACK;";
  $result = mysqli_query( $_SESSION['link'], $sql );
}
else { // Commit the changes to the database
  $sql = "COMMIT;";
  $result = mysqli_query( $_SESSION['link'], $sql );
} // If insert worked

// Go back to the previous page
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;*/
?>
