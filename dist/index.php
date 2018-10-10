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
  <div class="welcome-banner">
    <div id="stick-on-top">
      <?php
        include 'connect.php';
      ?>
      <h3>NOTE: For questions or concerns that involve personal information such as name,
          student id, or anything that can identify a specific person, please
          email us at aio@ucsd.edu through your ucsd email. Emailing is the only
          secure communication channel, so please help us protect your and others'
          privacy.</h3>
    </div>
    <h1>Welcome to UC San Diego Academic Integrity Online Office</h1>
    <h3>For general questions, please visit our website first. We might have
        the answer for you there.</h3>
  </div>
  <div class="mainBody">
    <div class="left">
      <!-- Personal account if logged in. If not, it says log in or sign up-->
      <?php if( isset( $_SESSION['signed_in'] ) && $_SESSION['signed_in']) : ?>

        <div class="logged-in-user">
          Hello, <?php echo $_SESSION['user_name'] ?>.
          Not you? <a href="signout.php">Sign out</a>
          <a>View your question history</a>
          <a>Update your profile</a>

          <?php if( $_SESSION['user_permission'] <= 1 ) : // If you are peer ?>

            <a>View your reply history</a>
            <?php if( $_SESSION['user_permission'] == 0 ) : // If you are admin ?>
              <a href="admin-manage.php">Manage users</a>
            <?php endif; ?>

          <?php endif; //End control if it's a student or from office?>
        </div>

      <?php else: ?>
          <div class="not-logged-in-user">
            <a href="signin.php">Sign in</a>
            or <a href="signup.php">create an account</a>. </div>
      <?php endif; // End control for logged in or not ?>
    </div>
    <div id="middle">

   </div>
  </div>
</body>
<script type="text/javascript" src="index_bundle.js"></script></body>
