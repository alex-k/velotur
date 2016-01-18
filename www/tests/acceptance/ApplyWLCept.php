<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Apply on the WL for tour 310');


$I->amOnPage('/apply.php?tourID=310');


$I->fillField('#formLoginEmail','alex@kochetov.com');
$I->fillField('#formLoginPassword','123123');

$I->click('#formLoginSubmit');


$I->canSee('Вы собираетесь подать заявку');
$I->canSee('в СПИСОК ОЖИДАНИЯ');
$I->canSee('Лыжи: По монастырям Русского севера');
$I->canSee('05.01.2016-09.01.2016');

$I->seeElement(['name'=>'formSubmit']);
$I->seeInSource('<input type=submit value="Подтверждаю" name=formSubmit class=input>');

