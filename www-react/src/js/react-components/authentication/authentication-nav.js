import React from "react";

import css from "css/authentication/authentication-nav.css";

let AuthenticationNav = (props) => (
  <nav className="main-nav">
    <ul>
      <li>
        <a href="" 
          className={"tab " + ((props.mode === "login") ? "selected" : "")}
          onClick={(event) => { 
              event.preventDefault(); 
              props.handleModeChange("login")
            }
          }
        >
          Вход
        </a>
      </li>
      <li>
        <a href=""
          className={"tab " + ((props.mode === "register") ? "selected" : "")}
          onClick={(event) => {
              event.preventDefault();
              props.handleModeChange("register")
            } 
          }
        >
          Регистрация
        </a>
      </li>
    </ul>
  </nav>
);

export default AuthenticationNav;