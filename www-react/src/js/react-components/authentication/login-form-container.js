import React from "react";
import LoginForm from "./login-form";

class LoginFormContainer extends React.Component {
  submit(formData) {
    console.log("Submitting data: ", formData);

    const URL = config.api.url + '/login';

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
      <LoginForm onSubmit={ this.submit } />
    );
  }
}

export default LoginFormContainer;