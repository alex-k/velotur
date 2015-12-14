<?
require_once("../config/init.php");



if ($_POST['tourID']) $_SESSION['tourID']=$_POST['tourID'];
if ($_POST['type']) $_SESSION['type']=$_POST['type'];


$_reg_tourID=$_SESSION['tourID'];
$_reg_type=$_SESSION['type'];
	$tour=new Tours($_reg_tourID);
	$tour->loadLinkedFromDB();
	$smarty->assign('Tour',$tour->getValues());
	$smarty->assign('type',$_reg_type);


$smarty->assign($_POST);
include ("auth.php");

$smarty->assign('message',$message);

$smarty->display('apply_ok.html');
?>
