<?
require_once("../../config/init.php");
require_once "auth.php";
#ini_set('display_errors', 1);
#ini_set('log_errors', 1);
#ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
#error_reporting(E_ALL);

$smarty->assign('d_tourstatus',getTourStatus());

loadclass("Partners");


if (!empty($_POST) && !empty($_POST[completeTour]) && is_numeric($_POST['tourID'])) {
	$tour=new Tours((int)$_POST['tourID']);
	if (strtotime($tour->tourEndDate)<time()) {
		$que=sprintf("update TourUsers set tourUserType='completed' where tourUserType='apply' and tourID='%d'",$tour->getID());
		$DBCLASS->query($que);
		$que="update User set userType='guard' where userCompletedTours>=5 and userType='regular'";
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
	if (isset($_POST['tourUserType'])) {
			$user->Tours[$tour->getID()]->tourUserType=$_POST['tourUserType'];
			$user->Tours[$tour->getID()]->tourUserModifyDate=date('Y-m-d H:i:s');
			
	}
	if (isset($_POST['tourUserComments'])) $user->Tours[$tour->getID()]->tourUserComments=$_POST['tourUserComments'];
	if (isset($_POST['tourUserCommentsUser'])) $user->Tours[$tour->getID()]->tourUserCommentsUser=$_POST['tourUserCommentsUser'];
	if (isset($_POST['tourUserCommentsPrepay'])) $user->Tours[$tour->getID()]->tourUserCommentsPrepay=$_POST['tourUserCommentsPrepay'];
	if (isset($_POST['tourUserCommentsTicket'])) $user->Tours[$tour->getID()]->tourUserCommentsTicket=$_POST['tourUserCommentsTicket'];
	if (isset($_POST['tourUserCommentsRegVia'])) $user->Tours[$tour->getID()]->tourUserCommentsRegVia=$_POST['tourUserCommentsRegVia'];

	if (isset($_POST['newTourID']) && is_numeric($_POST['newTourID'])) {
		$newtour=new Tours((int)$_POST['newTourID']);
		if (!$newtour->getID()) break;



		$user->Tours[$newtour->getID()]=$user->Tours[$tour->getID()];
		$user->Tours[$newtour->getID()]->tourID=$newtour->getID();
		unset($user->Tours[$tour->getID()]);
		unset($tour->Users[$user->getID()]);


		$user->storeLinkedInDB();
		$tour->updateDB();
		$newtour->updateDB();

		$payments=new tw_payments();
		$payments->find_records(array('tourID'=>$tour->getID(),'userID'=>$user->getID()));
		foreach ($payments as $p) {
			$p->tourID=$newtour->getID();
		}
		$payments->commit();

	}

	for($i=1;$i<=5;$i++) {
		$n="tourUserAddValue$i";
		if (isset($_POST[$n])) $user->Tours[$tour->getID()]->$n=trim($_POST[$n]);
	}



	if (isset($_POST['tourUserRoomingType'])) $user->Tours[$tour->getID()]->tourUserRoomingType=$_POST['tourUserRoomingType'];
	if (isset($_POST['tourUserRoomingNo'])) $user->Tours[$tour->getID()]->tourUserRoomingNo=$_POST['tourUserRoomingNo'];
	$user->Tours[$tour->getID()]->tourUserRooming=$user->Tours[$tour->getID()]->tourUserRoomingType.' '.$user->Tours[$tour->getID()]->tourUserRoomingNo;
	
	

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

		if ( $user->Tours[$tour->getID()]->tourUserAddEmail1) {
			pmail($user->Tours[$tour->getID()]->tourUserAddEmail1,$body,$subject,false,$gmail);
			$message.=$user->Tours[$tour->getID()]->tourUserAddEmail1.": mail sent<br>";
		}
		if ( $user->Tours[$tour->getID()]->tourUserAddEmail2) {
			pmail($user->Tours[$tour->getID()]->tourUserAddEmail2,$body,$subject,false,$gmail);
			$message.=$user->Tours[$tour->getID()]->tourUserAddEmail2.": mail sent<br>";
		}
	}
	$user->storeLinkedInDB();
	$tour->loadLinkedFromDB();
	$tour->updateDB();

	header("Location:".$_SERVER["REQUEST_URI"]);
	die();
}

