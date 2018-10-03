import React, { Component } from "react";
import NavMenu from "./nav-menu.js";
import SearchBar from "./search-bar.js";
import '../styles/App.css';

class App extends Component {

    /*var Table = React.createClass({
      getInitialState: function() {
        return {data: this.props.data};
      }
    })*/

    render() {
        return (
            <div className="middle">
                <div className="action">
                  <NavMenu />
                  <SearchBar />
                </div>
            </div>
        );
    }
}

export default App;
