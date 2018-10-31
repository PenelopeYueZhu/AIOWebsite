<?php
/* Assign new categories to the question
 */

include 'connect.php';

$q_id = $_GET['id'];
$cat;
// Loop through all the categories to get one that is already checked
for( $i = 0 ; $i < count($_SESSION['categories']) ; $i++ ){
  if( isset( $_POST[$i]) ) {
    $cat = $_POST[$i];
    break;
  }
}

$sql_pushCat = "UPDATE
                   questions
               SET
                  q_cat = $cat
               WHERE
                  q_id = $q_id";

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
exit;
?>
