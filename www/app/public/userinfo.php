<?
require_once("../config/init.php");
include ("auth.php");
include ("restricted.php");
if (!is_object($user) || !$user->exists()) {
	throw new MyException('Unknown user');
}

$smarty->assign('d_userSex',getUserSex());
$smarty->assign('d_userHowFound',getUserHowFound());
$smarty->assign($user->getValues());
$smarty->assign($_POST);


$output=$smarty->fetch("userinfo.html");


$smarty->assign('_validate_error',$smarty->_validate_error);

if (!empty($_POST) && !empty($_POST[formSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	$_POST['_classname']='Users';
	include("_default.action.php");
	exit();
}



$smarty->display("userinfo.html");
?>
