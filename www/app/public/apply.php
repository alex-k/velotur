<?


require_once("../config/init.php");


if ($_POST['tourID']) $_SESSION['tourID'] = $_POST['tourID'];


$_reg_tourID = $_SESSION['tourID'];

$tour = new Tours($_reg_tourID);
$tour->loadLinkedFromDB();

$smarty->assign('Tour', $tour->getValues());
$smarty->assign("addvaluesArray", $tour->getAddValuesArray());

$applyType = '';
if ($tour->_allowPreApply()) $applyType = 'WL';
if ($tour->_allowWaitinglist()) $applyType = 'WL';
if ($tour->_allowApply()) $applyType = 'apply';

$_POST['applyType'] = $applyType;
$smarty->assign('type', $applyType);


$smarty->assign($_POST);
include("auth.php");
include("restricted.php");

$smarty->assign('d_userSex', getUserSex());
$smarty->assign('d_userHowFound', getUserHowFound());

/** @var Users $user */
$user->loadLinkedFromDB();


$sd = strtotime($tour->tourStartDate);
$ed = strtotime($tour->tourEndDate." -1 day");
$overlaps = array();
foreach ($user->Tours as $t) {
    $sd1 = strtotime($t->tourStartDate);
    $ed1 = strtotime($t->tourEndDate." -1 day");
    if (
        ($sd <= $sd1 && $sd1 <= $ed)
        ||
        ($sd1 <= $sd && $sd <= $ed1)
    ) {
        array_push($overlaps,$t);
    }
}
$smarty->assign('overlaps',$overlaps);


if (isset($_POST['applyFriend'])) {
    $v = $user->getValues();
    $smarty->assign('Tours', $v['Tours']);
    $smarty->assign('userID', $v['userID']);
} else {
    $smarty->assign($user->getValues());
}

$smarty->assign($_POST);


$smarty->assign('message', $message);

if (!is_object($user) || !$user->exists()) {
    $smarty->display('login.html');
    exit();
} else if ($user->isBlocked()) {
    $smarty->assign('u', $user->getValues());
    $gtext = $smarty->fetch('inc_userinfo.html');
    $gtext .= $smarty->fetch('inc_userinfo_addvalues.html');
    $gmail = $tour->Guides->guideEmail1;


    $text .= $smarty->fetch('apply_denied_mail.html');

    pmail($user->userEmail, $text, "ваша заявка на поход $tour->tourTitle", $headers, $gmail);
    pmail($gmail, $gtext, "заблокированный пользователь пытается заявиться на поход $tour->tourTitle $tour->tourStartDate-$tour->tourEndDate", $headers, $gmail);
    pmail($tour->Guides->guideEmail2, $gtext, "заблокированный пользователь пытается заявиться на поход $tour->tourTitle $tour->tourStartDate-$tour->tourEndDate", $headers, $gmail);
    $smarty->display('apply_denied.html');
    exit();
} else {


    $output = $smarty->fetch("apply.html");
    if (isset($_POST['applyFriendID'])) {
        $friend = new Users($_POST['applyFriendID']);
        $friend->loadLinkedFromDB();
        if (!isset($friend->Tours[$tour->getID()])) {
            $tour->userID = $friend->getID();
            $tour->tourUserType = $_POST['applyType'];
            $tour->tourUserDate = date('Y-m-d H:i:s');
            $friend->Tours[$tour->getID()] = $tour;
            apply_discount($friend->getID(), $tour->getID());
            $friend->storeLinkedInDB();
            $tour->loadLinkedFromDB();
            $tour->updateDB();

            email_apply($friend, $tour);

        }
        header("Location: /usertours.php?tourID=" . $tour->getID() . "&message=user added#tour" . $tour->getID());
        exit();
    } else if ($smarty->_validate_processed == 1 && $smarty->_validate_error == 1) {
        $message = '_errorCheckValues';
    } else if ($smarty->_validate_processed == 1 && $smarty->_validate_error != 1) {
        $_POST['_classname'] = 'Users';
        //$_POST['_action']='actionsUpdate';
        $_POST['_id'] = $user->getID();
        $_NOREDIR = 1;
        $origuser = $user;
        include("_default.action.php");
        $user = $obj;
        $smarty->assign('u', $user->getValues());

        $tour = new Tours($_reg_tourID);
        $tour->userID = $user->getID();
        $tour->tourUserType = $_POST['applyType'];
        $tour->tourUserDate = date('Y-m-d H:i:s');
        $tour->tourUserComments = $user->Tours[$tour->getID()]->tourUserComments;
        $tour->tourUserCommentsUser = $user->Tours[$tour->getID()]->tourUserCommentsUser;
        $tour->tourUserCommentsPrepay = $user->Tours[$tour->getID()]->tourUserCommentsPrepay;
        $tour->tourUserCommentsRegVia = $user->Tours[$tour->getID()]->tourUserCommentsRegVia;

        if ($_POST['tourUserAddValue1']) {
            $tour->tourUserAddName1 = $_POST['tourUserAddName1'];
            $tour->tourUserAddValue1 = $_POST['tourUserAddValue1'];
        }
        if ($_POST['tourUserAddValue2']) {
            $tour->tourUserAddName2 = $_POST['tourUserAddName2'];
            $tour->tourUserAddValue2 = $_POST['tourUserAddValue2'];
        }
        if ($_POST['tourUserAddValue3']) {
            $tour->tourUserAddName3 = $_POST['tourUserAddName3'];
            $tour->tourUserAddValue3 = $_POST['tourUserAddValue3'];
        }
        if ($_POST['tourUserAddValue4']) {
            $tour->tourUserAddName4 = $_POST['tourUserAddName4'];
            $tour->tourUserAddValue4 = $_POST['tourUserAddValue4'];
        }
        if ($_POST['tourUserAddValue5']) {
            $tour->tourUserAddName5 = $_POST['tourUserAddName5'];
            $tour->tourUserAddValue5 = $_POST['tourUserAddValue5'];
        }
        if ($_POST['tourUserCommentsUser']) {
            $tour->tourUserCommentsUser = $_POST['tourUserCommentsUser'];
        }


        $user->loadLinkedFromDB();
        $user->Tours[$tour->getID()] = $tour;

        apply_discount($user->getID(), $tour->getID());


        $user->storeLinkedInDB();

        $tour = new Tours($_reg_tourID);
        $tour->loadLinkedFromDB();
        $tour->updateDB();


        email_apply($origuser, $tour);

        $user = $origuser;


        header("Location: /usertours.php?tourID=" . $tour->getID() . "&message=" . urlencode($message) . "#tour" . $tour->getID());
        exit();
    }
}
$smarty->assign('message', $message);
$smarty->display('apply.html');


