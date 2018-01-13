import React from "react";
import {Field, reduxForm} from "redux-form";

let UserForm = (props) => (
  <form className="user-form" onSubmit={props.handleSubmit}>

    {/* --- ДАННЫЕ УЧАСТНИКА --- */}
    <fieldset>
      <legend>Данные участника</legend>
      <ul>

        <li>Фамилия:
          <Field name="russianLastName" component="input" type="text" placeholder="Фамилия" />
          <span className="form-input-hint"> Русскими буквами: Петров </span>
        </li>

        <li>Имя:
          <Field name="russianFirstName" component="input" type="text" placeholder="Имя" />
          <span className="form-input-hint"> русскими буквами: Иван </span>
        </li>

        <li>Отчество:
          <Field name="russianMiddleName" component="input" type="text" placeholder="Отчество" />
          <span className="form-input-hint"> русскими буквами: Абрамович </span>
        </li>
        
        <li>Имя латиницей:
          <Field name="latinFirstName" component="input" type="text" placeholder="Имя латиницей" />
          <span className="form-input-hint"> как в загранпаспорте </span>
          <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : IVAN </span>
        </li>

        <li>Фамилия латиницей:
          <Field name="latinLastName" component="input" type="text" placeholder="Фамилия латиницей" />
          <span className="form-input-hint"> как в загранпаспорте </span>
          <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : PETROV </span>
        </li>

        <li>Дата рождения:
          <Field name="birthday" component="input" type="text" placeholder="Дата рождения" />
          <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>
        </li>

        <li>Гражданство:
          <Field name="citizenship" component="input" type="text" placeholder="Гражданство" />
          <span className="form-input-hint"> Россия </span>
        </li>

        <li>Пол:
          <Field component="select" name="sex">
            <option value="null">--</option>
            <option value="male">Мужской</option>
            <option value="femail">Женский</option>
          </Field>
        </li>

        <li>Город:
          <Field component="input" name="city" type="text" placeholder="Город" />
          <span className="form-input-hint"> Москва </span>
        </li>
      
      </ul>
    </fieldset>

    {/* --- ДАННЫЕ ЗАГРАНПАСПОРТА --- */}
    <fieldset>
      <legend>Данные загранпаспорта</legend>
      <ul>

        <li>Номер загранпаспорта:
          <Field component="input" name="passportNumber" type="text" placeholder="Номер загранпаспорта" />
          <span className="form-input-hint"> значок "№" не ставится : 70 1234567 </span>
        </li>

        <li>
          <Field component="input" name="hasNoPassport" type="checkbox" value="hasNoPassport" />
          Я не являюсь гражданином России либо не имею загранпаспорта по иной причине.
        </li>

        <li>Кем выдан:
          <Field component="input" name="passportIssuedBy" type="text" placeholder="Кем выдан" />
          <span className="form-input-hint"> все буквы БОЛЬШИЕ: РУВД 257 </span>	
        </li>

        <li>Когда выдан:
          <Field component="input" name="passportIssuedDate" type="text" placeholder="Когда выдан" />
          <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
        </li>

        <li>Действителен до:
          <Field component="input" name="passportValidThrough" type="text" placeholder="Действителен до" />
          <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
        </li>

      </ul>
    </fieldset>

    {/* --- ВАЖНАЯ ИНФОРМАЦИЯ --- */}
    <fieldset>
      <legend>Важная информация</legend>
      <ul>

        <li>Контактный телефон:
          <Field component="input" name="phone" type="text" placeholder="Контактный телефон" />
          <span className="form-input-hint"> полностью с кодами : +7 901 313 59 66 </span>
        </li>

        <li>Номер карты ВелоПитера (если имеется):	
          <Field component="input" name="vpNumber" type="text" placeholder="Номер карты велопитера" />
        </li>

        <li>Как вы нас нашли:
          <Field component="select" name="howFound">
            <option value="null">--</option>
            <option value="Давно уже знаю">Давно уже знаю</option>
            <option value="Нашел поиском в интернете">Нашел поиском в интернете</option>
            <option value="Друзья рассказали">Друзья рассказали</option>
            <option value="Пришел по линкам с других сайтов">Пришел по линкам с других сайтов</option>
            <option value="">Иное (написать что)</option>
          </Field>
          <Field component="input" name="howFoundText" type="text" />
        </li>

      </ul>
    </fieldset>
    
    <input type="submit" />
    
  </form>
);

export default reduxForm({
  form: "user-form"
})(UserForm)