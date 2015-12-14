<?

ini_set('display_errors',1);
ini_set('error_reporting',E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);


$_CONF=array();
$_CONF[root_dir]=realpath(!empty($_LOCALCONF[root_dir])  ? $_LOCALCONF[root_dir] : $_SERVER[DOCUMENT_ROOT]."/../")."/";
$_CONF[pear_dir]="/pear/";
$_CONF[config_dir]="/config/";

$_runtm=microtime(true);




$_POST=array_merge($_POST,$_GET);

include_once($_CONF[root_dir].$_CONF[pear_dir].'Config.php');


$config=new Config();
$_CONF=$config->parseConfig($_CONF[root_dir].$_CONF[config_dir].'config.ini','IniFile');
$_CONF=$_CONF->toArray();
$_CONF=$_CONF[root];

if (!empty($_LOCALCONF[root_dir])) $_CONF[root_dir]=$_LOCALCONF[root_dir];
if (!isset($_CONF[root_dir])) $_CONF[root_dir]=realpath($_SERVER[DOCUMENT_ROOT]."/../")."/";
if (!isset($_CONF[hostname])) $_CONF[hostname]=$_SERVER[HTTP_HOST];


ini_set('include_path',ini_get('include_path').":$_CONF[root_dir]/$_CONF[pear_dir]:");

if (FALSE && $_CONF['log_error']) {
	ini_set('error_reporting','E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR');
	ini_set('log_errors','On');
	ini_set('error_log',$_CONF[root_dir].$_CONF[log_dir].$_CONF[log_errors]);

}




require $_CONF[root_dir].$_CONF[smarty_dir].'Smarty.class.php';
require $_CONF[root_dir].$_CONF[smarty_dir].'SmartyValidate.class.php';
require $_CONF[root_dir].$_CONF[classes_dir].'class_dblevel.php';
require $_CONF[root_dir].$_CONF[classes_dir].'base.php';
require $_CONF[root_dir].$_CONF[classes_dir].'base_functions.php';

/*
require_once 'HTML/QuickForm.php';
$form = new HTML_QuickForm('1');
$form->addElement('hidden', '_id');
$form->addElement('text', '_id');
$form->addElement('button', '_id');
$form->addElement('submit', '_id');
*/

$_CONF[http_referer]=$_SERVER[HTTP_REFERER];

if ($_CONF['default_socket_timeout']) {
	ini_set('default_socket_timeout',$_CONF['default_socket_timeout']);
}

/*
if (!empty($_POST[SESSID])) {
	session_id($_POST[SESSID]);
}
*/

//import_request_variables("gpc");
$subdir=str_replace($_SERVER[DOCUMENT_ROOT],'',dirname($_SERVER['SCRIPT_FILENAME']));
$www_subdir=substr($_SERVER['REQUEST_URI'],-1)=='/' ?  $_SERVER['REQUEST_URI'] : dirname($_SERVER['REQUEST_URI']);

$smarty = & new Smarty;
$smarty->template_dir=array(
			realpath($_CONF['root_dir'].$_CONF['template_dir'].$www_subdir.'/'), 
			realpath($_CONF['root_dir'].$_CONF['template_dir'].$subdir.'/'), 
			realpath($_CONF['root_dir'].$_CONF['template_dir'].'/'),
			);
$smarty->compile_dir=$_CONF['root_dir'].$_CONF['temp_dir'].'templates_c/'.$subdir;
$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->assign('_CONF',$_CONF);





$DBCLASS=new DbMysqlLayer();


#set_exception_handler('handlerMyException');
#$old_error_handler = set_error_handler("myErrorHandler");


if ($_CONF[system_maintene] && strpos($_SERVER[REQUEST_URI],'/admin/')===false) {
	$smarty->display($_CONF[root_dir]."public_html/templates/maintene.html");
	exit();
}

loadclass('Trips');
loadclass('Tours');
loadclass('Guides');
loadclass('Users');
loadclass('Partners');

session_start();
header("Cache-control: private");

if (!empty($_POST['formLoginSubmit'])) {
	$_SESSION['user']=NULL;
}

if (isset($_POST['logout'])) {
	$_SESSION['user']=NULL;
}

$user=$_SESSION['user'];
if (is_object($user) && $user->getID()) {
	$user=new Users($user->getID());
	$_SESSION['user']=$user;
	$smarty->assign('User',$user->getValues());
}
/*
function __autoload($class) {
	return loadclass($class);
}
*/

/*
require_once(dirname(__FILE__).'/../libs/config.lib.php');
$cfg=gs_config::get_instance();
$init=new gs_init('user');
$init->init(LOAD_CORE | LOAD_STORAGE | LOAD_TEMPLATES);
$init->load_modules();
*/

require_once(dirname(__DIR__).'/vendor/phpbee/phpbee/libs/config.lib.php');
$cfg=gs_config::get_instance();
$init=new gs_init('auto');
cfg_set('tpl_data_dir',array(
	cfg('tpl_data_dir'),
	realpath(cfg('root_dir').'html'),
));
//$init->init(LOAD_CORE);
$init->init(LOAD_CORE | LOAD_STORAGE | LOAD_TEMPLATES | LOAD_EXTRAS);
cfg_set('init_data',$init->data);
cfg_set('www_dir','');

?>
