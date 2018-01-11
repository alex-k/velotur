import {Field, reduxForm} from 'redux-form';

let RegistrationForm = (props) => (
  <form className="registration-form" onSubmit={props.handleSubmit}>

    <label>Email:
      <Field name="email" component="input" type="email">
    </label>

    <label>Пароль:
      <Field name="password" component="input" type="password">
    </label>

    <label>Подтвердите пароль:
      <Field name="confirm-password" component="input" type="password">
    </label>
  
    <input type="submit" />

  </form>
);

export default reduxForm({
  form: "registration-form"
})(RegistrationForm);