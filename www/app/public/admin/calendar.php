<?
require_once("../../config/init.php");
require_once "auth.php";
$smarty->assign('d_tourstatus',getTourStatus());

$t=new Tours();
$options=array();
$options['ORDERBY']=array('field'=>'tourStartDate','eq'=>'ORDERBY','value'=>'tourStartDate');

if (isset($_POST['year'])) $options[]=array('field'=>'tourStartDate','eq'=>'STRONGLIKE','value'=>(int)($_POST['year'])."%%");
if (isset($_POST['tripID'])) {
	$options[]=array('field'=>'tripID','eq'=>'=','value'=>(int)($_POST['tripID']));
	$options['ORDERBY']=array('field'=>'tourStartDate','eq'=>'ORDERBY','value'=>'tourStartDate desc');
}
if (!isset($_POST['tripID'])) {
	if (!isset($_POST['tourStatus'])) {
		$options[]=array('field'=>'tourStatus','eq'=>'!=','value'=>'hidden');
	} else {
		$options['tourStatus']=$_POST['tourStatus'];
	}
}

if($guide->guideType!=='admin') {
	$options['strings_OR']['guideID']=$guide->getID();
	$options['strings_OR']['guideID2']=$guide->getID();
}
$tours=$t->searchFromDB($options);
foreach ($tours as $k=>$t ) {
	$tw_tour=record_by_id($t['tourID'],'tw_tours');
	$tw_apply_users=$tw_tour->Users->find(array('tourUserType'=>'apply'))->array_keys();
	$pa=array();
	foreach ($tw_tour->Payments->find(array('Type'=>'оплата')) as $p) {
		if($pu=record_by_id($p->userID, 'tw_users') && in_array($p->userID, $tw_apply_users)) {
			$pa[$p->userID]=$p->userID;
		}
	}
	$t['completedUsersCount']=$tw_tour->Users->find(array('tourID'=>$tw_tour->get_id(),'tourUserType'=>'completed'))->count();
	$t['tourUsersPayed']=count($pa);
	$tours[$k]=$t;
}
$smarty->assign('Tours',$tours);
$smarty->assign($_POST);



$smarty->assign('title',"- календарь ".(int)($_POST['year']));

$smarty->display("calendar.html");
?>
