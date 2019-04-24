<?php
/**
 * Push the question into the database when a user submits a new question
 * Recording the subject, content, data of the question
 */
include 'connect.php';

$query = "BEGIN WORK;";

// Grab all the posted values
$q_subject = filter_input(INPUT_POST, 'q_subject', FILTER_VALIDATE_STRING);
$q_content = filter_input(INPUT_POST, 'q_content', FILTER_SANITIZE_STRING);
$captcha = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
// If we dont have a captcha, then we report the problem and exit
if( !$captcha ) {
	echo '<h2>Please check with the admin. reCAPTCHA failed.</h2>';
	exit(1);
}

$secretKey = '6LfCCqAUAAAAAEcn73YLrq63RqYO9zBWgn6RJt59';
$ip = $_SERVER['REMOTE_ADDR'];

/* post verification request to server*/
// The url to send the verification request to
$url = 'https://www.google.com/recaptcha/api/siteverify';

// First assemble the data
$data = array('secret' => $secretKey, 'response' => $captcha);
$options = array(
  'http' => array(
    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    'method'  => 'POST',
    'content' => http_build_query($data)
  )
);
$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$responseKeys = json_decode($response,true);
header('Content-type: application/json');

// Now if google says you are a real human, we push the question
if($responseKeys["success"]) {

	// Try to begin work
	$result = mysqli_query( $_SESSION['link'], $query );
	// If the connection has failed
	if( !$result )
	  echo 'An error has occured when trying to load the database.?Begin work';
	// else we start to process the form
	else { // The question defaults to the first category, unpublished(private )
	  $sql = "INSERT INTO
	              questions(q_subject, q_date, q_cat,q_content,
	                        publish_status)
	          VALUES( '" . mysqli_real_escape_string($_SESSION['link'],
	                                             $_POST['q_subject']) . "',
	                 NOW(),
	                 1,
	                 '" . mysqli_real_escape_string($_SESSION['link'],
	                                             $_POST['q_content']) . "',
	                 0
	                )
	          "; //'

		// Try to connect to the query
	  $result = mysqli_query( $_SESSION['link'], $sql );

	  if( !$result ) { // If we can't somehow write to the database, revert
	    echo "An error has occured when trying to load the database";
	    $sql = "ROLLBACK;";
	    $result = mysqli_query( $_SESSION['link'], $sql );
	  }
	  else { // Commit the changes to the database
	    $sql = "COMMIT;";
	    $result = mysqli_query( $_SESSION['link'], $sql );
	    echo json_encode(array('success' => 'true'));
	  } // If insert worked
	} // If "BEGIN WORK;" succeeded

} else {
  echo json_encode(array('success' => 'false'));
}


?>
