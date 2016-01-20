<?
require_once("../../config/init.php");
require_once "auth.php";

$smarty->assign('d_userSex',getUserSex());

$guideo=new Guides((int)$_POST['_id']);
$smarty->assign($guideo->getValues());

$smarty->assign($_POST);
$output=$smarty->fetch("guide_edit.html");

if (!empty($_POST) && !empty($_POST[formSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	include("_default.action.php");
	exit();
}



$smarty->display("guide_edit.html");


?>
