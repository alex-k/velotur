<?
require_once("../../config/init.php");
require_once "auth.php";


$years=$DBCLASS->queryResult('select distinct date_format(tourStartDate,"%Y") as year from Tour where tourStartDate>"2000-01-01"',1);
$smarty->assign('years',$years);

$smarty->display("years.html");


?>