$t=new Tours((int)$_POST['tourID']);

$t->loadLinkedFromDB();

$tw_tour=record_by_id($t->getID(),'tw_tours');
$ps=new tw_payments;

if (is_array($t->Users)) foreach ($t->Users as $k=>$u) {
	 $u->loadLinkedFromDB();
}

/*if (is_array($t->Users)) foreach ($t->Users as $k=>$u) {
	 $u->loadLinkedFromDB();
	 $u->payments_pay=$tw_tour->Payments->find(array('userID'=>$k,array('field'=>'Type','case'=>'!=','value'=>'скидка')));
	 $u->payments_discounts=$tw_tour->Payments->find(array('userID'=>$k,'Type'=>'скидка'));
	 foreach (array('payments_pay','payments_discounts') as $p) {
		$s=0;
		if (!$u->$p) continue;
		foreach($u->$p as $o) {
			$s+=$o->Amount;
		}
		$u->{$p."_sum"}=$s;
		$u->{$p."_txt"}=implode("\n",$u->$p->recordset_as_string_array());
		$u->payments_credit_sum=$ps->calc_total($t->getID(),$k);
	 }
	 $t->Users[$k]=$u;
}*/

if (is_array($t->Users)) so_tour_status($t->Users);

if (!empty($_POST) && !empty($_POST[mailFormSubmit])) {
	$gmail=$guide->guideEmail1;
	$mailText=$_POST['mailText'];
	$mailSubject=$_POST['mailSubject'];
	if (isset($_FILES['mailFile']) && $_FILES['mailFile']['error']==0) {
		include($_CONF[root_dir].$_CONF[pear_dir].'Mail/mime.php');
		$message = new Mail_mime();
		/*
		$fname="/tmp/".$_FILES['mailFile']['name'];
		move_uploaded_file($_FILES['mailFile']['tmp_name'],$fname);
		$message->addAttachment($fname);
		*/


		$message->addAttachment ( 
			 $_FILES['mailFile']['tmp_name'] ,
			 'application/octet-stream' ,
			 $_FILES['mailFile']['name'],
			  true ,
			 'base64' ,
			 'attachment' ,
			 'UTF-8' ,
			 '' ,
			 '' ,
			 'UTF-8',
			 'UTF-8', //f_encoding
			 '' ,
			 'UTF-8'
			);


		$message->setTXTBody($mailText);

		$mimeparams['text_encoding']="8bit"; 
		$mimeparams['text_charset']="UTF-8"; 
		$mimeparams['html_charset']="UTF-8"; 
		$mimeparams['head_charset']="UTF-8"; 
		$mimeparams["debug"] = "True"; 

		$mailText= $message->get($mimeparams);
		$headers = $message->headers($headers);

	}
	$message='';
	$recipients=array();
	$recipients_userid=array();
	if (!$_POST['mailTestMode'] ) {
		if (is_array($t->Users)) foreach ($t->Users as $u) {
			$u->loadLinkedFromDB();
			if (in_array($u->tourUserType,$_POST['mailStatus'])) {
				$recipients[]=$u->userEmail;
				$recipients_userid[]=$u->userID;

				if ($u->userPartnerID) {
					$p=new Partners($u->userPartnerID);
					$recipients[]=$p->partnerEmail;
				}
				if ( $u->Tours[$t->getID()]->tourUserAddEmail1) $recipients[]=$u->Tours[$t->getID()]->tourUserAddEmail1;
				if ( $u->Tours[$t->getID()]->tourUserAddEmail2) $recipients[]=$u->Tours[$t->getID()]->tourUserAddEmail2;

			} else if ( in_array('checkedUsers',$_POST['mailStatus']) && in_array($u->userEmail, explode(',',trim($_POST['mailCheckedEmails'],',') ))) {
				$recipients[]=$u->userEmail;
				$recipients_userid[]=$u->userID;
				if ($u->userPartnerID) {
					$p=new Partners($u->userPartnerID);
					$recipients[]=$p->partnerEmail;
				}
				if ( $u->Tours[$t->getID()]->tourUserAddEmail1) $recipients[]=$u->Tours[$t->getID()]->tourUserAddEmail1;
				if ( $u->Tours[$t->getID()]->tourUserAddEmail2) $recipients[]=$u->Tours[$t->getID()]->tourUserAddEmail2;
			}
		}

		foreach (array('guideID','guideID2') as $guideID) {
			if ($t->$guideID) {
				$g=new Guides($t->$guideID);
				$recipients[]=$g->guideEmail1;
				$recipients[]=$g->guideEmail2;
			}
		}
	}
	$recipients[]=$guide->guideEmail1;
	$recipients[]=$guide->guideEmail2;
	$recipients[]='alexei.kochetov@gmail.com';

	if (pmail(array_unique($recipients),$mailText,$mailSubject,$headers,$gmail)) {
		$message="MAIL SENT:<br>".implode($recipients,"<br>\n");

		$history=new mailhistory();
		$rec=$history->new_record($_POST);
		$rec->fromEmail=$gmail;
		$rec->recipients=implode(',',$recipients);
		$rec->mailStatus=implode(',',$_POST['mailStatus']);
		if (isset($_POST['mailCheckedEmails']) && is_array($_POST['mailCheckedEmails'])) $rec->mailCheckedEmails=implode(',',$_POST['mailCheckedEmails']);
		$history->commit();
	
		foreach ($recipients_userid as $id) {
			$DBCLASS->query(sprintf('replace into m2m_mailhistory_tw_users (tw_users_id,mailhistory_id) values (%d,%d)',$id,$rec->get_id()));
		}

	}

}

