<?
require_once("../config/init.php");
$smarty->assign('d_apply',getApplyStatus());

$t=new Tours((int)$_POST['tourID']);
$t->loadLinkedFromDB();

 function so ($a, $b) { 
	 $ccmp=array('apply'=>'a','WL'=>'b','completed'=>'c','deleted'=>'d');
	 $r=(strcmp($ccmp[$a->tourUserType],$ccmp[$b->tourUserType]));    
	 if ($r===0) $r=strcmp ($a->userRussianName,$b->userRussianName);
	 return $r;
	 }

 function fo ($a) {
	 return in_array($a->tourUserType, array('apply','completed'));
 }

if (is_array($t->Users))  {
	$t->Users=array_filter($t->Users,'fo');
	uasort($t->Users, 'so');
}


$smarty->assign($t->getValues());


$smarty->assign('title',sprintf("- информация по походу %s (%s - %s) ", $t->tourTitle, $t->tourStartDate, $t->tourEndDate));

$smarty->display("tourinfo.html");
?>
