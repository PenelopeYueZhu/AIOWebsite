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

  <!-- If the user has not submit the form -->
  <?php if( $_SERVER['REQUEST_METHOD'] != 'POST' ): ?>
    <form method="post" action="" >
      Username: <input type = "text" name = "user_name" />
      Password: <input type = "password" name = "user_pw" />
      Password again: <input type="password" name = "user_pw_check" />
      Email: <input type = "email" name = "user_email" />
      <input type = "submit" value = "Sign Up" />
    </form>
  <!-- If the user has clicked submit -->
  <?php else: ?>
    <?php
      $errors = array();

      // If the username is inputted
      if( isset( $_POST['user_name'] ) ){
        if( !ctype_alnum( $_POST['user_name'] ) ){
          $errorsp[] = 'The username can only contain letters and digits.';
        }
        if( strlen( $_POST['user_name'] ) > 30){
          $errors[] = 'The username cannot be longer than 30 characters.';
        }
      } // end isset username
      else {
        $errors[] = 'The username must not be empty.';
      }

      // If the passwork is inputted
      if( isset( $_POST['user_pw'] ) ) {
        if( $_POST['user_pw'] != $_POST['user_pw_check'] ) {
          $errors[] = 'Password does not match.';
        }
      } // end isset password
      else $errors[] = 'The password must not be empty.';
    ?>
  <!-- end if the form is submitted or not -->

  <!-- check if there is error, and if yes, display them -->
  <?php if( !empty($errors) ): ?>
    <p class="error">Mmmmm seems like we ran into a problem here...</p>
    <ul>
    <?php foreach( $errors as $key => $value ) {
            echo '<li>' . $value . '</li>'; // List the errors
          }
    ?>
    </ul>
    <!-- If there is no error, we try to insert the user information into the
         data base -->
    <?php else : ?>
      <?php
            $sql = "INSERT INTO
                        users( permission, user_name, user_pw, user_email,
                               user_create_on, user_last_login, user_level)
                        VALUES( 1,
                                '" . mysqli_real_escape_string( $_SESSION['link'],
                                                          $_POST['user_name']) . "',
                                '" . sha1($_POST['user_pw']) . "',
                                '" . mysqli_real_escape_string( $_SESSION['link'],
                                                          $_POST['user_email']) . "',
                                NOW(), null, 0)
                    ";
          $result = mysqli_query($_SESSION['link'], $sql);
      ?>

      <?php if( !$result ) : ?>
        <p class="error">Something went wrong while registering. Please try again later.</p>
      <?php else : ?>
        Registered successfully!
      <!-- End if to see if the thing has been successfully inserted -->
      <?php endif; ?>

    <?php endif; ?>
  <?php endif; ?>
</body>