if ($_POST['type']!=='finlist') {
	if ($t->guideID) {
		$g=new Guides($t->guideID);

		$t->Users[]=$g;
	}
	if ($t->guideID2) {
		$g=new Guides($t->guideID2);
		$t->Users[]=$g;
	}
}

$smarty->assign("Tour",$t->getValues());
$smarty->assign("addvaluesArray",$t->getAddValuesArray());
$t=$t->getValues();

if ($_POST['tourID']!=$_SESSION['tourinfo_sid']) {
	$_SESSION['tourinfo_s']=$_SESSION['tourinfo_sr']=FALSE;
	$_SESSION['tourinfo_sid']=$_POST['tourID'];
} else if (isset($_POST['s'])) {
	if ($_POST['s']==$_SESSION['tourinfo_s']) $_SESSION['tourinfo_sr'] = $_SESSION['tourinfo_sr'] ? FALSE : TRUE;

	if ( $_POST['s']=='tourUserType') $_SESSION['tourinfo_s']=$_SESSION['tourinfo_sr']=FALSE;
	else $_SESSION['tourinfo_s']=$_POST['s'];
}
$sf=$_SESSION['tourinfo_s'];
$sr=$_SESSION['tourinfo_sr'];
if ($sf) {
	if ($sr) {
		$sb=sprintf('$a[\'%s\']',$sf);
		$sa=sprintf('$b[\'%s\']',$sf);
	} else {
		$sa=sprintf('$a[\'%s\']',$sf);
		$sb=sprintf('$b[\'%s\']',$sf);
	}
	usort($t['Users'],create_function('$a,$b',"return is_numeric($sa) && is_numeric($sb) ? $sa-$sb : strcmp($sa,$sb);"));
}


$smarty->assign($t);

$smarty->assign('message',$message);

$smarty->assign('title',sprintf("- информация по походу %s (%s - %s) ", $t['tourTitle'], $t['tourStartDate'], $t['tourEndDate']));

