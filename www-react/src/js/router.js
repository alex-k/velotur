import React from "react";
import {BrowserRouter, HashRouter, Route, Switch} from "react-router-dom";
import AuthenticationContainer from "components/authentication/authentication-container";
import TourParticipantFormContainer from "components/tour/tour-participant-form";
import UserFormContainer from "components/user/user-form";

let Router = (props) =>
    <HashRouter>
      <Switch> 
        <Route path="/login" render={ () => <AuthenticationContainer {...props} /> } />
        <Route path="/tourParticipantForm" render={ () => <TourParticipantFormContainer {...props} /> } />
        <Route path="/userRegistrationForm" render={ () => <UserFormContainer {...props} /> } />
      </Switch>
    </HashRouter>

export default Router;