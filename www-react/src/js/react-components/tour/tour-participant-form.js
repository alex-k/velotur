import React from "react";
import {Field, reduxForm} from "redux-form";

let TourParticipantForm = (props) => (
  <form className="tour-participant-form" onSubmit={props.handleSubmit}>

    <h1> Данные участника тура </h1>

    {/* --- ДАННЫЕ УЧАСТНИКА --- */}
    <fieldset>
      <legend>Данные участника</legend>
      <ul>

        <li>
          <Field name="russianLastName" component="input" type="text" placeholder="Фамилия" />
          <span className="form-input-hint"> Русскими буквами: Петров </span>
        </li>

        <li>
          <Field name="russianFirstName" component="input" type="text" placeholder="Имя" />
          <span className="form-input-hint"> русскими буквами: Иван </span>
        </li>

        <li>
          <Field name="russianMiddleName" component="input" type="text" placeholder="Отчество" />
          <span className="form-input-hint"> русскими буквами: Абрамович </span>
        </li>
        
        <li>
          <Field name="latinFirstName" component="input" type="text" placeholder="Имя латиницей" />
          <span className="form-input-hint"> как в загранпаспорте </span>
          <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : IVAN </span>
        </li>

        <li>
          <Field name="latinLastName" component="input" type="text" placeholder="Фамилия латиницей" />
          <span className="form-input-hint"> как в загранпаспорте </span>
          <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : PETROV </span>
        </li>

        <li>
          <Field name="birthday" component="input" type="text" placeholder="Дата рождения" />
          <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>
        </li>

        <li>
          <Field name="citizenship" component="input" type="text" placeholder="Гражданство" />
          <span className="form-input-hint"> Например: Россия </span>
        </li>

        <li>
          <label>Пол: 
            <label> 
              <Field component="input" type="radio" name="sex" value="male" />
              Мужской
            </label>
            <label> 
              <Field component="input" type="radio" name="sex" value="female" />
              Женский
            </label>
          </label>
        </li>

        <li>
          <Field component="input" name="city" type="text" placeholder="Город" />
          <span className="form-input-hint"> Например: Москва </span>
        </li>

      </ul>
    </fieldset>

    {/* --- ДАННЫЕ ЗАГРАНПАСПОРТА --- */}
    <fieldset>
      <legend>Данные загранпаспорта</legend>
      <ul>

        <li>
          <Field component="input" name="passportNumber" type="text" placeholder="Номер загранпаспорта" />
          <span className="form-input-hint"> значок "№" не ставится : 70 1234567 </span>
        </li>

        <li>
          <Field component="input" name="hasNoPassport" type="checkbox" value="hasNoPassport" />
          Я не являюсь гражданином России либо не имею загранпаспорта по иной причине.
        </li>

        <li>
          <Field component="input" name="passportIssuedBy" type="text" placeholder="Кем выдан" />
          <span className="form-input-hint"> все буквы БОЛЬШИЕ: РУВД 257 </span>	
        </li>

        <li>
          <Field component="input" name="passportIssuedDate" type="text" placeholder="Когда выдан" />
          <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
        </li>

        <li>
          <Field component="input" name="passportValidThrough" type="text" placeholder="Действителен до" />
          <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
        </li>
      </ul>
    </fieldset>

    {/* --- ВАЖНАЯ ИНФОРМАЦИЯ --- */}
    <fieldset>
      <legend>Важная информация</legend>
      <ul>

        <li>
          <Field component="input" name="phone" type="text" placeholder="Контактный телефон" />
          <span className="form-input-hint"> полностью с кодами : +7 901 313 59 66 </span>
        </li>

        <li>	
          <Field component="input" name="vpNumber" type="text" placeholder="Номер карты велопитера" />
        </li>

      </ul>
    </fieldset>

    {/* --- ДОПОЛНИТЕЛЬНЫЕ СВЕДЕНИЯ--- */}
    <fieldset>
      <legend>Дополнительные сведения</legend>

      <ul>

        <li>
          <Field component="textarea" name="comments" placeholder="Ваши пожелания" />
        </li>

      </ul>

    </fieldset>

    <button type="submit"> Сохранить </button> 
    
  </form>
);

export default reduxForm({
  form: "tour-participant-form"
})(TourParticipantForm);