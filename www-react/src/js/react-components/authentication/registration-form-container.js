import React from "react";
import axios from "axios";
import config from "config";
import RegistrationForm from "./registration-form";
import validateEmail from "util/validate-email";

class RegistrationFormContainer extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
      isEmailValid: true,
      isPasswordValid: true
    }

    this.submit = this.submit.bind(this);
    this.validate = this.validate.bind(this);
  }

  /**
   * Submit the form data.
   * @param {Object} formData 
   */
  submit(formData) {
    const URL = config.api.url + '/register';

    if (this.validate(formData)) {
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
  }

  /**
   * Validate the form data.
   * @param {Object} formData
   * @returns {boolean} - True if valid, false if invalid.
   */
  validate(formData) {
    let isValid = true;

    // validate email format
    if (validateEmail(formData.email)) {
      this.setState({ isEmailValid: true });
    } else {
      this.setState({ isEmailValid: false });
      isValid = false;
    }

    // check if password matches confirm password 
    if (formData.password && formData.password.length > 0 && formData.password === formData.confirmPassword) {
      this.setState({ isPasswordValid: true });  
    } else {
      this.setState({ isPasswordValid: false });
      isValid = false;
    }

    return isValid;
  }

  render() {
    return (
      <div>
        <RegistrationForm 
          onSubmit={this.submit}
          isEmailValid={this.state.isEmailValid}
          isPasswordValid={this.state.isPasswordValid}
        />
        <div className="error-message">
          {
            (this.state.isEmailValid) ? "" :
              "Ошибка: некорректный формат email"
          }
        </div>
        <div className="error-message">
          {
            (this.state.isPasswordValid) ? "" :
              "Ошибка: некорректный формат пароля или пароли не совпадают"
          }
        </div>
      </div>
    );
  }
}

export default RegistrationFormContainer;