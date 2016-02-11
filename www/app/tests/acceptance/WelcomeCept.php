<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/'); 
$I->see('velotur.ru');
$I->see('Календарь велопоходов');
$I->seeLink('Записаться в лист ожидания','/apply.php?tourID=310');
