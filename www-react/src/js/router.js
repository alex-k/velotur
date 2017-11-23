import React from 'react';
import {BrowserRouter, Route} from 'react-router-dom';
import TopComponent from 'components/top-component';

let Router = () =>
    <BrowserRouter>
      <Route path='/' component={TopComponent} />
    </BrowserRouter>

export default Router