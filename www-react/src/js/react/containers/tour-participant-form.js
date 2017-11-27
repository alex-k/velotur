import React from 'react';
import TourParticipantForm from 'components/tour-participant-form';
import axios from 'axios';
import config from 'config';

class TourParticipantFormContainer extends React.Component {
  constructor(props) {
    super(props);
  }

  submit(data) {
    console.log("Submitting data: ", data);

    axios({
      method: 'GET',
      url: config.api.url + '/submitParticipant',
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      data: data
    })
    .catch(error => {
      console.log("Caught server error: ", error);
    })
    .then(response => {
      console.log("Received server response: ", response);
    });
  }

  render() {
    return (
      <TourParticipantForm onSubmit={this.submit} />
    );
  }
}

export default TourParticipantFormContainer