<?
require_once("../../config/init.php");
require_once "auth.php";
$smarty->assign('d_tourstatus',getTourStatus());
$year=is_numeric($_POST['year']) ? $_POST['year'] : date('Y');
$year.='-01-01';
$tour=new Tours();
$options=array();
if (!is_numeric($_POST['year'])) $options['strings_OR']['tourStatus']='normal';
$options['strings_OR'][2][]=array('field'=>'tourStartDate','eq'=>'>=','value'=>date('Y-01-01',strtotime($year)));
$options['strings_OR'][2][]=array('field'=>'tourStartDate','eq'=>'<','value'=>date('Y-01-01',strtotime("$year +1 year")));
$_SQLDEBUG=0;
if($guide->guideType!=='admin') {
	$options['strings_OR']['guideID']=$guide->getID();
	$options['strings_OR']['guideID2']=$guide->getID();
}
$tours=$tour->searchFromDB($options);
function so ($a, $b) { 
	 $ccmp=array('normal'=>'a','closed'=>'b','hidden'=>'c');
	 $r=(strcmp($ccmp[$a[tourStatus]],$ccmp[$b[tourStatus]]));    
	 if ($r===0) $r=strcmp ($a[tourStartDate],$b[tourStartDate]);
	 return $r;
	 }


if (is_array($tours)) uasort($tours, 'so');

$smarty->assign('Tours',$tours);
$smarty->assign('TourAllYears',$tour->getAllYears());

$smarty->display("tour.html");


?>
