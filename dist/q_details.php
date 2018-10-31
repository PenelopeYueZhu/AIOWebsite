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

  <!-- my own css -->
  <link rel="stylesheet" href="../src/styles/q-details.css">

</head>
<body>
  <!-- Peers and admin can reply  -->
  <?php include 'connect.php'; ?>

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

  <!-- Container for react -->
  <div id="middle">
  </div>

  <script>
    // Getting all the question details here
    var questionReq = new XMLHttpRequest();
    questionReq.onload = function () {
      // Parse the responce text
      var questionReqArray = JSON.parse( questionReq.responseText );
      // Now we write the values into the website

      // First write the question
      // add a category tag
      var category = document.createElement('h6');
      category.className = "category-tag";
      category.innerHTML = questionReqArray["category"];

      document.getElementById("question_title").appendChild( category );
      document.getElementById("question_title").appendChild(
                        document.createTextNode(questionReqArray["title"] ) );
      document.getElementById("question_content").innerHTML =
        questionReqArray["content"];

      // Then we write the replies
      var numReplies = questionReqArray["numReplies"];
      var replies = questionReqArray["replies"];
      var replyTime = questionReqArray["replyTime"];
      var replyDiv = document.createElement("div");

      // Loop through the questions
      for( var i = 0 ; i < numReplies ; i++ ) {
        var pContent = document.createElement('p');
        // Style this reply paragraph
        pContent.className = "reply-content";

        var tContent = document.createElement('p');
        // Style this time line
        tContent.className = "reply-time";

        var replyText = document.createTextNode( replies[i] );
        var individualReplyTime =
          document.createTextNode( "AIO Office  " +  replyTime[i]);
        var divideLine = document.createElement( "hr");

        pContent.appendChild( replyText );
        tContent.appendChild( individualReplyTime );
        replyDiv.appendChild( pContent );
        replyDiv.appendChild( tContent );
        replyDiv.appendChild( divideLine );
      }
      document.getElementById("replies").appendChild( replyDiv );
    }
    questionReq.open( "get",
                      "getQDetail.php?id=" + <?php echo $_GET['id'] ?>);

    questionReq.send();
  </script>

  <!-- Place to put the question we retrieved -->
  <h1 id="question_title"> </h1>
  <p id="question_content" style="margin-left: 2em;"></p>

  <hr>

  <!-- place to put the replies we retrieved -->
  <div id="replies">

  </div>

  <!-- You have to have peer access to reply -->
  <?php if( (isset($_SESSION['signed_in']) ) && $_SESSION['signed_in']
             && $_SESSION['user_permission'] < 2 ): ?>

  <form method="post" action= <?php echo "pushReply.php?id=" . $_GET['id'] ?> >
    <div class="form-group">
      <label for="reply"> Add your reply:</label><br>
      <textarea class="form-control" name="q_reply" style="margin-left: 2em;"/>
         </textarea>
    </div>

      <button type="submit" class="btn btn-primary">Submit</button>
  </form>

  <br>

  <script>
    var catReq = new XMLHttpRequest();
    catReq.onload = function () {
      var catsArray = JSON.parse( catReq.responseText );
      // Now we write the values into the website

      // Get all the categories
      var allCats = catsArray["categories"];
      var errors = catsArray["errors"];
      var id = catsArray["catsId"];

      // TODO Deal with errors

      // Loop through the cats and display them
      for( var i = 0 ; i < allCats.length ; i++ ) {
        var checkDiv = document.createElement('div');
        checkDiv.className = "form-check form-check-inline";

        // Input box
        var input = document.createElement('input');
        input.className = "form-check-input";
        // Set all the attributes
        input.setAttribute("type", "checkbox");
        input.setAttribute("name", i)
        input.setAttribute("value", parseInt( id[i]) );
        input.setAttribute("id", i);

        // Label of the input box
        var label = document.createElement('label');
        label.className = "form-check-label";
        // Set all the attributes
        input.setAttribute("for", i);

        label.innerHTML =  allCats[i];

        // Group all elements together
        checkDiv.appendChild( input );
        checkDiv.appendChild( label );
        document.getElementById("select-cats").appendChild( checkDiv );
      }
    }

    catReq.open( "get", "getCategories.php");

    catReq.send();
  </script>
  <!-- Assign categories by peers -->
  <form id="assign-cats" method="post"
        action=<?php echo "pushQCategory.php?id=" . $_GET['id'] ?>>
    <div id="select-cats"></div>

    <button type="submit" class="btn btn-outline-primary">
        Assign Category
    </button>
  </form>

  <br>

  <script>
    var statusReq = new XMLHttpRequest();
    statusReq.onload = function () {
      var status = statusReq.responseText;
      // If it's 1, then the question has been published
      if( status.localeCompare( "\"1\"" ) == 0 )
        document.getElementById("status_button").innerHTML = "unpublish";
      else // Else we publish it
        document.getElementById("status_button").innerHTML = "publish";
    }

    statusReq.open( "get",
                    "getPublishStatus.php?id=" + <?php echo $_GET['id'] ?>);

    statusReq.send();
  </script>

  <form method="post"
        action=<?php echo "toggleQStatus.php?id=" . $_GET['id'] ?> >
    <button type="submit" class="btn btn-outline-primary" id="status_button">
    </button>
  </form>
<?php endif; ?>

</body>

<script type="text/javascript" src="index_bundle.js"></script></body>
