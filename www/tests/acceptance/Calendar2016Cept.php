<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('See calendar for 2016');

$I->amOnPage('/calendar.php?year=2016');
$I->seeLink('Записаться в лист ожидания','/apply.php?tourID=310');
