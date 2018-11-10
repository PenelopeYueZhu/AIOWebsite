import React, { Component } from "react";
//import '../styles/Nav.css';
import SideBarItem from './side-bar-item.js';

class NavMenu extends Component {

  constructor( props ) {
    super( props );

    this.state = { signed_in: 0,
                   level: 3,
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
                level: userDataArray[2],
                name:  userDataArray[1] };
      });
    }

    userDataReq.open( "get", "checkSignedIn.php" );

    userDataReq.send();
  }

  render() {
    // All the constants for all the options
    const viewReplyHistory = "View your reply history";
    const viewQuestionHistory = "View your question history";
    const signIn = 'Sign in';
    const signOut = "Sign out";
    const createAccount = "Create a new account";
    const viewProfile = "Update your profile";
    const manage = "Manager all users";

    // Prompt the user to sign in or to show their account
    let normal_option_1 = <SideBarItem name={signIn} address="signin.html"/>
    let normal_option_2 = <SideBarItem name={createAccount} address="signup.html"/>;
    if( this.state.signed_in && this.state.level != 0) { // non-admin access
      normal_option_1 = <SideBarItem name={viewQuestionHistory} address="viewUserQ.html"/>
      normal_option_2 = <SideBarItem name={signOut} address="signout.php"/>;
    }
    else if ( this.state.signed_in && this.state.level == 0 ){ // Admin access
      normal_option_1 = <SideBarItem name={manage} address="manage.html"/>
      normal_option_2 = <SideBarItem name={signOut} address="signout.php"/>;
    }

    return (
      <nav className="navbar navbar-expand-sm bg-dark navbar-dark">
      <ul className="navbar-nav">
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
            Hello, {this.state.name}
          </a>
          <div className="dropdown-menu">
            {normal_option_1}
            {normal_option_2}
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
