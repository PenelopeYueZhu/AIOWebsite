<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "nl" lang = "nl">
<head>
  <meta httl-equiv="Content-Type" content = "text/html; charset=UTF-8" />
  <meta name="description" content="A forum for UCSD students and faculties to
                                    ask questions about integrity."/>
  <meta name="keywords" content="UCSD, integrity, question, realtime"/>
  <title>Integrity Overflow</title>

  <!-- style scripts -->
  <link rel="stylesheet" href="../src/styles/index.css">

</head>
<body>

  <?php include 'connect.php' ?>
  <h3> Sign in </h3>

  <!-- Check if the user is not already signed in -->
  <?php if( isset($_SESSION['signed_in']) && ($_SESSION['signed_in'] ) ) : ?>
    <p class="Error">You are already signed in.</p>

  <!-- else if the user is signed in already -->
  <?php else : ?>

    <!-- If the form hasn't been submitted, display the form -->
    <?php if( $_SERVER['REQUEST_METHOD'] != 'POST') : ?>
      <form method="post" action="">
      Username: <input type="text" name="user_name" />
      Password: <input type="password" name="user_pw" />
      <input type="submit" value="sign in" />
      </form>

    <!-- If the form is already submitted -->
    <?php else : ?>
      <?php
        // Check for errors
        $errors = array();

        if( !isset( $_POST['user_name'] ) )
          $errors[] = 'The username field must not be empty';
        if( !isset( $_POST['user_pw'] ) )
          $errors[] = 'The password cannot be empty';
      ?>

      <!-- Check if we ran into errors -->
      <?php if( !empty($errors) ) : ?>
        <div class="error">
          <p>Uhmmmm...</p>
          <ul>
            <?php
            foreach( $errors as $key => $value )
              echo '<li>' . $value . '</li>';
            ?>
          </ul>
        </div>
      <!-- If we did not run into errors, check the information -->
      <?php else : ?>
        <?php
          $sql = "SELECT
                      user_id,
                      permission,
                      user_name,
                      user_level
                  FROM
                      users
                  WHERE
                      user_name =
                        '" . mysqli_real_escape_string( $_SESSION['link'],
                                                     $_POST['user_name']) . "'
                  AND
                      user_pw =
                        '" . sha1( $_POST['user_pw'] ) . "'
                 ";
          $result = mysqli_query( $_SESSION['link'], $sql );
        ?>

        <!-- If we did not successfully insert -->
        <?php if( !$result ): ?>
          <p class="error">Something went wrong...</p>

        <!-- If we successfully retrieved from database -->
        <?php else : ?>
          <!-- If info retrieved in empty -->
          <?php if( mysqli_num_rows ( $result ) == 0 ) : ?>
            <p class="error">Incorrect username or password. Please try again.</p>
          <?php else : ?>
            <?php
              $_SESSION['signed_in'] = true;

              while( $row = mysqli_fetch_assoc( $result ) ) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_permission'] = $row['permission'];
              }
              echo 'Welcome, ' . $_SESSION['user_name'] .
                   '. <a href="index.php">Home</a>';
            ?>

          <?php endif; ?>

        <!-- End control for retrieving information from the database -->
        <?php endif; ?>
      <!-- End control for gathering information from form -->
      <?php endif; ?>

    <!-- End control for if the form is already submitted or not -->
    <?php endif; ?>
  <?php endif; ?>
</body>
