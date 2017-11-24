import React from 'react';
import axios from 'axios';
import config from 'config';

class TourParticipantForm extends React.Component {

  render() {
    return (
      <form className="tour-participant-form">

        {/* --- ДАННЫЕ УЧАСТНИКА --- */}
        <fieldset>
          <legend>Данные участника</legend>

          <label>Фамилия:
            <input name="russianLastName" type="text" />
            <span className="form-input-hint"> Русскими буквами: Петров </span>
          </label>

          <label>Имя:
            <input name="russianFirstName" type="text" />
            <span className="form-input-hint"> русскими буквами: Иван </span>
          </label>

          <label>Отчество:
            <input name="russianMiddleName" type="text" />
            <span className="form-input-hint"> русскими буквами: Абрамович </span>
          </label>
          
          <label>Имя латиницей:
            <input name="latinFirstName" type="text" />
            <span className="form-input-hint"> как в загранпаспорте </span>
            <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : PETROV </span>
          </label>

          <label>Фамилия латиницей:
            <input name="latinLastName" type="text" />
            <span className="form-input-hint"> как в загранпаспорте </span>
            <span className="form-input-hint">БОЛЬШИМИ БУКВАМИ : IVAN </span>
          </label>

          <label>Дата рождения:
            <input name="birthday" type="text" />
            <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>
          </label>

          <label>Гражданство:
            <input name="citizenship" type="text" />
            <span className="form-input-hint"> Россия </span>
          </label>

          <label>Пол:
            <select name="sex" defaultValue="null">
              <option value="null">--</option>
              <option value="male">Мужской</option>
              <option value="femail">Женский</option>
            </select>
          </label>

          <label>Город:
            <input name="city" type="text" />
            <span className="form-input-hint"> Москва </span>
          </label>

        </fieldset>

        {/* --- ДАННЫЕ ЗАГРАНПАСПОРТА --- */}
        <fieldset>
          <legend>Данные загранпаспорта</legend>

          <label>Номер загранпаспорта:
            <input name="passportNumber" type="text" />
            <span className="form-input-hint"> значок "№" не ставится : 70 1234567 </span>
          </label>

          <label>
            <input name="hasNoPassport" type="checkbox" value="hasNoPassport" />
            Я не являюсь гражданином России либо не имею загранпаспорта по иной причине.
          </label>

          <label>Кем выдан:
            <input name="passportIssuedBy" type="text" />
            <span className="form-input-hint"> все буквы БОЛЬШИЕ: РУВД 257 </span>	
          </label>

          <label>Когда выдан:
            <input name="passportIssuedDate" type="text" />
            <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
          </label>

          <label>Действителен до:
            <input name="passportValidThrough" type="text" />
            <span className="form-input-hint"> День-Месяц-Год c дефисами : 31-12-1981 </span>	
          </label>

        </fieldset>

        {/* --- ВАЖНАЯ ИНФОРМАЦИЯ --- */}
        <fieldset>
          <legend>Важная информация</legend>

          <label>Контактный телефон:
            <input name="phone" type="text" />
            <span className="form-input-hint"> полностью с кодами : +7 901 313 59 66 </span>
          </label>

          <label>Номер карты ВелоПитера (если имеется):	
            <input name="vpNumber" type="text" />
          </label>

          <label>Как вы нас нашли:
            <select name="howFound" defaultValue="null">
              <option value="null">--</option>
              <option value="Давно уже знаю">Давно уже знаю</option>
              <option value="Нашел поиском в интернете">Нашел поиском в интернете</option>
              <option value="Друзья рассказали">Друзья рассказали</option>
              <option value="Пришел по линкам с других сайтов">Пришел по линкам с других сайтов</option>
              <option value="">Иное (написать что)</option>
            </select>
            <input name="howFoundText" type="text" />
          </label>

        </fieldset>

        {/* --- ДОПОЛНИТЕЛЬНЫЕ СВЕДЕНИЯ--- */}
        <fieldset>
          <legend>Дополнительные сведения</legend>

          <label>Ваши пожелания
            <textarea name="comments" />
          </label>

        </fieldset>

        <input type="submit" />
        
      </form>
    );
  }
}

export default TourParticipantForm