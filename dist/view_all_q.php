<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "nl" lang = "nl">
<head>
  <meta httl-equiv="Content-Type" content = "text/html; charset=UTF-8" />
  <meta name="description" content="A forum for UCSD students and faculties to
                                    ask questions about integrity."/>
  <meta name="keywords" content="UCSD, integrity, question, realtime"/>
  <title>Integrity Overflow - Ask Us A Question</title>

  <!-- Linking in bootstrap -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Popper JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</head>
<body>
  <!-- Head banner -->
  <div class="jumbotron text-center" style="margin-bottom:0;">
    <h1 style="font-size: 50px">UCSD AIO Online</h1>
    <h2>For general questions, please visit our website first. We might have
       the answer for you there.</p>

    <h2 style="color: red"> NOTE: For questions or concerns that involve personal information such as name,
             student id, or anything that can identify a specific person, please
             email us at aio@ucsd.edu through your ucsd email. Emailing is the only
             secure communication channel, so please help us protect your and others'
             privacy. </h2>
  </div>

  <div id="middle">
  </div>

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

// Sorting drop down list
// When we have not applied the sorting and filter options
$sorting_option = $_GET['sort'];
$filter_option = $_GET['filter'];
if( $_SERVER['REQUEST_METHOD'] != 'POST') {
  echo '<form class="form-inline" method="post" action="">';
    echo '<label for="sort">Sort by:</label>';
    echo '<select name="sort_by" class="form-control">';
      // display the options
      if( $sorting_option.strcmp( "qNTO") == 0 ) {
        echo '<option value="qNTO">Newest Question first</option>';
        echo '<option value="qOTN">Oldest question first</option>';
      } else {
        echo '<option value="qOTN">Oldest question first</option>';
        echo '<option value="qNTO">Newest Question first</option>';
      }
    echo '</select>';

    // Filter drop down list
    echo '<label for="category">Filter by category:</label>';
    echo '<select name="filter_by" class="form-control">';
      // Display all the categories
      if( $filter_option == 0 ) {
        echo '<option value="0">All</option>';
        for( $i = 0 ; $i < count( $_SESSION['categories'] ); $i++ ) {
          echo '<option value="' . ($i+1) . '">' . $_SESSION['categories'][$i]
                . '</option>';
        }
      } else {
        echo '<option value="' . $filter_option . '">' .
             $_SESSION['categories'][$filter_option-1] . '</option>';
        echo '<option value="0">All</option>';
        for( $i = 0 ; $i < count( $_SESSION['categories'] ); $i++ ) {
          if ( $i == ($filter_option - 1) ) continue;
             echo '<option value="' . ($i+1) . '">' . $_SESSION['categories'][$i]
                  . '</option>';
          }
      }
  echo '</select>';

  echo '<button type="submit" class="btn btn-primary">Submit</button>
        </form>
       ';
}
else { // When the user do apply a filter or sorting option,
       // Sort and filter the thing
  $url = 'Location: view_all_q.php?sort=' . $_POST['sort_by'] .
         '&filter=' . $_POST['filter_by'];
  header($url);
}

$sql = getQuestions( $sorting_option, $filter_option );

$result = mysqli_query( $_SESSION['link'], $sql );

// If the result is null
if( !$result ) {
  echo 'Fail to load all the posts.';
}
else {
  echo '<table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Posted Time</th>
              <th>Question</th>
            </tr>
          </thead>
       ';
  // Display all the questions
  while( $row = mysqli_fetch_assoc( $result ) ){
    echo '<tr>';
      echo '<td >';
        echo $row['q_date'];
      echo '</td>';
      echo '<td>';
        echo '<a href="q_details.php?id=' . $row['q_id'] . '">' .
             $row['q_subject'] . '</a>';
      echo '</td>';
    echo '</tr>';
  }
  echo '</table>';
}
?>
</body>
<script type="text/javascript" src="index_bundle.js"></script></body>
