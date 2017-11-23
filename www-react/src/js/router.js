import React from 'react';
import {BrowserRouter, Route, Switch} from 'react-router-dom';
import TopComponent from 'components/top-component';
import TourParticipantForm from 'components/tour-participant-form';

let Router = (props) =>
    <BrowserRouter>
      <Switch>
        <Route path="/" render={ () => <TourParticipantForm {...props} /> } />
      </Switch>
    </BrowserRouter>

export default Router