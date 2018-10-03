<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "nl" lang = "nl">
<head>
  <meta httl-equiv="Content-Type" content = "text/html; charset=UTF-8" />
  <meta name="description" content="Admin page for AIO online office."/>
  <title>Integrity Overflow</title>

  <!-- style scripts -->
  <link rel="stylesheet" href="../src/styles/index.css">

</head>
<body>
  <?php
    include 'connect.php';

    // First get all the users from the database
    $sql_all_users = "SELECT * FROM users";
    $result_all_users = mysqli_query( $_SESSION['link'], $sql_all_users );

    if( !$result_all_users ) {
      echo 'Something went wrong when loading the database';
    }
    else {
        echo '  

             '
    }

   ?>

</body>
