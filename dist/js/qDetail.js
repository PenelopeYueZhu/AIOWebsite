function onLoad() {
  // Execute all these after dom has been loaded
  getQuestionDetail(); // Get the question first
  addPeerOptions();

};

// ------------------------------------------------------------------------

/* Function that gets all the question details and display them */
function getQuestionDetail() {
  // Getting all the question details here
  var questionReq = new XMLHttpRequest();
  questionReq.onload = function () {
    // Parse the responce text
		console.log( questionReq.responseText);
    var questionReqArray = JSON.parse( questionReq.responseText );

		// Check if we actually returned any details
		if( questionReqArray == null ) {
			// Then we display the error message and halt
			document.getElementById("question_title").innerHTML =
	      "You do not have access to this question";
		}
		// Otherwise we write the values into the website
		else {
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
  }
  questionReq.open( "get",
                    "GetQDetail.php"  +window.location.search);

  questionReq.send();
}

// ------------------------------------------------------------------------

/* Function that gets all the categories for the user to choose from */
function getAllCategories() {
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

  catReq.open( "get", "GetCategories.php");

  catReq.send();
}

// ------------------------------------------------------------------------

/* Function that gets the publish status of the questions */
function getPublishStatus() {
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
                  "GetPublishStatus.php" + window.location.search);

  statusReq.send();
}

// ------------------------------------------------------------------------

function addPeerOptions() {
  var userDataReq = new XMLHttpRequest(); // Request the data from signin script
  userDataReq.onload = function () {
    // Parse the responce text
    var userDataArray = JSON.parse( userDataReq.responseText );
    var userSignedIn = userDataArray[0];
    var userPermission = userDataArray[0];
    // Check the user's permission. If it's peer, creat the reply box, Category
    // Select box and publish/unpublish option
    if( userPermission < 1 ) {
                          // THe form for reply box
      $('body').append($('<form method="post" id="post-reply" '  +
                           'action="PushReply.php' +  window.location.search +
                          '">' +
                           '<div class="form-group">' +
                             '<label for="reply"> Add your reply:</label><br>'+
                             '<textarea class="form-control"' +
                                       'name="q_reply"' +
                                       'style="margin-left: 2em;"/>' +
                             '</textarea></div>'+
                           '<button type="submit" class="btn btn-primary">' +
                             'Submit</button>' +
                        '</form><br>' +
                         // the form to select categories
                        '<form id="assign-cats" method="post" id="post-cat"' +
                              'action="PushQCategory.php' +
                              window.location.search + '">'+
                          '<div id="select-cats"></div>' +
                            '<button type="submit" ' +
                                   'class="btn btn-outline-primary">'+
                              'Assign Category' +
                            '</button>' +
                        '</form><br>' +
                         // The publish / unpublish question button
                        '<form method="post" ' + 'action="ToggleQStatus.php'
                                     + window.location.search + '">' +
                          '<button type="submit" ' +
                                  'class="btn btn-outline-primary"' +
                                  'id="status_button">' +
                          '</button>' +
                       '</form>'));
      // Now we have the dom ready, we call each function on the form
      // To populate it
      getCategoryOptions(); // Populate the category
      getPublishStatus(); // Set the button to publish/unpublish

    }
  }
  userDataReq.open( "get", "CheckSignedIn.php" );

  userDataReq.send();
}

// -------------------------------------------------------------------------
/* Function that gets all the categories and lay them out as checkboxes for
  peers to choose and add */
function getCategoryOptions() {
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

  catReq.open( "get", "GetCategories.php");

  catReq.send();
}

// ---------------------------------------------------------------------------

/* Function that gets if a question is published or not */
function getPublishStatus() {
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
                  "GetPublishStatus.php" + window.location.search);

  statusReq.send();
}
