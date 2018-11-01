window.onload = function() {
  // Execute all these after dom has been loaded
  // register a listener to the select category form
  getAllQuestions(); // Get all the questions first
  getAllCategories(); // Then we get all the categories and display them
};

//---------------------------------------------------------------------------

// Filter through all the categories the user has chosen
function filterCategories(){
  // array of selected categories
  var selectedCats = [];
  // Array to store the checked categories
  var checked = [];

  // Get all categories
  var allCats = $("#select-cats input");

  // Get all the table contents
  var allTrs = $("#allQTable tr");

  // First loop through all options and get the checked ones
  for( var i = 0 ; i < allCats.length ; i++ ){
    if( allCats[i].checked ) selectedCats.push( allCats[i].name.toUpperCase() );
  }

  // Loop through all table rows, and hide those who don't match the category
  for (var i = 0; i < allTrs.length; i++) {
    // This is is the title part of the td, which includes the category tag
    tdTitle = allTrs[i].getElementsByTagName("td")[1];

    if ( tdTitle ) {
      // When we don't have a filter selected
      if( selectedCats.length == 0 ) {
        // Show all the questions
        allTrs[i].style.display = "";
      } else {
        // When we do have a filter selected
        var tag = tdTitle.getElementsByTagName("h6")[0];
        // Loop through all the categoris
        for( var j = 0 ; j < selectedCats.length ; j ++ ) {
          // Search each line for the category
          if (tag.innerHTML.toUpperCase().indexOf(selectedCats[j]) > -1 ) {
            allTrs[i].style.display = "";
            break;
          } else {
            allTrs[i].style.display = "none";
          } // end if search
        } // end loop selectedCats
      }
    } // End if tdTitle
  } // End for allTrs.length
}

// ---------------------------------------------------------------------------

/* function to get all the question details */
function getAllQuestions( ) {
  var allQReq = new XMLHttpRequest();
  allQReq.onload = function () {
    // Parse the responce text
    var allQReqArray = JSON.parse( allQReq.responseText );

    // Now we write the values into the website
    var table = document.getElementById("allQTable");

    // If we have admin permission
    if( "privateQId" in allQReqArray ) {
      var privateQTimes = allQReqArray["privateQTimes"];
      var privateQIds = allQReqArray["privateQId"];
      var privateQTitle = allQReqArray["privateQTitle"];
      var privateQContent = allQReqArray["privateContent"];
      var privateQCat = allQReqArray["privateCat"];

      for( var i = 0 ; i < privateQTimes.length ; i++ ) {
        var tr = document.createElement('tr');
        tr.className = 'table-primary';

        // The time cell of the table
        var tdTime = document.createElement('td');
        var qTime = document.createTextNode( privateQTimes[i]);

        // The title cell of the table
        var tdTitle = document.createElement('td');
        var qTitle = document.createTextNode( privateQTitle[i] );
        // Create the category tag
        var category = document.createElement('h6');
        category.innerHTML = privateQCat[i];

        // The link to the details of the question
        var address = document.createElement('a')
        address.setAttribute("href", "q_details.html?id=" + privateQIds[i]);

        // The preview cell of the table
        var tdContent = document.createElement('td');

        var pContent = document.createElement('p');
        pContent.className = "previewQ";

        // The link to the details of the question
        var preview_address = document.createElement('a')
        preview_address.setAttribute("href",
                                     "q_details.html?id=" + privateQIds[i]);
        preview_address.appendChild(
                              document.createTextNode( privateQContent[i] ) );
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
      address.setAttribute("href", "q_details.html?id=" + qIds[i]);

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
  allQReq.open( "get", "getAllQ.php");

  allQReq.send();
}
/* End of getting all the question details */

// ---------------------------------------------------------------------------


/* Getting all the categories for users to choose from */
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
      input.setAttribute("name", allCats[i]);
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
}
/* End of getting all categories */

// ---------------------------------------------------------------------------

/* Funtion that searches through the displayed questions */
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
/* End of searchTitle() function */
