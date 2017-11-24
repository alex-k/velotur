import React from 'react';
import {BrowserRouter, Route, Switch} from 'react-router-dom';
import TourParticipantFormContainer from 'containers/tour-participant-form';

let Router = (props) =>
    <BrowserRouter>
      <Switch>
        <Route path="/" render={ () => <TourParticipantFormContainer {...props} /> } />
      </Switch>
    </BrowserRouter>

export default Router