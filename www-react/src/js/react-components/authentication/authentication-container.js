import React from "react";
import AuthenticationNav from "./authentication-nav";
import LoginFormContainer from "./login-form-container";
import RegistrationFormContainer from "./registration-form-container";

import css from "css/authentication/authentication-container.css";

class AuthenticationContainer extends React.Component {
  constructor(props) {
    super(props);
    
    this.state = {
      mode: "login"
    }
    
    this.handleModeChange = this.handleModeChange.bind(this);
  }
  
  handleModeChange(mode) {
    this.setState({ mode: mode });
  } 
  
  render() {
    
    return (
      <div className="authentication-component">
      
        <AuthenticationNav 
          mode={this.state.mode} 
          handleModeChange={this.handleModeChange}
        />
        
        <div>
          { 
            (this.state.mode === "login") ?
              <LoginFormContainer /> :
              <RegistrationFormContainer />
          }
        </div>
          
      </div>
    );
  }
}

export default AuthenticationContainer;