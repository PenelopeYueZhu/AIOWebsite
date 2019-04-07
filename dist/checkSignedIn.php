<?php
/** A script without function that checks user permission and user name
 *
 * Used for ajax to get permission and name for javascript.
 * When the user is signed in as a peer / career staff, the permission is 0 with
 * Universal name UCSD AIO
 * Otherwise the user is just guest, with permisssion of 1
 * All data encoded with jason_encode and returned with echo
 */

include 'connect.php'; // Connect to the sql database
$indUser = $GLOBALS['SIGN_IN_SESSION_INDEX']; // Get the index of user info

if( isset($_SESSION[$indUser]) && ($_SESSION[$indUser] ) ) {
   // Then we encode the user data
   // array[0] - user permission, 0 for admin
	 // array [1] - user name, UCSD AIO for admin
	 $userData = array( 0, 'UCSD AIO' );
	 echo json_encode( $userData );
} else{

	// Then we encode guest data
	$guestData = array( 1, 'Triton');
	echo json_encode( $guestData );
}
?>
