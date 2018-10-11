import React from "react";
import { render } from "react-dom";
import { BrowserRouter } from 'react-router-dom';
import App from "./components/App.js";

render( //(<BrowserRouter> <App /> </BrowserRouter>) ,
          <App />,
                document.getElementById("middle"));
