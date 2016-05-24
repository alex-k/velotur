<?
require_once("../config/init.php");
switch ($_REQUEST['action']) {
    case 'login':
        include("auth.php");
        include("restricted.php");
        break;
    case 'logout':
        $_SESSION['user'] = null;
        break;
}

header("Location: /calendar.php?year=" . date("Y"));
include("index.php");
