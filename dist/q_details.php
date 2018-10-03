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
  // View an indivial question in detail
  include 'connect.php';

  // Display the corresponding question based on which id we get from url
  $q_id = $_GET['id'];
  if( !$q_id ) echo 'Problem loading the question, please try again later.';
  else { // Get all the information about that question
    $sql = "SELECT
                q_subject,
                q_date,
                q_cat,
                q_by,
                q_content
            FROM
                questions
            WHERE q_id = $q_id
           ";

    $result = mysqli_query( $_SESSION['link'], $sql );
    if( !$result ) {
      echo 'Failed to load the database.';
    }
    else { // Display the question
      $row = mysqli_fetch_assoc( $result );
      echo '<h1>' . $row['q_subject'] . '</h1>';
      echo '<p>' . $row['q_content'] . '<p>';
    }
  }

  /* Display replies, if thre is any */
  $sql_reply = "SELECT
              reply_id,
              DATE(reply_date),
              reply_by,
              reply_content
          FROM
              replies
          WHERE reply_q_id = $q_id
         ";
  $result_reply = mysqli_query( $_SESSION['link'], $sql_reply );
  if( !$result_reply ) echo 'Failed to load the database.';
  // When there is a reply
  else if( mysqli_num_rows( $result_reply ) > 0 ) {
    // Display each reply
    echo '<table border="0">
            <tr>
              <th>Replied on</th>
              <th>Reply</th>
            </tr>
         ';
    while( $row_reply = mysqli_fetch_assoc( $result_reply ) ){
      echo '<tr>';
        echo '<td class="leftpart">';
          echo $row_reply['DATE(reply_date)'];
        echo '</td>';
        echo '<td class="rightpart">';
          echo $row_reply['reply_content'];
        echo '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }

  /* Add the box to add new reply */
  // If the user is not signed in, ask them to sign in to answer Questions
  if( !( isset($_SESSION['signed_in']) && ($_SESSION['signed_in'] ) ) ) {
    echo 'You have to sign in to answer a question.';
    echo '<a href="signin.php">Sign in here</a>';
    echo 'Do not have an account? <a href="signup.php">Sign up here</a>';
  }
  else { // Add the box for user to reply
    if( $_SERVER['REQUEST_METHOD'] != 'POST'){ // When the user hasn't submitted
      echo '<h2>Reply</h2>';
      echo '<form method="post" action="">
           Reply: <textarea name="q_reply" /></textarea>
           <input type="submit" value="reply" />
           </form>
           ';
    }
    else { // When the user submitted
      $query = "BEGIN WORK;";
      $result_query = mysqli_query( $_SESSION['link'], $query );
      if( !$result_query ) echo 'Failed to load the database when trying to
                               display box.';


      $sql_new_reply = "INSERT INTO
                          replies(reply_date, reply_q_id, reply_by,
                                    reply_content)
                          VALUES(NOW(),
                                 '" . $q_id . "',
                                 '" . $_SESSION['user_id'] . "',
                                 '" . mysqli_real_escape_string( $_SESSION['link'] ,
                                                                 $_POST['q_reply']) . "'
                                )
                     ";
      $result_reply = mysqli_query( $_SESSION['link'], $sql_new_reply );
      // When there is an error that occured in the middle of insertion
      if( !$result_reply ) {
        echo 'Failed to load the database.';
        $query = "ROLLBACK;";
        $result_query = mysqli_query( $_SESSION['link'], $query );
      }
      else {
        $query = "COMMIT;";
        $result_query = mysqli_query( $_SESSION['link'], $query );
        echo 'Your reply has been posted.';
      } // End of else !result_reply
    }
  }
  ?>
</body>
