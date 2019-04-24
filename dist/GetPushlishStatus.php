<?php
/**
 * File that calls function getPublishStatus to get a value and return to
 * caller from ajax
 */

include 'connect.php';

// Get the id from the address line
$q_id = $_GET['id'];

echo json_encode( getPublishStatus( $q_id ) );

?>
