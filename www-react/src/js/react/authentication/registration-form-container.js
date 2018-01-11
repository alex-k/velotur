import React from "react";
import RegistrationForm from "./registration-form";

class LoginFormContainer extends React.Component {
  submit(formData) {
    console.log("Submitting data: ", formData);

    const URL = config.api.url + '/register';

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
      <RegistrationForm onSubmit={ this.submit }/>
    );
  }
}