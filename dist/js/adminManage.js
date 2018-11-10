function onLoad() {
  // Execute all these after dom has been loaded
  getAllCategories(); // Get the question first
  getAllUsers();

};

// ------------------------------------------------------------------------

/* Function that gets all the categories for the admin to manage */
function getAllCategories() {
  var catReq = new XMLHttpRequest();
  catReq.onload = function () {
    var catsArray = JSON.parse( catReq.responseText );
    // Now we write the values into the website

    // Get all the categories
    var allCats = catsArray["categories"];
    var errors = catsArray["errors"];
    var id = catsArray["catsId"];
    var descriptions = catsArray["descriptions"];

    // TODO Deal with errors

    // Loop through the cats and display them
    for( var i = 0 ; i < allCats.length ; i++ ) {
      // Each category takes up one line
      var tr = $("<tr></tr>");
      // Each cell
      var nameCell = $("<td></td>").text(allCats[i]);
      var descriptionCell = $("<td></td>").text(descriptions[i]);
      // Append the cells to the line
      tr.append( nameCell, descriptionCell);
      // Append the line to the table
      $("#cat-table").append(tr);
    }
  }

  catReq.open( "get", "getCategories.php");

  catReq.send();
}

// ------------------------------------------------------------------------

/* Function to get all the student and peer uesrs for the admin to manage */

function getAllUsers() {

  // First we get the student table
  var studentReq = new XMLHttpRequest();
  studentReq.onload = function() {
    createUserTable(2, studentReq );
  }

  studentReq.open( "get", "adminGetUser.php?sort=2");
  studentReq.send();

  // Then we get the peer table
  var peerReq = new XMLHttpRequest();
  peerReq.onload = function() {
    createUserTable(1, peerReq );
  }

  peerReq.open( "get", "adminGetUser.php?sort=1");
  peerReq.send();

}

// ------------------------------------------------------------------------

function createUserTable( userPermission, request ) {
  var userTable;

  // If user has permission 1, then its a peer
  if( userPermission == "1" ) {
    userTable = $("#peer-table");
  }
  // Otherwise it's general user table
  else if( userPermission == "2" ) {
    userTable = $("#student-table");
  }

  // Then we create the table

  var reqArray = JSON.parse( request.responseText );
  // Now we write the values into the website

  // Get all the categories
  var id = reqArray["userId"];
  var name = reqArray["userName"];
  var email = reqArray["userEmail"];
  var createTime = reqArray["userCreatedOn"];

  // TODO Deal with errors

  // Loop through the cats and display them
  for( var i = 0 ; i < id.length ; i++ ) {
    // Each user takes up one line of the table
    var tr = $("<tr></tr>");

    // Create each cell
    var nameCell = $("<td></td>").text(name[i]);
    var emailCell = $("<td></td>").text(email[i]);
    var createTimeCell = $("<td></td>").text(createTime[i]);
    // Action cell requires two steps becuase the inner html is a link
    var actionCell = $("<td></td>");
    var action = $("<a href=\"deleteUser.php?id=" + id[i] +"\"></a>")
                    .text("delete");
    actionCell.append( action );

    // Append the line to the table
    tr.append(nameCell, emailCell, createTimeCell, actionCell);
    userTable.append( tr );
  }
}

// ------------------------------------------------------------------------
