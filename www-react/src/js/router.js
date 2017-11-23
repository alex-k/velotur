import React from 'react';
import {BrowserRouter, Route} from 'react-router-dom';
import TopComponent from 'components/top-component';

let Router = (props) =>
    <BrowserRouter>
      <Route path="/" render={ () => <TopComponent {...props} /> } />
    </BrowserRouter>

export default Router