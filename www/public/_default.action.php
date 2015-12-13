<?
require_once "../config/init.php";
$classname=$_POST[_classname];
$action=$_POST[_action];
$backurl=$_POST[_backurl];
loadclass($classname);
$obj=new $classname($_POST[_id]);
switch($action) {
	case 'actionsUpdate':
		$obj->loadFromHTML();
		$obj->updateDB();
		if ($obj->getClass()=='Users') {
			$user=new Users($obj->getID());
			$_SESSION['user']=$user;
		}
		break;

	case 'actionsDelete':
		$obj->deleteFromDB();
		break;


	case 'actionsAdd':
		$obj->loadFromHTML();
		$obj->insertIntoDB();
		$obj->loadFromDB($obj->getID());
		if ($obj->getClass()=='Users' && !($obj->userReferalID>0)) {
			$user=new Users($obj->getID());
			$_SESSION['user']=$user;
		}
		break;
}
if (strpos('?',$backurl)===null) $href="$backurl?_classname=$classname&id=".$obj->getID()."&$obj->_idfield=".$obj->getID()."&message=".$obj->getMessage();
	else $href="$backurl";
if (!$_NOREDIR) {
	header("Location: $href");
	exit();
}
?>
