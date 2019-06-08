<?php
/**
 * File that gets all the questions and display them based on the sorting
 * And filtering requirement
 */

include 'Database.php';
session_start();

// First establis the RO connection to the database
$database = new Database();

// If we are signed in, then we establish the admin connection
if( isset($_SESSION['signed_in'] ) && $_SESSION['signed_in'] == 1 ) {
	$database->initAdminConnection();
} else { // Otherwise we just do normal RO connection
	$database->initROConnection();
}

// Prepare the array to store all the parsed information of the database
$resultArray = array(); // The big container
// The smaller containers
$resultArray['qTimes'] = array();
$resultArray['qId'] = array();
$resultArray['qTitles'] = array();
$resultArray['qContent'] = array();
$resultArray['qCats'] = array();
$resultArray['qPublished'] = array();

// Let database fetch all the questions
$database->getAllQuestions( $resultArray );

// Clear all the connections 
$database->endConnection();

echo json_encode( $resultArray );
?>
