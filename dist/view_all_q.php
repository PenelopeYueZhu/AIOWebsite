<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "nl" lang = "nl">
<head>
  <meta httl-equiv="Content-Type" content = "text/html; charset=UTF-8" />
  <meta name="description" content="A forum for UCSD students and faculties to
                                    ask questions about integrity."/>
  <meta name="keywords" content="UCSD, integrity, question, realtime"/>
  <title>Integrity Overflow - Ask Us A Question</title>

</head>
<body>
<?php
// To look at all the questions
include 'connect.php';

/* Function to grab questions based on filter and sorting options */
function getQuestions( $sort_by, $filter_by ) {
  $sql_return = null;
  if( $filter_by == 0 ) {
    // Get the sorting option
    switch( $sort_by ) {
      case "qNTO":
        echo 'Newest to oldest';
        $sql_return = "SELECT
                           q_id, q_subject, q_content, q_date, q_by,
                           user_id, user_name
                       FROM
                           questions
                       LEFT JOIN
                           users
                       ON questions.q_by = users.user_id
                       ORDER BY
                           q_id DESC
                      ";
        break;
      case "qOTN":
        echo 'Oldest to newest.';
        $sql_return = "SELECT
                           q_id, q_subject, q_content, q_date, q_by,
                           user_id, user_name
                       FROM
                           questions
                       LEFT JOIN
                           users
                       ON questions.q_by = users.user_id
                       ORDER BY
                           q_id ASC
                      ";
        break;
    }
  } // filter_by = 0
  else {
    switch( $sort_by ) {
      case "qNTO":
        echo 'Newest to oldest';
        $sql_return = "SELECT
                           q_id, q_subject, q_content, q_date, q_by,
                           user_id, user_name
                       FROM
                           questions
                       LEFT JOIN
                           users
                       ON questions.q_by = users.user_id
                       WHERE q_cat = $filter_by
                       ORDER BY
                           q_id DESC
                      ";
          break;
      case "qOTN":
        echo 'Oldest to newest.';
        $sql_return = "SELECT
                           q_id, q_subject, q_content, q_date, q_by,
                           user_id, user_name
                       FROM
                           questions
                       LEFT JOIN
                           users
                       ON questions.q_by = users.user_id
                       WHERE q_cat = $filter_by
                       ORDER BY
                           q_id ASC
                       ";
        break;
    }
  } // filter_by has a category
  return $sql_return;
}

// Put a sorting option, default is to sort by asked time newest first
//$sorting_option = $_GET['sort'];
// Filter option, default is to show all the questions
//$filter_option = $_GET['filter'];

/*$sql = "SELECT
            cat_id,
            cat_name,
            cat_description
        FROM
            categories
       ";
$result = mysqli_query( $_SESSION['link'], $sql );

// If we cannot retrive result
if( !$result ) exit('Fail to load the database.');*/

// Sorting drop down list
// When we have not applied the sorting and filter options
$sorting_option = "qNTO";
$filter_option = "0";
$filter_option = $_POST['filter_by'];
if( $_SERVER['REQUEST_METHOD'] != 'POST') {
  echo '<form method="post" action="">';
    echo '<select name="sort_by">';
      // display the options
      echo '<option value="qNTO">Newest Question first</option>';
      echo '<option value="qOTN">Oldest question first</option>';
    echo '</select>';
    // Filter drop down list
    echo '<select name="filter_by">';
      // Display all the categories
      echo '<option value="0">All</option>';
      for( $i = 0 ; $i < count( $_SESSION['categories'] ); $i++ ) {
        echo '<option value="' . ($i+1) . '">' . $_SESSION['categories'][$i]
              . '</option>';
      }
  echo '</select>';

  echo '<input type="submit" value="Apply"/>
        </form>
       ';
}
else { // When the user do apply a filter or sorting option,
       // Sort and filter the thing
  $sorting_option = $_POST['sort_by'];
  $filter_option = $_POST['filter_by'];
  $url = 'Location: view_q.php?sort=' . $_POST['sort_by'] .
         '&filter=' . $_POST['filter_by'];
  header($url);
}

$sql = getQuestions( $sorting_option, $filter_option );/*"SELECT
            q_id, q_subject, q_content, q_date, q_by,
            user_id, user_name
        FROM
            questions
        LEFT JOIN
            users
        ON questions.q_by = users.user_id
       ";*/
$result = mysqli_query( $_SESSION['link'], $sql );

// If the result is null
if( !$result ) {
  echo 'Fail to load all the posts.';
}
else {
  echo '<table border="1">
          <tr>
            <th>Posted Time</th>
            <th>Question</th>
          </tr>
       ';
  // Display all the questions
  while( $row = mysqli_fetch_assoc( $result ) ){
    echo '<tr>';
      echo '<td class="leftpart">';
        echo $row['q_date'];
      echo '</td>';
      echo '<td class="rightpart">';
        echo '<a href="q_details.php?id=' . $row['q_id'] . '">' .
             $row['q_subject'] . '</a>';
      echo '</td>';
    echo '</tr>';
  }
  echo '</table>';
}
?>
</body>
