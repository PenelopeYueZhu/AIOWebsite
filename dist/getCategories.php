<?php

/**
 * Get all the categories if user does not specify a question
 * Or get a category for one question specifically for that question
 */

include 'Database.php';
session_start();

// First establis the connection to the database
$database = new Database();
// We only need RO permission
$database->initROConnection();

// Prepare the array to store all the category information
$resultArray = array(); // The big container
$resultArray['catsId'] = array();
$resultArray['categories'] = array(); // The smaller containers

$id; // potential id of the question
// If no id passed in from URL, use -1 to indicate getting all cates
if( !isset( $_GET['id'] ) ) {
	$id = -1;
} else $id = $_GET['id'] ;

$database->getCategories( $id, $resultArray );

// End the connections
$database->endConnection();

echo json_encode( $resultArray );

?>
