<?
require_once("../../config/init.php");
require_once "auth.php";
$smarty->assign('d_tourstatus',getTourStatus());

$t=new Tours();
$options=array();
$options[]=array('field'=>'tourStartDate','eq'=>'STRONGLIKE','value'=>(int)($_POST['year'])."%%");
if (!isset($_POST['tourStatus'])) {
	$options[]=array('field'=>'tourStatus','eq'=>'!=','value'=>'hidden');
} else {
	$options['tourStatus']=$_POST['tourStatus'];
}
$options[]=array('field'=>'tourStartDate','eq'=>'ORDERBY','value'=>'tourStartDate');
$tours=$t->searchFromDB($options);
$smarty->assign('Tours',$tours);
$smarty->assign($_POST);



$smarty->assign('title',"- календарь ".(int)($_POST['year']));

$smarty->display("calendar.html");
?>
