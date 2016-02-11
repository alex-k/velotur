<?
$smarty->assign('d_tourstatus',getTourStatus());
$smarty->assign('d_tourdifficulty',getTripDifficulty());
$smarty->assign('d_tourcomfort',getTripComfort());
$tour=new Tours();
$smarty->assign('TourAllYears',$tour->getAllFutureYears());

$options=array();
if (is_numeric($_POST['year'])) {
	$year=(int)($_POST['year']);
	//$options['strings_OR'][]=array('field'=>'tourStartDate','eq'=>'STRONGLIKE','value'=>"$year%%");
	$options['strings_OR'][]=array('field'=>'tourStartDate','eq'=>'STRONGLIKE','value'=>"$year%%");
	$options['strings_OR'][]=array('field'=>'tourEndDate','eq'=>'STRONGLIKE','value'=>"$year%%");
} else {
	//$year=date("Y");
	//if (date("m")>8) $year++;
	$options[]=array('field'=>'tourStartDate','eq'=>'>=','value'=>date('Y-m-d H:i:s',strtotime('-1 week')));
	$options[]=array('field'=>'tourStartDate','eq'=>'<=','value'=>date('Y-m-d H:i:s',strtotime('+1 year')));
}
$smarty->assign('year',$year);

$t=new Tours();
$options[]=array('field'=>'tourStatus','eq'=>'!=','value'=>'hidden');
$options[]=array('field'=>'tourStartDate','eq'=>'ORDERBY','value'=>'tourStartDate');
$tours=$t->searchObjectsFromDB($options);
if(is_array($tours)) foreach ($tours as $t) {
	$t->loadLinkedFromDB();
	$toursv[]=$t->getValues();
}
$smarty->assign('Tours',$toursv);
?>
