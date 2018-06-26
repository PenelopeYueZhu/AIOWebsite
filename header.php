<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "nl" lang = "nl">
<head>
  <meta httl-equiv="Content-Type" content = "text/html; charset=UTF-8" />
  <meta name="description" content="A short description"/>
  <meta name="keywords" content="put, keywords, here"/>
  <title> PHP-MYSQL forum</title>
  <link rel="stylesheet" href="style.css" tyle="text/css">
</head>
<body>
  <h1>AIO Forum</h1>
  <div id="wrapper">
    <div id="menu">
       <a class="item" href="create_cat.php">Home</a> -
       <a class="item" href="create_topic.php">Create a topic</a> -
       <a class="item" href="create_cat.php">Create a category</a>

       <div id="userbar">

         <div id="userbar">
           <?php
           //if( !isset($_SESSION['signed_in'] ) ) $_SESSION['signed_in'] = false;
           if( isset( $_SESSION['signed_in'] ) && $_SESSION['signed_in'])
             echo 'Hello ' . $_SESSION['user_name'] .
                  '. Not you? <a href="signout.php">Sign out</a>';
           else
             echo '<a href="signin.php">Sign in</a>
                   or <a href="sign up">create an account</a>.';
            ?>
         </div>

       </div>
    </div><!--menu-->
  </div> <!-- wrapper-->
