import React from "react";
import {Field, reduxForm} from "redux-form";

let RegistrationForm = (props) => (
  <form className="registration-form" onSubmit={props.handleSubmit}>
    <ul>

      <li>
        <Field 
          name="email" 
          component="input" 
          type="email" 
          placeholder="Email"
          className={" " + ((props.isEmailValid) ? "" : "invalid")}
        />
      </li>

      <li>
        <Field 
          name="password" 
          component="input" 
          type="password" 
          placeholder="Выберите пароль" 
          className={" " + ((props.isPasswordValid) ? "" : "invalid")}
        />
      </li>

      <li>
        <Field 
          name="confirmPassword" 
          component="input" 
          type="password" 
          placeholder="Подтвердите пароль" 
          className={" " + ((props.isPasswordValid) ? "" : "invalid")}
        />
      </li>
  
      <button type="submit"> Зарегистрироватся </button>
    
    </ul>
  </form>
);

export default reduxForm({
  form: "registration-form"
})(RegistrationForm);