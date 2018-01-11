import {Field, reduxForm} from 'redux-form';

let LoginForm = (props) => (
  <form className="login-form" onSubmit={props.handleSubmit}>
    
    <label>Email:
      <Field name="email" component="input" type="email">
    </label>

    <label>Пароль:
      <Field name="password" component="input" type="password">
    </label>

    <input type="submit" />

  </form>
);

export default reduxForm({
  form: "login-form"
})(LoginForm);