import React from "react";

class AuthenticationContainer extends React.Component {
  constructor(props) {
    super(props);
    this.state = { displayMode: "login" };

    this.setDisplayMode = this.setDisplayMode.bind(this);
  }

  setDisplayMode(newMode) {
    this.setState({ displayMode: newMode });
  }

  render() {
    return (
      <div class="authentication-container">

        {/* --- Toggle for changing between Login and Registration screens --- */}
        <div class="authentication-mode-toggle">
          <input type="radio" name="login-register-toggle" value="login" 
            onselect={ () => this.setDisplayMode("login") }
            checked
           >
          <input type="radio" name="login-register-toggle" value="register" 
            onselect={ () => this.setDisplayMode("register") }
          >
        </div>
      
        {/* --- Display Login or Registration screen depending on the mode --- */}
        { 
          (this.state.displayMode == "login") ?
            <LoginFormContainer /> :
            <RegistrationContainer />
        }

      </div>
    );
  }
}