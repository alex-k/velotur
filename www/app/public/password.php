<?
require_once("../config/init.php");


$smarty->assign($_POST);

$output = $smarty->fetch("password.html");
if (!empty($_POST) && !empty($_POST[formSubmit])) {
    $message = '_errorCheckValues';
    if (($smarty->_validate_processed == 1 && $smarty->_validate_error != 1)) {
        $message = '_errorUserNotFound';
        $user = new Users();
        $options = array();
        $options['userEmail'] = $_POST['userEmail'];
        //$options['strings_OR'][]=array('field'=>'userEmail', 'eq'=>'like', 'value'=>$_POST['userEmail'];

        $u = $user->searchObjectsFromDB($options);
        if ($u) {
            $user = current($u);
            $smarty->assign('u', $user->getValues());

            $text = $smarty->fetch('email_lost_password.html');
            $message = pmail($user->userEmail, $text, "Данные учетной записи на сайте velotur.ru", $headers) ? '_password_Sent' : '_cannot_send_mail';
        }


    } else {
        $message = '_errorCheckValues';
    }
}
$smarty->assign('message', $message);
$smarty->display('password.html');
