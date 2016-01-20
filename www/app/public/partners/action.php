<?
require_once("../../config/init.php");
switch ($_POST['action']) {
	case 'login':
		include ("auth.php");
		break;
	case 'logout':
		unset($_SESSION['partner']);
		header("Location: /");
		break;
}

//header("Location: /");
include("index.php");

?>
