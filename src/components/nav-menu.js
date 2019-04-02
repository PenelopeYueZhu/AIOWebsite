import React, { Component } from "react";
//import '../styles/Nav.css';
import SideBarItem from './side-bar-item.js';

class NavMenu extends Component {

  constructor( props ) {
    super( props );

    this.state = { signed_in: 2,
                   name: 'guest' };
    //console.log( this.state.name );

    // Default value for react is not signed in
    var component = this;

    var userDataReq = new XMLHttpRequest(); // Request the data from signin script
    userDataReq.onload = function () {
      // Parse the responce text
      var userDataArray = JSON.parse( userDataReq.responseText );
      //console.log( userDataArray[1]);

      // Update the component so we rerender with fetched username and
      // Permission level
      component.setState(() => {
        return {signed_in: userDataArray[0],
                name:  userDataArray[1] };
      });
    }

    userDataReq.open( "get", "checkSignedIn.php" );

    userDataReq.send();
  }

  render() {
    // All the constants for all the options
    const signIn = 'Sign in';
    const signOut = "Sign out";
    const manage = "Manager all users";

    // Prompt the user to sign in or to show their account
    let user_option = <SideBarItem name={signIn} address="signin.html"/>;
    if( this.state.signed_in < 2 ) { // signed in access
      user_option = <SideBarItem name={signOut} address="signout.php"/>;
    }
    else user_option = <SideBarItem name={signIn} address="signin.html"/>;

    return (
      <nav className="navbar navbar-expand-sm bg-dark navbar-dark">
      <ul className="navbar-nav">
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
            Hello, {this.state.name}
          </a>
          <div className="dropdown-menu">
            {user_option}
          </div>
        </li>
        <li className="nav-item">
          <a className="nav-link"
             href="http://academicintegrity.ucsd.edu/about/index.html" >
          Get To Know Us
          </a>
        </li>
        <li className="nav-item">
          <a className="nav-link"
             href="viewAllQ.html">Browse All Questions</a>
        </li>
        <li className="nav-item">
          <a className="nav-link"
             href="askQuestion.php">
              Ask Us A Question
          </a>
        </li>
        <li className="nav-item">
          <a className="nav-link"
             href="http://academicintegrity.ucsd.edu/take-action/report-cheating/index.html">
          Report Cheating
          </a>
        </li>
        <li className="nav-item">
          <a className="nav-link"
             href="http://academicintegrity.ucsd.edu/events/index.html">
          Upcoming Events
          </a>
        </li>
        <li className="nav-item">
          <a className="nav-link"
             href="http://academicintegrity.ucsd.edu/contact/index.html">
          Contact Us
          </a>
        </li>
      </ul>
      </nav>
    );
  }
}

export default NavMenu;
