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

    // First get all the peers from the database
    $sql_peers = "SELECT
                     user_id,
                     user_name,
                     user_email,
                     user_create_on
                  FROM
                      users
                  WHERE
                      permission = 1
                 ";
    $result_peers = mysqli_query( $_SESSION['link'], $sql_peers );

    if( !$result_peers ) {
      echo 'Something went wrong when loading the peers';
    }
    else {
      echo '<h2>Peers</h2>';
      echo '<table class="peers">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Action</th>
              </tr>
             ';
      // Retrieve and display all rows
      while( $row_peer = mysqli_fetch_assoc( $result_peers ) ) {
        echo '<tr>
                <td>' . $row_peer['user_name'] . '</td>' .
                '<td>' . $row_peer['user_email'] . '</td>' .
                '<td>' . $row_peer['user_create_on'] . '</td>' .
                '<td>
                  <a href="deleteUser.php?id=' .
                  $row_peer['user_id'] . '">Delete user</a>
                </td>' .
              '</tr>';
      }
      echo '</table>';
      echo '<a href="addPeer.php">Add A Peer</a>';
    } // End control for retrieving peers

    // Retrieve and display all the registered students
    $sql_students = "SELECT
                     user_id,
                     user_name,
                     user_email,
                     user_create_on
                  FROM
                      users
                  WHERE
                      permission = 2
                 ";
    $result_students = mysqli_query( $_SESSION['link'], $sql_students );

    if( !$result_students ) {
      echo 'Something went wrong when loading the students';
    }
    else {
      echo '<h2>Students</h2>';
      echo '<table class="peers">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Action</th>
              </tr>
             ';
      // Retrieve and display all rows
      while( $row_student = mysqli_fetch_assoc( $result_students ) ) {
        echo '<tr>
                <td>' . $row_student['user_name'] . '</td>' .
                '<td>' . $row_student['user_email'] . '</td>' .
                '<td>' . $row_student['user_create_on'] . '</td>' .
                '<th>
                  <a href="deleteUser.php?id=' .
                  $row_student['user_id'] . '">Delete user</a>
                </th>' .
              '</tr>';
      }
      echo '</table>';
    } // End control for retrieving students
   ?>
<a href="index.php">Home</a>
</body>
