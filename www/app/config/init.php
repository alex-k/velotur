<?
chdir(dirname(__DIR__));

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);


$_CONF = array();
$_CONF[root_dir] = realpath(!empty($_LOCALCONF[root_dir]) ? $_LOCALCONF[root_dir] : $_SERVER[DOCUMENT_ROOT] . "/../") . "/";
$_CONF[pear_dir] = "/pear/";
$_CONF[config_dir] = "/config/";

$_runtm = microtime(true);


$_POST = array_merge($_POST, $_GET);

include_once($_CONF[root_dir] . $_CONF[pear_dir] . 'Config.php');


$config = new Config();
$_CONF = $config->parseConfig($_CONF[root_dir] . $_CONF[config_dir] . 'config.ini', 'IniFile');
$_CONF = $_CONF->toArray();
$_CONF = $_CONF[root];

if (!empty($_LOCALCONF[root_dir])) $_CONF[root_dir] = $_LOCALCONF[root_dir];
if (!isset($_CONF[root_dir])) $_CONF[root_dir] = realpath($_SERVER[DOCUMENT_ROOT] . "/../") . "/";
if (!isset($_CONF[hostname])) $_CONF[hostname] = $_SERVER[HTTP_HOST];


ini_set('include_path', ini_get('include_path') . ":$_CONF[root_dir]/$_CONF[pear_dir]:");

if (getenv('PHPBEE_DEBUG') !== FALSE && !defined('DEBUG')) {
    define('DEBUG', getenv('PHPBEE_DEBUG'));
}

if (!defined('PHPBEE_VAR_DIR')) {
    if (getenv('PHPBEE_VAR_DIR') !== FALSE) {
        define('PHPBEE_VAR_DIR', getenv('PHPBEE_VAR_DIR'));
    } else {
        define('PHPBEE_VAR_DIR', '/tmp/velotur/var/');
    }
}


require_once('vendor/phpbee/phpbee/libs/config.lib.php');
require_once('vendor/phpbee/phpbee/libs/smarty/extSmarty.class.php');
require_once('vendor/phpbee/phpbee/libs/smarty/SmartyValidate.class.php');
#require_once('__smarty/plugins/function.controller.php');
#require_once('__smarty/plugins/function.html_options.php');
#require_once('__smarty/plugins/function.mailto.php');
#require_once('__smarty/plugins/function.beehandler.php');
#require_once('__smarty/plugins/modifier.date_format.php');

$init = new gs_init('auto');
$init->init(LOAD_CORE);
$init->load_modules();
$smarty=gs_tpl::get_instance();
$smarty->assign('_gsdata',$_REQUEST);


require_once $_CONF[root_dir] . $_CONF[classes_dir] . 'class_dblevel.php';
require_once $_CONF[root_dir] . $_CONF[classes_dir] . 'base.php';
require_once $_CONF[root_dir] . $_CONF[classes_dir] . 'base_functions.php';


$_CONF[http_referer] = $_SERVER[HTTP_REFERER];

if ($_CONF['default_socket_timeout']) {
    ini_set('default_socket_timeout', $_CONF['default_socket_timeout']);
}

$subDir = str_replace($_SERVER[DOCUMENT_ROOT], '', dirname($_SERVER['SCRIPT_FILENAME']));
$wwwSubDir = substr($_SERVER['REQUEST_URI'], -1) == '/' ? $_SERVER['REQUEST_URI'] : dirname($_SERVER['REQUEST_URI']);

$smarty->setTemplateDir(array(
    realpath($_CONF['root_dir'] . $_CONF['template_dir'] . $wwwSubDir . '/'),
    realpath($_CONF['root_dir'] . $_CONF['template_dir'] . $subDir . '/'),
    realpath($_CONF['root_dir'] . $_CONF['template_dir'] . '/'),
));
$smarty->setCompileDir('/tmp/velotur/legacy/templates_c' . $subDir);
$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->assign('_CONF', $_CONF);


$DBCLASS = new DbMysqlLayer();


if ($_CONF[system_maintene] && strpos($_SERVER[REQUEST_URI], '/admin/') === false) {
    $smarty->display($_CONF[root_dir] . "public_html/templates/maintene.html");
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
    $_SESSION['user'] = NULL;
}

if (isset($_POST['logout'])) {
    $_SESSION['user'] = NULL;
}

$user = $_SESSION['user'];
if (is_object($user) && $user->getID()) {
    $user = new Users($user->getID());
    $_SESSION['user'] = $user;
    $smarty->assign('User', $user->getValues());
}

require_once('./vendor/phpbee/phpbee/libs/config.lib.php');
$init = new gs_init('auto');
cfg_set('tpl_data_dir', array(
    cfg('tpl_data_dir'),
    realpath(cfg('root_dir') . 'html'),
));
$init->init(LOAD_CORE | LOAD_STORAGE | LOAD_TEMPLATES | LOAD_EXTRAS);
cfg_set('init_data', $init->data);
cfg_set('www_dir', '');


