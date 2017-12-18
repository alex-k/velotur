import React from 'react';
import UserForm from 'components/user-form';
import axios from 'axios';
import config from 'config';

class UserFormContainer extends React.Component {
  constructor(props) {
    super(props);
  }

  submit(data) {
    console.log("Submitting data: ", data);

    axios({
      method: 'POST',
      url: config.api.url + '/submitUser',
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
      <UserForm onSubmit={this.submit} />
    );
  }
}

export default UserFormContainer