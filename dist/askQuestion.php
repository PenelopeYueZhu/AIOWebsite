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
  <?php include 'connect.php' ?>
  <?php if( !(isset($_SESSION['signed_in']) && ($_SESSION['signed_in'] ) ) ):?>
    <!--If the user is not signed in, then we need to prompt him/her to sign in -->

    <h4 class="Error">You are not signed in.<p>
    <a href="signin.php">Sign In Here.</a>
  <?php else : ?>
      <!-- The form has not been posted, so we just display the information-->
      <?php if( $_SERVER['REQUEST_METHOD'] != 'POST' ) : ?>

        <?php if( count( $_SESSION['categories'] ) == 0 ) :?>
          <p class="Error">Before you can post anything,
              please wait for admin to create categoreis</p>
        <?php else : ?>
          <form method="post" action="">
            Subject: <input type="text" name="q_subject" />
            Category:<select name="q_cat">

            <?php
              for( $i = 0 ; $i < count( $_SESSION['categories'] ); $i++ ) {
                echo '<option value="' . ($i+1) .
                     '">' . $_SESSION['categories'][$i]
                     . '</option>';
              }
            ?>
           </select>

            Message: <textarea name="q_content" /></textarea>
                  <input type="submit" value="post_question"/>
           </form>

         <?php endif; ?>

    <?php else :// When we do post the question, process the form ?>
      <?php // Start the transaction
      $query = "BEGIN WORK;";
      $result = mysqli_query( $_SESSION['link'], $query );
      // If the connection has failed
      if( !$result )
        echo 'An error has occured when trying to load the database.?Begin work';
      // else we start to process the form
      else {
        $sql = "INSERT INTO
                    questions(q_subject, q_date, q_cat, q_by, q_content)
                VALUES( '" . mysqli_real_escape_string($_SESSION['link'],
                                                   $_POST['q_subject']) . "',
                       NOW(),
                       '" . mysqli_real_escape_string($_SESSION['link'],
                                                   $_POST['q_cat']) . "',
                       '" . $_SESSION['user_id'] . "',
                       '" . mysqli_real_escape_string($_SESSION['link'],
                                                   $_POST['q_content']) . "'
                      )
                ";
        $result = mysqli_query( $_SESSION['link'], $sql );
        if( !$result ) {
          echo "An error has occured when trying to load the database";
          $sql = "ROLLBACK;";
          $result = mysqli_query( $_SESSION['link'], $sql );
        }
        else {
          $sql = "COMMIT;";
          $result = mysqli_query( $_SESSION['link'], $sql );
          echo 'Your question has been posted!';
          echo '<a href="index.php">Home</a>';
        } // If insert worked
      } // If "BEGIN WORK;" succeeded
      ?>
    <!-- End control for if the method is posted or not -->
    <?php endif; ?>
  <!-- End control for is the student has signed in or not -->
  <?php endif; ?>
</body>
