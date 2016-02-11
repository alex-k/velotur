<?php

if (!is_object($user) || !$user->exists()) {
	$smarty->display('login.html');
	exit();
}
?>
