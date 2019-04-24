<?php
/**
 * File that gets all the questions and display them based on the sorting
 * And filtering requirement
 */

include 'connect.php';

// arrays to store different kinds of information we need
$error = '';
$allQ = array(); // The array that will store all the arrays
// The key that represents what publsh_status questin we want
// Default is 1 - public questions
$statusKey = getQueryString( 1 );

// If the user is signed in, then we know it's a peer/admin
if( isset($_SESSION['signed_in'] ) && $_SESSION['signed_in'] == 1 ) {

  // Get private quesitons that are not published yet
	$statusKey = getQueryString( 0 );
}

$result_allQ = mysqli_query( $_SESSION['link'], $statusKey );

// If the result is null, store the error message
if( !$result_allQ ) {
	echo null;
  exit(1);
}
else { // Get all the questions into the array
	parseQueryResult( $result_allQ, $allQ );
}

echo json_encode($allQ);

/**
 * Function to grab questions based on filter and sorting options
 * @param publishStatus 1 means the question is public, 0 is private
 * @return sql_return the sql string that is used to query questions
 */
function getQueryString( $publishStatus  ) {
	// use questions.puslish_state >= $publishStatus
	// So we can get both public and private question when we have param 0
	// But only public questions when we have param 1
  $sql_return = "SELECT
                      q_id, q_subject, q_content, q_date,q_cat, publish_status
                  FROM
                      questions
                  WHERE questions.publish_status >= $publishStatus
                  ORDER BY
                      q_id DESC
                ";

  return $sql_return;
}

/**
 * Function to push question information to corresponding array
 * @param queryResult the fetched info from the database
 * @param resultArray the array to store the parsed value of questions from
 *                    the queryResult. This is passed in by reference
 */
function parseQueryResult( $queryResult, &$resultArray ) {
	// First declare temp arrays
	$qCats = array();
	$qTimes = array();
	$qId = array();
	$qTitles = array();
	$qContent = array();
	$qPublished = array();

	// First loop through the rows
	while( $row = mysqli_fetch_assoc( $queryResult) ) {
		// Then enter the data into each array column by column
		array_push( $qTimes, $row['q_date'] );
		array_push( $qId, $row['q_id']);
		array_push( $qTitles, $row['q_subject'] );
		array_push( $qContent, $row['q_content'] );
		array_push( $qCats,
							  $_SESSION["categories"][intval($row['q_cat']) - 1]);
		array_push( $qPublished, $row['publish_status'] );
	}

	// Push them into allQ for return
	$resultArray['qTimes'] = $qTimes;
	$resultArray['qId'] = $qId;
	$resultArray['qTitles'] = $qTitles;
	$resultArray['qContent'] = $qContent;
	$resultArray['qCats'] = $qCats;
	$resultArray['qPublished'] = $qPublished;

}
?>
