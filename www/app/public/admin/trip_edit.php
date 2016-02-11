<?
require_once("../../config/init.php");
require_once "auth.php";


$smarty->assign('d_tripdifficulty',getTripDifficulty());
$smarty->assign('d_tripcomfort',getTripComfort());


$trip=new Trips((int)$_POST['_id']);
$smarty->assign($trip->getValues());

$smarty->assign($_POST);
$output=$smarty->fetch("trip_edit.html");

if (!empty($_POST) && !empty($_POST[formSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	include("_default.action.php");
	exit();
}



$smarty->display("trip_edit.html");


?>
