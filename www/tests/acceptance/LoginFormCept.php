<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Apply on the WL for tour 310');


$I->amOnPage('/apply.php?tourID=310');

$I->see('Вы собираетесь подать заявку в ЛИСТ ОЖИДАНИЯ на поход Лыжи: По монастырям Русского севера запланированного на даты 05.01.2016-09.01.2016');
$I->seeElement('#formLoginNewUser');
$I->seeElement('#formLogin #formLoginSubmit');

$I->fillField('#formLoginEmail','alex@kochetov.com');
$I->fillField('#formLoginPassword','wrongpassword');
$I->click('#formLoginSubmit');

$I->canSee('неверный email или пароль');



$I->checkOption('#formLoginNewUser');
$I->fillField('#formLoginEmail','alex@kochetov.com');
$I->fillField('#formLoginPassword','wrongpassword');

$I->click('#formLoginSubmit');
$I->canSee('данный e-mail уже зарегистрирован');

