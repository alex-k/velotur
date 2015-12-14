<?
require_once("../../config/init.php");

require_once("ajax.php");





$guide=new Guides();
$smarty->assign('Guides',$guide->searchFromDB());

$smarty->display("guide.html");


?>
