<?
require_once("../config/init.php");


$fname=basename($_GET['t']).'.html';
if (file_exists("../public_html/content/$fname")) {
		$smarty->display("content/$fname");
} else {
		$smarty->display("index.html");
}

