import React, { Component } from "react";
import NavMenu from "./nav-menu.js";
import SearchBar from "./search-bar.js";
import SideBar from "./side-bar.js";
import '../styles/App.css';

class App extends Component {
    render() {
        return (
          <div className="all-navs">
            <div className="action">
              <NavMenu signed_in={window.user_signed_in}
                       user_level={window.user_level}
                       name={window.user_name}/>
            </div>
          </div>
        );
    }
}

export default App;
