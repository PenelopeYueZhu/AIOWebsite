<?php

/* Get all the categories if user does not specify a question
 * Or get a category for one question specifically for that question
 */

include 'connect.php';

$id = -1;
$sql_cat;
$errors = array();
$cats = array();
$catsId = array();
$catDescription = array();
$return = array();

// Get the question id if we have an id
if( isset( $_GET['id'] ) ) {
  $id = $_GET['id'];
  $sql_cat = "SELECT
                  q_cat
              FROM
                  questions
              WHERE q_id = $id";

  $result_cat = mysqli_query( $_SESSION['link'], $sql_cat );

  if( !$result_cat ) {
      array_push( $errors, "Error loading all the categories." );
  }
  else {
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

  $result_cat = mysqli_query( $_SESSION['link'], $sql_cat );

  if( !$result_cat ) {
    array_push( $errors, "Error loading all the categories." );
  }
  else {
    while( $row_cat = mysqli_fetch_assoc( $result_cat ) ) {
      array_push( $catsId, strval($row_cat['cat_id']) );
      array_push( $cats, $row_cat['cat_name'] );
      array_push( $catDescription, $row_cat['cat_description']);
    }
  }
}

$return["catsId"] = $catsId;
$return["categories"] = $cats;
$return["errors"] = $errors;
$return["descriptions"] = $catDescription;

echo json_encode( $return );
?>
