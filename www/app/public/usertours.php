<?
require_once("../config/init.php");
include ("auth.php");
include ("restricted.php");

if (isset($_POST['_action'])) {
	if ($_POST['tourUserType']=='deleted') {
		$tour=new Tours($_POST['applyTourID']);
		if ($_POST['deleteLinkedUsers']) {
			$twt=record_by_id($tour->getID(),'tw_tours');
			foreach ($twt->Users as $t) {
				$tu=record_by_id($t->userID,'tw_users');
				if (!$tu || $tu->userReferalID!=$user->getID()) continue;

				$t->tourUserType='deleted';
				$t->tourUserModifyDate=date('Y-m-d H:i:s');

			}
			$twt->commit();
		}
		$user->Tours[$_POST['applyTourID']]->tourUserType='deleted';
		$user->Tours[$_POST['applyTourID']]->tourUserModifyDate=date('Y-m-d H:i:s');

		$gmail=$tour->Guides->guideEmail1;
		$subject="отказ от тура $tour->tourTitle";
		$body="
		ваш статус изменен. 
		подробнее по ссылке 'http://www.velotur.ru/usertours.php'
		";
		pmail($user->userEmail,$body,$subject,false,$gmail);

		$subject="отказ от тура $tour->tourTitle";
		$body="
			Пользователь $user->userRussianName $user->userEmail отказался от тура $tour->tourTitle
			<a href=\"http://velotur.ru/admin/tourinfo.php?tourID=$tour->tourID&showUser=$user->userID#showUser\">
			http://velotur.ru/admin/tourinfo.php?tourID=$tour->tourID&showUser=$user->userID#showUser
			</a>
		";
		pmail($gmail,$body,$subject,false,$gmail);


		$message.="$user->userEmail: mail sent<br>";
		$user->storeLinkedInDB();

		$tour=new Tours($_POST['applyTourID']);
		$tour->loadLinkedFromDB();
		$tour->updateDB();

	}
	if (isset($_POST['tourUserCommentsTicket_add'])) {
		$user->Tours[$_POST['applyTourID']]->tourUserCommentsTicket.="\n\n".strip_tags($_POST['tourUserCommentsTicket_add']);
		$user->storeLinkedInDB();
	}
	if (isset($_POST['tourUserCommentsUser'])) {
		$user->Tours[$_POST['applyTourID']]->tourUserCommentsUser=strip_tags($_POST['tourUserCommentsUser']);
		$user->storeLinkedInDB();
	}
	header("Location: usertours.php");
}

$uvalues=$user->getValues();

$options=array();
$options['userReferalID']=$user->getID();
$u=new Users();
$referals=$u->searchObjectsFromDB($options);
if (is_array($referals) and sizeof($referals)>0) foreach ($referals as $ref) {
	$ref->loadLinkedFromDB();
	if (sizeof($ref->Tours)>0) foreach ($ref->Tours as $t) {
		$uvalues[Tours][$t->getID()][RefUsers][$ref->getID()]=$ref->getValues();
	}
}

//mydump($uvalues);

$smarty->assign($uvalues);



$smarty->display("usertours.html");
?>
