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
  <?php include 'connect.php' ?>

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

  <form method="post" action="pushQuestion.php">
    <div class="form-group">
      <label for="subject">Subject:</label>
      <input type="text" name="q_subject" />
    </div>
    <div class="form-group">
      <label for"category">Category:</label>
      <select name="q_cat">
      <?php
        for( $i = 0 ; $i < count( $_SESSION['categories'] ); $i++ ) {
          echo '<option value="' . ($i+1) .
               '">' . $_SESSION['categories'][$i]
               . '</option>';
        }
      ?>
      </select>
    </div>

    <div class="form-group">
      Message: <textarea name="q_content" /></textarea>
    </div>

    <?php if( (isset($_SESSION['signed_in']) ) && $_SESSION['signed_in'] )  : ?>
      <button type="submit" class="btn btn-primary">Submit</button>
    <?php else: ?>
      <button type="submit" class="btn btn-primary" disabled >
        Submit
      </button>
      *Please sign in first to ask a question.*
    <?php endif; ?>
  </form>
</body>

<script type="text/javascript" src="index_bundle.js"></script></body>
