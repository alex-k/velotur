<?
require_once("../../config/init.php");
require_once "auth.php";

loadclass("Partners");

$partnero=new Partners((int)$_POST['_id']);
$smarty->assign($partnero->getValues());

$smarty->assign($_POST);
$output=$smarty->fetch("partner_edit.html");

if (!empty($_POST) && !empty($_POST[formSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	include("_default.action.php");
	exit();
}



$smarty->display("partner_edit.html");


?>
