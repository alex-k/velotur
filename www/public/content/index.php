<?php
require "../../config/init.php";
include ("../auth.php");
$tplname=basename($_SERVER['REDIRECT_URL']) ? str_replace(dirname($_SERVER['SCRIPT_NAME']).'/','',$_SERVER['REDIRECT_URL']) : 'index.html';
$smarty->assign('tplname',$tplname);
$smarty->display($tplname);
?>
