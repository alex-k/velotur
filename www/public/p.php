<?
require_once("../config/init.php");


$fname=basename($_GET['t']).'.html';
if (file_exists("../public_html/content/$fname")) {
		$smarty->display("content/$fname");
} else {
		$smarty->display("index.html");
}

/*
switch ($_GET['t']) {
	case 'comfort':
		$smarty->display("comfort.html");
		break;

	default:
		$smarty->display("index.html");
}
*/

?>
