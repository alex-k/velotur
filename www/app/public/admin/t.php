<?
require_once("../../config/init.php");
require_once "auth.php";

//$tourID*1000000+$u.userID

//$que="select tourID, userID, tourUserCommentsPrepay from TourUsers where tourUserCommentsPrepay>''";

$res=mysql_query($que);
while ($r=mysql_fetch_object($res)) {
	echo $r->tourUserCommentsPrepay,'<br>';
	preg_match("/[0-9]+[ 0-9\.\,]*/",$r->tourUserCommentsPrepay,$c);
	$c=trim($c[0]);
	$que=sprintf("insert into tw_payments (Title,Amount,Type,tourID,userID,Hidden) values (\"%s\",%d,'оплата',%d,%d,0);",
				$r->tourUserCommentsPrepay,
				$c,
				$r->tourID,$r->userID
			);
	mysql_query($que);
	//echo $que,'<br>';
}

?>
