<?
require_once("../../config/init.php");
require_once "auth.php";

unset($user);




if ($_POST['tourID']) $_SESSION['tourID']=$_POST['tourID'];
if ($_POST['type']) $_SESSION['type']=$_POST['type'];


$_reg_tourID=$_SESSION['tourID'];
$_reg_type=$_SESSION['type'];
	$tour=new Tours($_reg_tourID);
	$tour->loadLinkedFromDB();
	$smarty->assign('Tour',$tour->getValues());
	$smarty->assign('type',$_reg_type);



$smarty->assign('d_userSex',getUserSex());
$smarty->assign('d_userHowFound',getUserHowFound());

//$user->loadLinkedFromDB();
//$smarty->assign($user->getValues());

$u=new Users();
if ($_POST['userEmail'] && !$_POST['userLoadedOK']) {
	unset($_POST['_id']);
	$user=$u->searchObjectsFromDB(array('userEmail'=>$_POST['userEmail']));
	if (is_array($user))  {
		$user=current($user);
		$smarty->assign($user->getValues());
		unset($_POST['formSubmit']);	
		$_POST['userLoaded']=1;
		$_POST=array_filter($_POST,create_function('$a','return !empty($a);'));
	}
}


$smarty->assign($_POST);


$smarty->assign('message',$message);

$tour=new Tours($_reg_tourID);
$tour->loadLinkedFromDB();
$smarty->assign('Tour',$tour->getValues());
$smarty->assign('type',$_reg_type);

for($i=1;$i<=5;$i++) {
	$n="tourAddValue$i";
	if (!empty($tour->$n)) {
		$v=$tour->$n;
		if (($vv=explode(',',$v))!==FALSE && sizeof($vv)>1) {
			$addvaluesArray[$n]=$vv;
		}
	}
}
$smarty->assign("addvaluesArray",$addvaluesArray);

if (!empty($_POST) && !empty($_POST[formSubmit])) {
	$output=$smarty->fetch("apply.html");
	$message='_errorCheckValues';
	if (($smarty->_validate_processed==1 && $smarty->_validate_error!=1)) {
		$_POST['_classname']='Users';
		$_NOREDIR=1;
		if(!$_POST['_id']) $_POST['_action']='actionsAdd';
		include("_default.action.php");

		$user=$obj;
		$smarty->assign('u',$user->getValues());

		$tour=new Tours($_POST['applyTourID']);
			$tour->userID=$user->getID();
			$tour->tourUserType=$_POST['applyType'];
			$tour->tourUserDate=date('Y-m-d H:i:s');
			$tour->tourUserComments=$user->Tours[$tour->getID()]->tourUserComments;
			$tour->tourUserCommentsPrepay=$user->Tours[$tour->getID()]->tourUserCommentsPrepay;
			$tour->tourUserCommentsTicket=$user->Tours[$tour->getID()]->tourUserCommentsTicket;
			//$tour->tourUserCommentsRegVia=$user->Tours[$tour->getID()]->tourUserCommentsRegVia;
			$tour->tourUserCommentsRegVia=$_POST['tourUserCommentsRegVia'];

			if ($_POST['tourUserAddValue1']) {$tour->tourUserAddName1=$_POST['tourUserAddName1']; $tour->tourUserAddValue1=$_POST['tourUserAddValue1'];}
			if ($_POST['tourUserAddValue2']) {$tour->tourUserAddName2=$_POST['tourUserAddName2']; $tour->tourUserAddValue2=$_POST['tourUserAddValue2'];}
			if ($_POST['tourUserAddValue3']) {$tour->tourUserAddName3=$_POST['tourUserAddName3']; $tour->tourUserAddValue3=$_POST['tourUserAddValue3'];}
			if ($_POST['tourUserAddValue4']) {$tour->tourUserAddName4=$_POST['tourUserAddName4']; $tour->tourUserAddValue4=$_POST['tourUserAddValue4'];}
			if ($_POST['tourUserAddValue5']) {$tour->tourUserAddName5=$_POST['tourUserAddName5']; $tour->tourUserAddValue5=$_POST['tourUserAddValue5'];}


		$user->loadLinkedFromDB();
		$user->Tours[$tour->getID()]=$tour;



		$user->storeLinkedInDB();

		$tour=new Tours($_POST['applyTourID']);
		$tour->loadLinkedFromDB();
		$tour->updateDB();

		$gtext=$smarty->fetch('inc_userinfo.html');
		$gtext.=$smarty->fetch('inc_userinfo_addvalues.html');
		$gmail=$tour->Guides->guideEmail1;


		$text=($_POST['applyType']=='WL') ? $tour->tourEmailTextWL :$tour->tourEmailText;
		$text.=$smarty->fetch('apply_additional_mail.html');


		//pmail($user->userEmail,$text,"ваша заявка на поход $tour->tourTitle",$headers,$gmail);
		pmail($gmail,$gtext,"заявка на поход $tour->tourTitle $tour->tourStartDate-$tour->tourEndDate",$headers,$gmail);
		pmail($tour->Guides->guideEmail2,$gtext,"заявка на поход $tour->tourTitle $tour->tourStartDate-$tour->tourEndDate",$headers,$gmail);

		$message='APPLY_OK';
		$smarty->assign('applymessage',$text);
		header("Location: tourinfo.php?tourID=".$tour->getID()."&message=".urlencode($message));
		exit();
	} else {
		$message='_errorCheckValues';
	}
}
$smarty->assign('message',$message);
$smarty->display('apply.html');
?>
