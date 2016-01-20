<?
$partner=$_SESSION['partner'];
if (!is_object($partner) || !$partner->exists()) {
if (!empty($_POST['formLoginSubmit'])) {
	$smarty->assign($_POST);
	$out=$smarty->fetch('login.html');
	if (!empty($_POST) && !empty($_POST[formLoginSubmit]) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
		$partner=new Partners();
		if ($partner->auth($_POST['partnerLogin'],$_POST['partnerPassword'])) {
			$message='_partnerAuthOK';
			$_SESSION['partner']=$partner;
		} else {
			$message='_errorLogPass';
		} 
	}
}
}
if ($partner && $partner->exists()) {
	$partner=new Partners($partner->getID());
	$partner->loadLinkedFromDB();
	$smarty->assign('Partner',$partner->getValues());
} else {
	$smarty->display('login.html');
	exit();
}


?>
