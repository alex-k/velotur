<?
$guide=$_SESSION['guide'];
if (!is_object($guide) || !$guide->exists()) {
if (!empty($_POST['formLoginSubmit'])) {
	$smarty->assign($_POST);
	$out=$smarty->fetch('login.html');
	if (!empty($_POST) && !empty($_POST[formLoginSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
		$guide=new Guides();
		if ($guide->auth($_POST['guideLogin'],$_POST['guidePassword'])) {
			mydump($guide->guideType);
			$message='_guideAuthOK';
			$_SESSION['guide']=$guide;
			if ($guide->guideType=='admin') {
				header('Location: stats.php');
				die();
			}
		} else {
			$message='_errorLogPass';
		} 
	}
}
}
if ($guide && $guide->exists()) {
	$guide=new Guides($guide->getID());
	$guide->loadLinkedFromDB();
	$smarty->assign('Guide',$guide->getValues());
} else {
	$smarty->display('login.html');
	exit();
}

$years=$DBCLASS->queryResult('select distinct date_format(tourStartDate,"%Y") as year from Tour where tourStartDate>"2000-01-01"',1);
$smarty->assign('alltouryears',$years);


