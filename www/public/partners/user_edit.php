<?
require_once("../../config/init.php");
require_once "auth.php";

$smarty->assign('d_userSex',getUserSex());
$smarty->assign('d_userType',getUserType());
$user=new Users((int)$_POST['_id']);

if ($user->userPartnerID!=$partner->getID()) throw new Exception ('userPartnerID not valid');

$smarty->assign($user->getValues());

$smarty->assign($_POST);
$output=$smarty->fetch("user_edit.html");

if (!empty($_POST) && !empty($_POST[formSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	include("_default.action.php");
	exit();
}



$smarty->display("user_edit.html");


?>
