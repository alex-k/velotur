<?
require_once("../../config/init.php");
require_once "auth.php";
$smarty->assign('d_tourstatus',getTourStatus());



$t=new Users((int)$_POST['userID']);
$t->loadLinkedFromDB();


$smarty->assign('u',$t->getValues());



if ($_POST['type']=='txt') {
	$smarty->display("userinfo_txt.html");
} else {
	$smarty->display("userinfo.html");
}
?>