function email_apply($origuser, $tour)
{
    global $smarty;
    $gtext = $smarty->fetch('inc_userinfo.html');
    $gtext .= $smarty->fetch('inc_userinfo_addvalues.html');
    $gmail = $tour->Guides->guideEmail1;


    $text = ($_POST['applyType'] == 'WL') ? $tour->tourEmailTextWL : $tour->tourEmailText;
    $text .= $smarty->fetch('apply_additional_mail.html');


    pmail($origuser->userEmail, $text, "ваша заявка на поход $tour->tourTitle", $headers, $gmail);
    pmail($gmail, $gtext, "заявка на поход $tour->tourTitle $tour->tourStartDate-$tour->tourEndDate", $headers, $gmail);
    pmail($tour->Guides->guideEmail2, $gtext, "заявка на поход $tour->tourTitle $tour->tourStartDate-$tour->tourEndDate", $headers, $gmail);

    $message = 'APPLY_OK';
    $smarty->assign('applymessage', $text);

    $_SESSION['applymessage'] = $text;

}


function apply_discount($user_id, $tour_id)
{

    $tw_user = tw_users::record_by_id($user_id);
    $tw_tour = tw_tours::record_by_id($tour_id);

    $discount = new tw_discount();
    $prs = array();

    if ($tw_user->userCompletedTours >= 1) {
        $prs['retcust'] = $discount->find_records(array('AutoKey' => 'retcust1'))->first();
    }
    if ($tw_user->userType == 'guard') {
        $prs['retcust'] = $discount->find_records(array('AutoKey' => 'retcust2'))->first();
    }

    $friends = new projectfriends();
    $friends->find_records(array('User_id' => $tw_user->get_id()));

    if ($friends->count() > 0) {
        $prs['projectfriends'] = $discount->find_records(array('AutoKey' => 'projectfriends'))->first();
    }


    foreach ($prs as $ppr) {

        $pr = record_by_id($ppr->get_id(), 'tw_discount');


        $pm = new tw_payments();
        $pm->find_records(array('tourID' => $tour_id, 'userID' => $user_id, 'Discount_id' => $pr->get_id()));
        if ($pm->count() > 0) return;
        $r = $pm->new_record();

        $r->Discount_id = $pr->get_id();
        $r->Title = "$pr->Type $pr->Title";
        $r->Amount = $pr->Amount;
        if ($pr->AmountType == '%') $r->Amount = round($pr->Amount * .01 * $tw_tour->tourPrice1);
        $r->Type = "скидка";
        $r->Hidden = 0;
        $r->tourID = $tw_tour->get_id();
        $r->userID = $tw_user->get_id();
        $pm->commit();
    }
}

?>
