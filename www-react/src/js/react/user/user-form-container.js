import React from 'react';
import UserForm from 'components/user-form';
import axios from 'axios';
import config from 'config';

class UserFormContainer extends React.Component {
  constructor(props) {
    super(props);
  }

  submit(formData) {
    console.log("Submitting data: ", formData);

    const URL = config.api.url + '/submitUserRegistration';

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
      <UserForm onSubmit={this.submit} />
    );
  }
}

export default UserFormContainer