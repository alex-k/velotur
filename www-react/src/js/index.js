import React from "react";
import ReactDOM from "react-dom";
import {createStore} from "redux";
import {Provider} from "react-redux";
import mainReducer from "reducers/main-reducer";
import Router from "./router";

import normalizeCss from "css/normalize.css";
import formsCss from "css/forms.css";

let reduxStore = createStore(mainReducer);

ReactDOM.render(
  <Provider store={reduxStore}>
    <Router />
  </Provider>,
  document.getElementById('react-root')
);
