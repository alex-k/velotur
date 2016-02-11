<?
require_once("../../config/init.php");
require_once "auth.php";


$smarty->assign('d_tourdifficulty',getTripDifficulty());
$smarty->assign('d_tourcomfort',getTripComfort());
$smarty->assign('d_tourstatus',getTourStatus());
$smarty->assign('d_tourPlacesGender',getPlacesGender());
$g=new Guides();
$smarty->assign('Guides',$g->getHTMLSelect('guideName'));

loadclass('RentabikeSet');
$rbs=new RentabikeSet();
$smarty->assign('rentabikeSet',$rbs->getHTMLSelect('name'));


$tour=new Tours((int)$_POST['_id']);
if (is_numeric($_POST['tripID'])) {
	$trip=new Trips((int)$_POST['tripID']);
	$v=$trip->getValues();
	$tv=$tour->getValues();
	foreach ($v as $k=>$r) {
		$tk=str_replace('trip','tour',$k);
		if ($tk!='tourID' && array_key_exists($tk,$tour->_EQUALS)) {
			$tour->$tk=$r;
		}
	}
}

$smarty->assign($tour->getValues());

$smarty->assign($_POST);
$output=$smarty->fetch("tour_edit.html");

if (!empty($_POST) && !empty($_POST[formSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	include("_default.action.php");
	exit();
}



$smarty->display("tour_edit.html");


?>
