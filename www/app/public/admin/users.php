<?
require_once("../../config/init.php");
require_once "auth.php";


$user=new Users();

$options=array();
if (isset($_POST['searchstr'])) {
	$_POST['searchstr']=trim($_POST['searchstr']);
	$options['strings_OR'][]=array('field'=>'userID', 'eq'=>'=', 'value'=>$_POST['searchstr']);
	$options['strings_OR'][]=array('field'=>'userEmail', 'eq'=>'like', 'value'=>'%%'.$_POST['searchstr'].'%%');
	$options['strings_OR'][]=array('field'=>'userRussianName', 'eq'=>'like', 'value'=>'%%'.$_POST['searchstr'].'%%');
	$options['strings_OR'][]=array('field'=>'userLatinName', 'eq'=>'like', 'value'=>'%%'.$_POST['searchstr'].'%%');
} 

if (is_numeric($_POST['id'])) {
	$options['userID']=intval($_POST['id']);
}
if (isset($_POST['userType'])) $options['userType']=$_POST['userType'];

if (sizeof($options)>0) {
	$users=$user->searchObjectsFromDB($options);

	if (is_array($users)) foreach($users as $u) {
		$u->loadLinkedFromDB();
		$userarr[]=$u->getValues();
	}
	$smarty->assign('Users',$userarr);
}
$smarty->assign($_POST);

$smarty->display("users.html");


?>
