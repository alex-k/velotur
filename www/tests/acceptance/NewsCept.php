<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('see news page without errors');

$I->amOnPage('/news/');
$I->dontSee('Fatal error');
