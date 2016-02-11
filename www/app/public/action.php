<?
require_once("../config/init.php");
switch ($_POST['action']) {
	case 'login':
		include ("auth.php");
		include ("restricted.php");
		break;
	case 'logout':
		unset($_SESSION['user']);
		break;
}

header("Location: /calendar.php?year=".date("Y"));
include("index.php");

?>
