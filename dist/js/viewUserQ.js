window.onload = function() {
  getUserQuestions();
}

/* Get all the questions in the database that is asked by the user,
 * published or not
 */
function getUserQuestions(){
  // Getting all the question details here
  var allQReq = new XMLHttpRequest();
  allQReq.onload = function () {

    // Parse the responce text
    var allQReqArray = JSON.parse( allQReq.responseText );

    // Now we write the values into the website
    var table = document.getElementById("allQTable");

    var qTitles = allQReqArray["qTitles"];
    var qCats = allQReqArray["category"];
    var qContent = allQReqArray["qContent"];
    var qIds = allQReqArray["qId"];
    var qTimes = allQReqArray["qTimes"];

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

      // Create the category tag
      var category = document.createElement('h6');
      category.innerHTML = qCats[i];

      // The preview cell of the table
      var tdContent = document.createElement('td');

      var pContent = document.createElement('p');
      pContent.className = "previewQ";

      // The link to the details of the question
      var preview_address = document.createElement('a')
      preview_address.setAttribute("href", "q_details.html?id=" + qIds[i]);
      preview_address.appendChild( document.createTextNode( qContent[i] ) );
      pContent.appendChild( preview_address );

      address.appendChild( category );
      address.appendChild( qTitle );
      tdTitle.appendChild( address );
      tdTime.appendChild( qTime );
      tdContent.appendChild( pContent );

      tr.appendChild( tdTime );
      tr.appendChild( tdTitle );
      tr.appendChild( tdContent );

      table.appendChild( tr );
    }

  }
  allQReq.open( "get", "getUserQ.php");

  allQReq.send();
}


/* Same function that we used to search through functions */
function searchTitles() {
  // Declare variables
  var input, filter, table, tr, td, i, a;
  input = document.getElementById("searchTitleInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allQTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    tdTitle = tr[i].getElementsByTagName("td")[1];
    tdContent = tr[i].getElementsByTagName("td")[2];

    if ( tdTitle && tdContent ) {
      if (tdTitle.innerHTML.toUpperCase().indexOf(filter) > -1
          || tdContent.innerHTML.toUpperCase().indexOf(filter) > -1
         ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
