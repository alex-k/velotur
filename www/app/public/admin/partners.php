<?
require_once("../../config/init.php");
require_once "auth.php";

$guideo=new Partners();
$smarty->assign('Partners',$guideo->searchFromDB());

$smarty->display("partners.html");


?>
