<?php
/* A script where we check if the user has signed in or
 * If yes, then we return the permission and user name,
 * if not, then we return no permission and guest as user name
 */

 include 'connect.php';
 if( isset($_SESSION['signed_in']) && ($_SESSION['signed_in'] ) ) {
   // Then we encode the user data
   // 0 - 1 for signed in 0 for not
   // 1 - user name
   // 2 - user permission
   $userData = array( 1,
                      $_SESSION['user_name'],
                      $_SESSION['user_permission'] );
   echo json_encode( $userData );
 } else {

   // Then we encode guest data
   $guestData = array( 0, 'guest', 3);
   echo json_encode( $guestData );
 }
 ?>
