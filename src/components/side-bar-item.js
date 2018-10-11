import React from 'react';
import '../styles/side-bar-item.css';


const SideBarItem = ( props ) => {

    // Get the page and display name passed in by parent
    const dest_address = props.address;
    const dest_name = props.name;

  return (
    <a className="dropdown-item" href={dest_address}>{dest_name}</a>
  );
};
export default SideBarItem;
