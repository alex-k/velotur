<?
require_once("../../config/init.php");
require_once "auth.php";

loadclass("Users");
loadclass("Tours");
$tu=$DBCLASS->fetch("select * from TourUsers where tourUserDate>'2000-01-01' and tourUserType in ('apply','WL') order by tourUserDate desc limit 15");
$users=array();
foreach ($tu as $t) {
	$u=new Users($t['userID']);
	$u=$u->getValues();
	$u['Tour']=new Tours($t['tourID']);
	$u['Tour']=array_merge($u['Tour']->getValues(),$t);
	$users[]=$u;
}
$smarty->assign('LastUsers',$users);

$tu=$DBCLASS->fetch("select * from TourUsers where tourUserDate>'2000-01-01' and tourUserType='deleted' and tourUserModifyDate>now()-interval 1 month order by tourUserModifyDate desc limit 15");
$users=array();
foreach ($tu as $t) {
	$u=new Users($t['userID']);
	$u=$u->getValues();
	$u['Tour']=new Tours($t['tourID']);
	$u['Tour']=array_merge($u['Tour']->getValues(),$t);
	$users[]=$u;
}
$smarty->assign('LastDeletedUsers',$users);

if (isset($_GET['full'])) {

$stats=$DBCLASS->fetch("select yearweek(tourUserDate) as d, year(tourUserDate) as year, week(tourUserDate) as week, count(userID) as cnt from TourUsers where tourUserDate is not null and tourUserDate>'2000-01-01' group by d order by d desc");
foreach($stats as $k=>$w) 
	$stats[$k]['date']=date("Y-m-d",strtotime($w['year']."-01-01 +".$w['week']." week -1 week"));


$smarty->assign('weekstats',$stats);

$st=$DBCLASS->fetch("select  date_format( tourUserDate,'%M %Y') as d,  count(userID) as cnt , date_format( tourUserDate ,'%Y-%m-01') as date, year(tourUserDate) as year, month(tourUserDate) as month from TourUsers where tourUserDate is not null and tourUserDate>'2000-01-01' group by d order by date desc");
$stats=array();
$stats2=array();
foreach ($st as $s) {
	$stats[$s['d']]=$s;
	$stats2[$s['year']][$s['month']]=$s;
}
$smarty->assign('monthstats',$stats);
$smarty->assign('monthstats2',$stats2);


//$stats=$DBCLASS->fetch("select month(tourStartDate) as m,  year(tourUserDate) as year , monthname(tourStartDate) as month , count(TourUsers.userID) as cnt from Tour left join TourUsers on Tour.tourID=TourUsers.tourID where tourUserType in ('apply','completed') group by year,m order by year desc,m desc");
$st=$DBCLASS->fetch("select date_format( tourStartDate ,'%Y-%m') as d, date_format( tourStartDate ,'%M %Y') as date,  count(TourUsers.userID) as cnt, sum(if(userType='regular',1,0)) as regular   from TourUsers left join Tour on TourUsers.tourID= Tour.tourID  left join User on User.userID=TourUsers.userID where tourUserType in ('completed') and tourUserDate>'2000-01-01' group by d order by d desc");
$stats=array();
foreach ($st as $s) {
	$stats[$s['d']]=$s;
}
$smarty->assign('monthmembers',$stats);

$st=$DBCLASS->fetch("select date_format( tourStartDate ,'%Y-%m') as d, date_format( tourStartDate ,'%M %Y') as date,  count(TourUsers.userID) as cnt from Tour left join (select tourID, TourUsers.userID from (select min(tourUserDate) m ,userID from TourUsers where tourUserType in ('completed') group by userID) as  a1 left join TourUsers on m=TourUsers.tourUserDate)  TourUsers using(tourID) group by d order by d desc");
$stats=array();
foreach ($st as $s) {
	$stats[$s['d']]=$s;
}

$smarty->assign('monthnewmembers',$stats);



$stats=$DBCLASS->fetch("select count(tourID) cnt, sum(tourPlaces) tourPlaces, sum(tourUsersApply) tourUsersApply from Tour where tourStatus='normal'");
$smarty->assign('activetours',reset($stats));

$st=array();
/*
$stats=$DBCLASS->fetch("select date_format(tourStartDate,'%Y') year, tourUserType , sum(tourPlaces) tourPlaces, count(userID) cnt from Tour left join TourUsers using (tourID) where tourStartDate>'2000-01-01' group by year, tourUserType having year>0 and tourUserType > '' order by year desc ");

foreach ($stats as $s) {
	$st[$s['year']][$s['tourUserType']]=$s['cnt'];
	$st[$s['year']]['tourPlaces']=$s['tourPlaces'];
}
*/
$stats=$DBCLASS->fetch("select date_format(tourStartDate,'%Y') year, group_concat(tourID) tours , sum(tourPlaces) tourPlaces from Tour where tourStartDate>'2000-01-01' group by year order by year desc");
foreach ($stats as $s) {
	$st[$s['year']]['tourPlaces']=$s['tourPlaces'];

	$ustat=$DBCLASS->fetch("select tourUserType,  count(userID) cnt from  TourUsers where tourID in (".$s['tours'].") group by tourUserType");
	foreach ($ustat as $u) {
		$st[$s['year']][$u['tourUserType']]=$u['cnt'];
	}
}


$cmpl=$DBCLASS->fetch("select  date_format(tourStartDate,'%Y') year, count(Tour.tourID) cnt from (select distinct tourID from TourUsers where tourUserType='completed') cmpl left join Tour using (tourID) group by year order by year desc");
foreach ($cmpl as $s) {
	$st[$s['year']]['completed_tours']=$s['cnt'];
}
$smarty->assign('touryears',$st);

$stats=$DBCLASS->fetch("select userCity, count(*) cnt from User left join TourUsers using(userID) where tourUserType='completed' group by  userCity order by cnt desc");
$smarty->assign('cityusers',$stats);

}


$smarty->display("stats.html");
?>

