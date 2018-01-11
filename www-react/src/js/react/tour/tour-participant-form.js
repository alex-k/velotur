import React from 'react';
import {Field, reduxForm} from 'redux-form';

let TourParticipantForm = (props) => (
  <form className="tour-participant-form" onSubmit={props.handleSubmit}>

    {/* --- ДАННЫЕ УЧАСТНИКА --- */}
    <fieldset>
      <legend>Данные участника</legend>

      <label>Фамилия:
        <Field name="russianLastName" component="input" type="text" placeholder="Фамилия" />
        <span className="form-input-hint"> Русскими буквами: Петров </span>
      </label>

      <label>Имя:
        <Field name="russianFirstName" component="input" type="text" placeholder="Имя" />
        <span className="form-input-hint"> русскими буквами: Иван </span>
      </label>

      <label>Отчество:
        <Field name="russianMiddleName" component="input" type="text" placeholder="Отчество" />
        <span className="form-input-hint"> русскими буквами: Абрамович </span>
      </label>
      
      <label>Имя латиницей:
        <Field name="latinFirstName" component="input" type="text" placeholder="Имя латиницей" />
        <span className="form-input-hint"> как в загранпаспорте </span>
        <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : IVAN </span>
      </label>

      <label>Фамилия латиницей:
        <Field name="latinLastName" component="input" type="text" placeholder="Фамилия латиницей" />
        <span className="form-input-hint"> как в загранпаспорте </span>
        <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : PETROV </span>
      </label>

      <label>Дата рождения:
        <Field name="birthday" component="input" type="text" placeholder="Дата рождения" />
        <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>
      </label>

      <label>Гражданство:
        <Field name="citizenship" component="input" type="text" placeholder="Гражданство" />
        <span className="form-input-hint"> Россия </span>
      </label>

      <label>Пол:
        <Field component="select" name="sex">
          <option value="null">--</option>
          <option value="male">Мужской</option>
          <option value="femail">Женский</option>
        </Field>
      </label>

      <label>Город:
        <Field component="input" name="city" type="text" placeholder="Город" />
        <span className="form-input-hint"> Москва </span>
      </label>

    </fieldset>

    {/* --- ДАННЫЕ ЗАГРАНПАСПОРТА --- */}
    <fieldset>
      <legend>Данные загранпаспорта</legend>

      <label>Номер загранпаспорта:
        <Field component="input" name="passportNumber" type="text" placeholder="Номер загранпаспорта" />
        <span className="form-input-hint"> значок "№" не ставится : 70 1234567 </span>
      </label>

      <label>
        <Field component="input" name="hasNoPassport" type="checkbox" value="hasNoPassport" />
        Я не являюсь гражданином России либо не имею загранпаспорта по иной причине.
      </label>

      <label>Кем выдан:
        <Field component="input" name="passportIssuedBy" type="text" placeholder="Кем выдан" />
        <span className="form-input-hint"> все буквы БОЛЬШИЕ: РУВД 257 </span>	
      </label>

      <label>Когда выдан:
        <Field component="input" name="passportIssuedDate" type="text" placeholder="Когда выдан" />
        <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
      </label>

      <label>Действителен до:
        <Field component="input" name="passportValidThrough" type="text" placeholder="Действителен до" />
        <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
      </label>

    </fieldset>

    {/* --- ВАЖНАЯ ИНФОРМАЦИЯ --- */}
    <fieldset>
      <legend>Важная информация</legend>

      <label>Контактный телефон:
        <Field component="input" name="phone" type="text" placeholder="Контактный телефон" />
        <span className="form-input-hint"> полностью с кодами : +7 901 313 59 66 </span>
      </label>

      <label>Номер карты ВелоПитера (если имеется):	
        <Field component="input" name="vpNumber" type="text" placeholder="Номер карты велопитера" />
      </label>

      <label>Как вы нас нашли:
        <Field component="select" name="howFound">
          <option value="null">--</option>
          <option value="Давно уже знаю">Давно уже знаю</option>
          <option value="Нашел поиском в интернете">Нашел поиском в интернете</option>
          <option value="Друзья рассказали">Друзья рассказали</option>
          <option value="Пришел по линкам с других сайтов">Пришел по линкам с других сайтов</option>
          <option value="">Иное (написать что)</option>
        </Field>
        <Field component="input" name="howFoundText" type="text" />
      </label>

    </fieldset>

    {/* --- ДОПОЛНИТЕЛЬНЫЕ СВЕДЕНИЯ--- */}
    <fieldset>
      <legend>Дополнительные сведения</legend>

      <label>Ваши пожелания
        <Field component="textarea" name="comments" placeholder="Ваши пожелания" />
      </label>

    </fieldset>

    <input type="submit" />
    
  </form>
);

export default reduxForm({
  form: "tour-participant-form"
})(TourParticipantForm)
