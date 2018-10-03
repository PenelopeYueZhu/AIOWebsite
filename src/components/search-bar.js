/*
 * Tutorial Source:
 * https://dev.to/sage911/how-to-write-a-search-component-with-suggestions-in-react-d20
 */

import React, {Component} from 'react';

class SearchBar extends Component {

   constructor( props ) {
     super(props);

     this.state = {
       query: ''
     }
   }

   handleInputChange(){
     this.setState( {
       query: this.search.value
     })
   }

   render() {
     return (
       <form>
         <input
           placeholder="Search for..."
           ref={input => this.search = input }
           onChange={this.handleInputChange}
         />
       <p> {this.state.query} </p>
       </form>
     )
   }
}

export default SearchBar
