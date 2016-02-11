<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Apply on the WL for tour 304');


$I->amOnPage('/apply.php?tourID=304');


$I->fillField('#formLoginEmail','alex@kochetov.com');
$I->fillField('#formLoginPassword','123123');

$I->click('#formLoginSubmit');


$I->canSee('Вы собираетесь подать заявку');
$I->canSee('в СПИСОК ОЖИДАНИЯ');
$I->canSee('Замки Мозеля и Рейна');
$I->canSee('28.05.2016-05.06.2016');

$I->seeElement(['name'=>'formSubmit']);
$I->seeInSource('<input type=submit value="Подтверждаю" name=formSubmit class=input>');

