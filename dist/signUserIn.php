<?php
/**
 * Script to sign a user in. Use Google API for user authenticatin OAuth2
 * Only used for admins
 * UCSD AIO
 */
include 'connect.php';
require_once 'vendor/autoload.php';

// Get $id_token via HTTPS POST.
$id_token = $_POST['idtoken'];
$CLIENT_ID = '710662674500' .
             '-futrtlk6umv4lnv0au4iqr4q70h107p3.apps.googleusercontent.com';
$ADMIND_ID = 106154969788821290729;

$returnVal = 0;

if( !$id_token ) echo json_encode( 'failed to pass in the token');

// Specify the CLIENT_ID of the app that accesses the backend
$client = new Google_Client(['client_id' => $CLIENT_ID ]);
$payload = $client->verifyIdToken($id_token); // Verify ID token
if ($payload) {
	$userid = $payload['sub'];

	// Now compare the userid. If it is the right id, then we know it is
	// The right account
	if( $userid == $ADMIND_ID ) {
		$_SESSION['signed_in'] = TRUE;
		$returnVal = 1;
	}
	// If request specified a G Suite domain:
	//$domain = $payload['hd'];

}

// Return 0 if we didnt get a payload or the id is wrong
// 1 if we successfully signed in
echo json_encode( $returnVal );
?>
