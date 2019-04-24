/* Function that signes user in */
function onSignIn(googleUser) {
	// Get the name for client side display
	var profile = googleUser.getBasicProfile();
	var name = profile.getName();
	// The ID token to pass to your backend:
	var id_token = googleUser.getAuthResponse().id_token;

	// Send the token to the backend for verification
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'SignUserIn.php');
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onload = function() {
		console.log( "response is " + xhr.responseText );
		var signedInSuccessful = JSON.parse( xhr.responseText );

		if( parseInt(signedInSuccessful) == 1 ) {
			// Replace the button with the new information
			$( "div.g-signin2" ).replaceWith( "<p>Welcome.</p>");
			console.log( signedInSuccessful );
		}
		else {
			// Now we know they didn't successfully sign in
			$( "div.g-signin2" ).replaceWith(
															"<p>You are not authorized yet.</p>");
			console.log( signedInSuccessful );
		}
	};
	xhr.send('idtoken=' + id_token);
}
