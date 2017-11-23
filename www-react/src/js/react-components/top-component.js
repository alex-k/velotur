import React from 'react';
import {Route} from 'react-router-dom';
import TourParticipantForm from 'components/tour-participant-form';

const TopComponent = (props) => 
  <div id="top-component">
    <Route path="/" render={ () => <TourParticipantForm {...props}/> } />
  </div>

export default TopComponent