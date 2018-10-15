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

  <script>
    // Getting all the question details here
    var allQReq = new XMLHttpRequest();
    allQReq.onload = function () {
      console.log( allQReq.responseText );

      // Parse the responce text
      var allQReqArray = JSON.parse( allQReq.responseText );

      // Now we write the values into the website

      // Then we write the replies
      var qTitles = allQReqArray["qTitles"];
      var qIds = allQReqArray["qId"];
      var qTimes = allQReqArray["qTimes"];
      var table = document.getElementById("allQTable");

      // Loop through the questions
      for( var i = 0 ; i < qTimes.length ; i++ ) {
        var tr = document.createElement('tr');

        // The time cell of the table
        var tdTime = document.createElement('td');
        var qTime = document.createTextNode( qTimes[i]);

        // The title cell of the table
        var tdTitle = document.createElement('td');
        var qTitle = document.createTextNode( qTitles[i]);

        // The link to the details of the question
        var address = document.createElement('a')
        address.setAttribute("href", "q_details.php?id=" + qIds[i]);

        address.appendChild( qTitle );
        tdTitle.appendChild( address );
        tdTime.appendChild( qTime );

        tr.appendChild( tdTime );
        tr.appendChild( tdTitle );

        table.appendChild( tr );
      }

    }
    allQReq.open( "get", "getAllQ.php");

    allQReq.send();
  </script>

  <script>
    function searchTitles() {
      // Declare variables
      var input, filter, table, tr, td, i, a;
      input = document.getElementById("searchTitleInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("allQTable");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
  </script>

  <!-- table to display all the questions -->
  <br>
  <input type="text" id="searchTitleInput"
         onkeyup="searchTitles()" placeholder="Search by keywords"
         class="form-control" aria-label="Default"
         aria-describedby="inputGroup-sizing-default">
  <br>
  <table class="table table-striped table-hover" id="allQTable">
    <thead>
      <tr>
        <th>Posted Time</th>
        <th>Question</th>
      </tr>
    </thead>
  </table>

</body>
<script type="text/javascript" src="index_bundle.js"></script></body>
