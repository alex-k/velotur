<?
require_once("../config/init.php");



$smarty->assign($_POST);

$output=$smarty->fetch("password.html");
if (!empty($_POST) && !empty($_POST[formSubmit])) {
	$message='_errorCheckValues';
	if (($smarty->_validate_processed==1 && $smarty->_validate_error!=1)) {
		$message='_errorUserNotFound';
		$user=new Users();
		$options=array();
		$options['userEmail']=$_POST['userEmail'];
		$u=$user->searchObjectsFromDB($options);
		if (sizeof($u)==1) {
			$user=current($u);
			$smarty->assign('u',$user->getValues());

		$message='_errorUserNotFound';
	
		$text=$smarty->fetch('email_lost_password.html');


		pmail($user->userEmail,$text,"данные учетной записи на сайте velotur.ru",$headers,$gmail);
		$message='_password_Send';
		}
		

	} else {
		$message='_errorCheckValues';
	}
}
$smarty->assign('message',$message);
$smarty->display('password.html');
?>
