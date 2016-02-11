<?
require_once("../../config/init.php");
require_once "auth.php";

$guideo=new Guides();
$smarty->assign('Guides',$guideo->searchFromDB());

$smarty->display("guide.html");


?>
