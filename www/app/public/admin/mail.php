<?
require_once("../../config/init.php");
require_once "auth.php";

$trip=new Trips();
$smarty->assign('Trips',$trip->searchFromDB());

$t=new Tours();
$options=array();
$options[]=array('field'=>'tourStartDate','eq'=>'ORDERBY','value'=>'tourTitle, tourStartDate desc');
$tours=$t->searchFromDB($options);
$smarty->assign('Tours',$tours);

if (!empty($_POST['mailFormSubmit'])) {
	global $DBCLASS;
	$users=$DBCLASS->mailUsers($_POST);


	$gmail=$guide->guideEmail1;
	$mailText=$_POST['mailText'];
	$mailSubject=$_POST['mailSubject'];

	if (isset($_FILES['mailFile']) && $_FILES['mailFile']['error']==0) {
		include('Mail/mime.php');
		$message = new Mail_mime();
		$fname="/tmp/".$_FILES['mailFile']['name'];
		move_uploaded_file($_FILES['mailFile']['tmp_name'],$fname);
		$message->addAttachment($fname);
		$message->setTXTBody($mailText);
		$mailText= $message->get(array('text_charset' => 'utf-8'));
		$headers = $message->headers($headers);
	}
	$message='';
	$sent=array();

	if (!$_POST['mailTestMode'] && is_array($users)) foreach($users as $u) {
		if (!$u['userEmail'] || in_array($u['userEmail'],$sent)) {
			continue;
		}
		pmail($u['userEmail'],$mailText,$mailSubject,$headers,$gmail);
		$sent[]=$u['userEmail'];
		$message.="$u[userEmail]: mail sent<br>";
	}
	pmail($guide->guideEmail1,$mailText,$mailSubject,$headers,$gmail);
	pmail($guide->guideEmail2,$mailText,$mailSubject,$headers,$gmail);

}

	$smarty->assign('message',$message);
$smarty->display("mail.html");


?>
