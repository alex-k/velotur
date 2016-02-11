<?
//if (!is_object($user) || !$user->exists()) {
if (!empty($_POST['formLoginSubmit'])) {
	$smarty->assign($_POST);
	$out=$smarty->fetch('login.html');
	$message='_errorCheckValues';
	//if (!empty($_POST) && !empty($_POST['formLoginSubmit']) && (($smarty->_validate_processed==1 && $smarty->_validate_error!=1))) {
	if (!empty($_POST) && !empty($_POST['formLoginSubmit']) && ($smarty->_validate_error!=1)) {
		$user=new Users();
		if ($user->auth($_POST['userEmail'],$_POST['userPassword'])) {
			$message='_userAuthOK';
			$_SESSION['user']=$user;

			/*
			if ($_SERVER['PHP_SELF']=='/action.php') {
				header('Location: /usertours.php');
			} else {
				header('Location: '.$_SERVER['REQUEST_URI']);
			}
			*/
			//header('Location: /usertours.php');
			header('Location: '.$_SERVER['REQUEST_URI']);
		} else {
			$message='_errorLogPass';
			if ($_POST['newUser']) {
				$user->loadFromHTML($_POST);
				$message='_errorBusyEmail';
				if ($user->checkLogin($user->userEmail)) {
					$user->insertIntoDB();
					$message='_userCreated';
					$_SESSION['user']=$user;
					header('Location: '.$_SERVER['REQUEST_URI']);
				}
			}
		} 
	}
	$smarty->assign('message',$message);
}
if (isset($_POST['formLoginAdmin']) && $guide=$_SESSION['guide']) {
		$user=new Users($_POST['userID']);
}

//}

if (is_object($user) && $user->exists()) {
	$user=new Users($user->getID());
	$user->loadLinkedFromDB();
	$smarty->assign('User',$user->getValues());
}


?>
