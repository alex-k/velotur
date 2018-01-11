import React from 'react';
import TourParticipantForm from 'components/tour-participant-form';
import axios from 'axios';
import config from 'config';

class TourParticipantFormContainer extends React.Component {
  constructor(props) {
    super(props);
  }

  submit(formData) {
    console.log("Submitting data: ", formData);

    const URL = config.api.url + '/submitParticipant';

    axios({
      method: 'POST',
      url: URL,
      data: formData,
      headers: {
        'Content-Type': 'text/plain'
      }
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