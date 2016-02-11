<?
require_once("../../config/init.php");
require_once "auth.php";

	$tour=new Tours((int)$_GET['tourID']);
	$tour->loadLinkedFromDB();
	$user=new Users((int)$_GET['userID']);
	$user->loadLinkedFromDB();

	$t=$user->Tours[$tour->getID()];

	$t->tourUserRoomingType=$_GET['tourUserRoomingType'];
	$t->tourUserRoomingNo=$_GET['tourUserRoomingNo'];
	$t->tourUserRooming=trim($t->tourUserRoomingType.' '.$t->tourUserRoomingNo);


	$user->storeLinkedInDB();


