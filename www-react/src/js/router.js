import React from 'react';
import {BrowserRouter, HashRouter, Route, Switch} from 'react-router-dom';
import TourParticipantFormContainer from 'containers/tour-participant-form';
import UserFormContainer from 'containers/user-form';

let Router = (props) =>
    <HashRouter>
      <Switch> 
        <Route path="/tourParticipantForm" render={ () => <TourParticipantFormContainer {...props} /> } />
        <Route path="/userRegistrationForm" render={ () => <UserFormContainer {...props} /> } />
      </Switch>
    </HashRouter>

export default Router