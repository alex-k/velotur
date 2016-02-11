<?
require_once("../../config/init.php");
require_once "auth.php";

$trip=new Trips();
$options=array(array('field'=>'tripTitle','eq'=>'ORDERBY','value'=>'tripTitle'));
$smarty->assign('Trips',$trip->searchFromDB($options));

$smarty->display("trip.html");


?>