if ($_POST['type']=='vypiska') {
	$smarty->assign('title',sprintf("-  выписка по походу %s (%s - %s) ", $t['tourTitle'], $t['tourStartDate'], $t['tourEndDate']));
	$smarty->display("tourinfo_vypiska.html");
} else if ($_POST['type']=='txt') {
	header('Content-type: text/plain');
	header(sprintf('Content-disposition: attachment; filename="%s (%s - %s).txt"',$t['tourTitle'], $t['tourStartDate'], $t['tourEndDate']));
	$out=$smarty->fetch("tourinfo_TXT.html");
	$out=Utf8ToWin($out);
	echo $out;
} else if (!empty($_POST['type'])) {
	$values=array(
	            'list'=>array(
	                       'ФИО'=>  $_POST['rus'] ? '{$u.userRussianName|@strtolower|@ucwords}' :'{$u.userLatinName|@strtolower|@ucwords}',
	                       'Дата рождения'=>'{$u.userBirthDay|date_format:"%Y-%m-%d"}',
	                       'Город'=>'{$u.userCity|@strtolower|@ucwords}',
	                       //'паспорт'=>'{$u.userPassport}{if $u.userPassportType} ({$u.userPassportType} {$u.userCitizenship}){/if}',
	                       'паспорт'=>'{$u.userPassport}',
	                       'выдан'=>'{$u.userPassportIssuedBy|@strtolower|@ucwords} {$u.userPassportIssuedDate|date_format:"%Y-%m-%d"}',
	                       'годен'=>'{$u.userPassportValidThrow|date_format:"%Y-%m-%d"}',
	                       'предоплата'=>'{$u.tourUserCommentsPrepay}',
	                       'билет'=>'{$u.tourUserCommentsTicket}',
							'руминг'=>'{$u.tourUserRooming}',
	                       'комментарии'=>'{$u.tourUserComments}',
	                       'пожелания'=>'{$u.tourUserCommentsUser}',
	                       'код вводящего'=>'{$u.tourUserCommentsRegVia}',
	                       $_POST['format']?'':'как нас нашли'=>$_POST['format']?'':'{$u.userInfoHowFound}',
	                   ),
	            'shortlist'=>array(
	                            'ФИО'=>  $_POST['rus'] ? '{$u.userRussianName|@strtolower|@ucwords}' :'{$u.userLatinName|@strtolower|@ucwords}',
	                            'ПОЛ'=>'{$u.userSex}',
				    //'паспорт'=>'{$u.userPassport}{if $u.userPassportType} ({$u.userPassportType} {$u.userCitizenship}){/if}',
				       'паспорт'=>'{$u.userPassport}',
	                            'годен'=>'{$u.userPassportValidThrow|date_format:"%Y-%m-%d"}',
	                            'Дата рождения'=>'{$u.userBirthDay|date_format:"%Y-%m-%d"}',
	                        ),
	            'guidelist'=>array(
	                            'ФИО'=>  $_POST['rus'] ? '{$u.userRussianName|@strtolower|@ucwords}' :'{$u.userLatinName|@strtolower|@ucwords}',
	                            'Номер Телефона'=>'{$u.userPhone}',
	                            //'предоплата'=>'{$u.tourUserCommentsPrepay}',
	                            'билет'=>'{$u.tourUserCommentsTicket}',
	                            'руминг'=>'{$u.tourUserRooming}',
	                            'комментарии'=>'{$u.tourUserComments}',
	                            'пожелания'=>'{$u.tourUserCommentsUser}',
	                        ),
	            'finlist'=>array(
	                            'ФИО'=>  $_POST['rus'] ? '{$u.userRussianName|@strtolower|@ucwords}' :'{$u.userLatinName|@strtolower|@ucwords}',
	                            'Стоимость'=>'{$tourPrice1}',
	                            //'скидка'=>'{$u.payments_discounts_txt}',
	                            'скидка'=>'{$u.payments_discounts_sum}',
	                            //'предоплата'=>'{$u.payments_pay_txt}',
	                            'предоплата'=>'{$u.payments_pay_sum}',
								'задолженность'=>'{$u.payments_credit_sum}',
								'Факт (отметка гида)'=>'',
								'баланс'=>'',
	                            //'пожелания'=>'{$u.tourUserCommentsUser}',
	                        ),
	            'chaikalist'=>array(
	                            'ФИО'=>  '{$u.userRussianName|@strtolower|@ucwords}' ,
							    'Город'=>'{$u.userCity|@strtolower|@ucwords}',
	                            'Email'=>'{$u.userEmail}',
	                            'Телефон'=>'{$u.userPhone}',
								'Руминг'=>'{$u.tourUserRooming}',
	                        ),
	            'kdenlist'=>array(
	                       'ФИО'=>  '{$u.userRussianName|@strtolower|@ucwords}',
	                       'Name (Как в загранпаспорте)'=>  '{$u.userLatinName|@strtolower|@ucwords}',
	                       'Город'=>'{$u.userCity|@strtolower|@ucwords}',
							'Email'=>'{$u.userEmail}',
							'Телефон'=>'{$u.userPhone}',
	                       'Дата рождения'=>'{$u.userBirthDay|date_format:"%Y-%m-%d"}',
	                       //'Паспорт'=>'{$u.userPassport}{if $u.userPassportType} ({$u.userPassportType} {$u.userCitizenship}){/if}',
	                       'паспорт'=>'{$u.userPassport}',
	                       'годен'=>'{$u.userPassportValidThrow|date_format:"%Y-%m-%d"}',
	                       'выдан'=>'{$u.userPassportIssuedBy|@strtolower|@ucwords} {$u.userPassportIssuedDate|date_format:"%Y-%m-%d"}',
	                       'регистрация'=>'{$u.tourUserDate}',
	                       'прокат велосипеда'=>'{$u.tourUserAddValue2}',
	                       'предоплата'=>'{$u.tourUserCommentsPrepay}',
	                       'билет'=>'{$u.tourUserCommentsTicket}',
							'руминг'=>'{$u.tourUserRooming}',
	                       'комментарии'=>'{$u.tourUserComments}',
	                       'пожелания'=>'{$u.tourUserCommentsUser}',
	                   ),
	        );


	$v=$values[$_POST['type']];
	if (is_array($v)) {
		$smarty->assign('fields_Names',array_keys($v));
		$smarty->assign('fields_Values',array_values($v));

		$smarty->assign('title',sprintf("-  участники похода %s (%s - %s) ", $t['tourTitle'], $t['tourStartDate'], $t['tourEndDate']));
		if ($_POST['format']=='csv') {
			header('Content-type: text/csv;charset=CP1251');
			header(sprintf('Content-disposition: attachment; filename="%s\_%s\_%s.csv"',$_POST['type'],$t['tourTitle'], $t['tourStartDate']));
			$out=$smarty->fetch("tourinfo_list_SHABLON_CSV.html");
			$out=Utf8ToWin($out);
			echo $out;
		} else if ($_POST['format']=='xls') {
			header('Content-type: application/vnd.ms-excel');
			header(sprintf('Content-disposition: attachment; filename="%s_%s_%s.xls"',$_POST['type'],$t['tourTitle'], $t['tourStartDate']));
			$out=$smarty->fetch("tourinfo_list_SHABLON_XLS.html");
			echo $out;
		} else if ($_POST['format']=='xml') {
			header('Content-type: application/xml');
			header(sprintf('Content-disposition: attachment; filename="%s\_%s\_%s.xml"',$_POST['type'],$t['tourTitle'], $t['tourStartDate']));
			$out=$smarty->fetch("tourinfo_list_SHABLON_XLS.html");
			echo $out;
		} else if ($_POST['format']=='txt') {
			header('Content-type: text/plain;charset=CP1251');
			header(sprintf('Content-disposition: attachment; filename="%s\_%s\_%s.txt"',$_POST['type'],$t['tourTitle'], $t['tourStartDate']));
			$out=$smarty->fetch("tourinfo_list_SHABLON_TXT.html");
			$out=Utf8ToWin($out);
			echo $out;
		} else {
			$smarty->display("tourinfo_list_SHABLON.html");
		}
	}

} else {
	$smarty->display("tourinfo.html");
}

