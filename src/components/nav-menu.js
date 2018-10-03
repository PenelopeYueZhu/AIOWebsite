import React, { Component } from "react";
import '../styles/Nav.css';

class NavMenu extends Component {
  render() {
    return (
      <nav>
      <ul className="nav-menu">
        <li>
          <a href="http://academicintegrity.ucsd.edu/about/index.html">
          Get To Know Us
          </a>
        </li>
        <li>
          <a href="view_all_q.php">Browse All Questions</a>
        </li>
        <li>
          <a href="askQuestion.php">Ask Us A Question</a>
        </li>
        <li>
          <a href="http://academicintegrity.ucsd.edu/take-action/report-cheating/index.html">
          Report Cheating
          </a>
        </li>
        <li>
          <a href="http://academicintegrity.ucsd.edu/events/index.html">
          Upcoming Events
          </a>
        </li>
        <li>
          <a href="http://academicintegrity.ucsd.edu/contact/index.html">
          Contact Us
          </a>
        </li>
      </ul>
      </nav>
    )
  }
}

export default NavMenu;
