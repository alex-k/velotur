import React from "react";
import {Field, reduxForm} from "redux-form";

let LoginForm = (props) => (
  <form className="login-form" onSubmit={props.handleSubmit}>
    <ul>
      <li>
        <Field name="email" component="input" type="email" placeholder="Email" />
      </li>
      <li>
        <Field name="password" component="input" type="password" placeholder="Пароль" />
      </li>
      <li>
        <a href="password.php"> Напомнить пароль </a>
      </li>

      <button type="submit"> Вход </button>

    </ul>
  </form>
);

export default reduxForm({
  form: "login-form"
})(LoginForm);