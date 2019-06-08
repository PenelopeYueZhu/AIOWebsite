<?php
/**
 * File that calls function getPublishStatus to get a value and return to
 * caller from ajax
 */
include 'Database.php';
// Make the new database object and establish RO permission
$database = new Database();
$database->initROConnection();

// The reference to hold the status
$status;

// If the website does not provide an id, then refuse
if( !isset($_GET['id'])|| !$database->getPublishStatus( $_GET['id'], $status )){
	$database->endConnection();
	echo "something went wrong...";
	exit(1);
} else {
	// Otherwise we proceed to fetch the status
	$database->endConnection();
	echo json_encode( $status );
}

?>
