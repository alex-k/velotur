import React from "react";
import {Field, reduxForm} from "redux-form";

let RegistrationForm = (props) => (
  <form className="registration-form" onSubmit={props.handleSubmit}>
    <ul>

      <li>
        <Field name="email" component="input" type="email" placeholder="Email" />
      </li>

      <li>
        <Field name="password" component="input" type="password" placeholder="Выберите пароль" />
      </li>

      <li>
        <Field name="confirm-password" component="input" type="password" placeholder="Подтвердите пароль" />
      </li>
  
      <button type="submit"> Зарегистрироватся </button>
    
    </ul>
  </form>
);

export default reduxForm({
  form: "registration-form"
})(RegistrationForm);