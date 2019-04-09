<?php

/**
 * Get all the categories if user does not specify a question
 * Or get a category for one question specifically for that question
 */

include 'connect.php';

// Arrays that will be storing all the information about the categories
$id = -1;
$sql_cat;
$errors = array();
$cats = array();
$catsId = array();
$catDescription = array();
// The array that will be storing the arrays
$return = array();

// Get the question id if we have an id
if( isset( $_GET['id'] ) ) {
  $id = $_GET['id'];
	// Now we are only selecting the specific question
  $sql_cat = "SELECT
                  q_cat
              FROM
                  questions
              WHERE q_id = $id";

  $result_cat = mysqli_query( $_SESSION['link'], $sql_cat );

	// If we do not have a result aka query failed
  if( !$result_cat ) {
		array_push( $errors, "Error loading all the categories." );
  }
  else { // Otherwise we got a result successfully
		// We push each categories that this question belongs to into the return
		// array
    while( $row_cat = mysqli_fetch_assoc( $result_cat ) ) {
      array_push( $cats, $row_cat['q_cat'] );
    }
  }
}
// Then if we dont have an id, we just get all categories
else {
  $sql_cat = "SELECT
                  cat_name,
                  cat_id,
                  cat_description
              FROM
                  categories";

	// We get the result from query
  $result_cat = mysqli_query( $_SESSION['link'], $sql_cat );

	// If we do not have a result, then we know the query failed.
  if( !$result_cat ) {
    array_push( $errors, "Error loading all the categories." );
  }
  else { // The query successfully returned
		// We push all the questions with all the categories into the returning
		// array
    while( $row_cat = mysqli_fetch_assoc( $result_cat ) ) {
      array_push( $catsId, strval($row_cat['cat_id']) );
      array_push( $cats, $row_cat['cat_name'] );
      array_push( $catDescription, $row_cat['cat_description']);
    }
  }
}

// Return everything 
$return["catsId"] = $catsId;
$return["categories"] = $cats;
$return["errors"] = $errors;
$return["descriptions"] = $catDescription;

echo json_encode( $return );
?>
