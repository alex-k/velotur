<?
require_once("../../config/init.php");
require_once "auth.php";
#ini_set('display_errors', 1);
#ini_set('log_errors', 1);
#ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
#error_reporting(E_ALL);

$smarty->assign('d_tourstatus',getTourStatus());


if (!empty($_POST) && !empty($_POST[completeTour]) && is_numeric($_POST['tourID'])) {
	$tour=new Tours((int)$_POST['tourID']);
	if (strtotime($tour->tourEndDate)<time()) {
		$que=sprintf("update TourUsers set tourUserType='completed' where tourUserType='apply' and tourID='%d'",$tour->getID());
		$DBCLASS->query($que);
	}
}
if (!empty($_POST) && !empty($_POST[formSubmit])) {
	$_POST['_classname']='Users';

	$tour=new Tours((int)$_POST['tourID']);
	$tour->loadLinkedFromDB();
	$user=new Users((int)$_POST['applyUserID']);
	$user->loadLinkedFromDB();
	/*
		$tour->userID=$user->getID();
		$tour->tourUserType=$_POST['tourUserType'];
		$tour->tourUserComments=$_POST['tourUserComments'];
		*/


	$oldStatus=$user->Tours[$tour->getID()]->tourUserType;
	if (isset($_POST['tourUserType'])) $user->Tours[$tour->getID()]->tourUserType=$_POST['tourUserType'];
	$user->Tours[$tour->getID()]->tourUserComments=$_POST['tourUserComments'];
	$user->Tours[$tour->getID()]->tourUserCommentsPrepay=$_POST['tourUserCommentsPrepay'];
	$user->Tours[$tour->getID()]->tourUserCommentsTicket=$_POST['tourUserCommentsTicket'];
	$user->Tours[$tour->getID()]->tourUserCommentsRegVia=$partner->partnerName;


	if ($oldStatus!=$user->Tours[$tour->getID()]->tourUserType) {
		$gmail=$tour->Guides->guideEmail1;
		$subject="изменения в походе $tour->tourTitle";
		$body="
		      ваш статус изменен.
		      подробнее по ссылке 'http://www.velotur.ru/usertours.php'
		      ";
		if ($_POST['tourUserType']=='WL') $body.=$tour->tourEmailTextWL;
		if ($_POST['tourUserType']=='apply') {
			$body.=$tour->tourEmailText;
			$body.=$smarty->fetch('apply_additional_mail.html');
		}
		pmail($user->userEmail,$body,$subject,false,$gmail);
		$message.="$user->userEmail: mail sent<br>";
	}



	/*
	mydump($_POST);
	mydump($user->Tours);
	exit();
	*/
	$user->storeLinkedInDB();
	$tour->loadLinkedFromDB();
	$tour->updateDB();



	header("Location:".$_SERVER["REQUEST_URI"]);
}

$t=new Tours((int)$_POST['tourID']);

$t->loadLinkedFromDB();



if (is_array($t->Users)) foreach ($t->Users as $k=>$u)  $u->loadLinkedFromDB();
if (is_array($t->Users)) so_tour_status($t->Users);


if ($t->guideID) {
	$g=new Guides($t->guideID);

	$t->Users[]=$g;
}
if ($t->guideID2) {
	$g=new Guides($t->guideID2);
	$t->Users[]=$g;
}


$smarty->assign($t->getValues());

$smarty->assign('message',$message);

$smarty->assign('title',sprintf("- информация по походу %s (%s - %s) ", $t->tourTitle, $t->tourStartDate, $t->tourEndDate));

$smarty->display("tourinfo.html");

function utf8_to_win($str) {
	$str = utf8_decode ($str); //  utf8 to iso8859-5
	$str = convert_cyr_string($str, 'i','w'); // w - windows-1251   to  i - iso8859-5
	return $str;
}
function Utf8ToWin($fcontents) {
	$out = $c1 = '';
	$byte2 = false;
	for ($c = 0;$c < strlen($fcontents);$c++) {
		$i = ord($fcontents[$c]);
		if ($i <= 127) {
			$out .= $fcontents[$c];
		}
		if ($byte2) {
			$new_c2 = ($c1 & 3) * 64 + ($i & 63);
			$new_c1 = ($c1 >> 2) & 5;
			$new_i = $new_c1 * 256 + $new_c2;
			if ($new_i == 1025) {
				$out_i = 168;
			} else {
				if ($new_i == 1105) {
					$out_i = 184;
				} else {
					$out_i = $new_i - 848;
				}
			}
			// UKRAINIAN fix
			switch ($out_i) {
			case 262:
				$out_i=179;
				break;// і
			case 182:
				$out_i=178;
				break;// І
			case 260:
				$out_i=186;
				break;// є
			case 180:
				$out_i=170;
				break;// Є
			case 263:
				$out_i=191;
				break;// ї
			case 183:
				$out_i=175;
				break;// Ї
			case 321:
				$out_i=180;
				break;// ґ
			case 320:
				$out_i=165;
				break;// Ґ
			}
			$out .= chr($out_i);

			$byte2 = false;
		}
		if ( ( $i >> 5) == 6) {
			$c1 = $i;
			$byte2 = true;
		}
	}
	return $out;
}
?>
