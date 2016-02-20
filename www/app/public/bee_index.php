<?php
chdir(dirname(__DIR__));


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
$gs_node_id = 1;
$cfg = gs_config::get_instance();
mlog('1');

$init = new gs_init('auto');

cfg_set('tpl_data_dir', array(
    cfg('tpl_data_dir'),
    realpath(cfg('root_dir') . '../html'),
));

$init->init(LOAD_CORE);
$init->load_modules();
include_once('classes/base.php');
include_once('classes/Users_class.php');
include_once('classes/Guides_class.php');
session_start();

if (stripos($_SERVER['REQUEST_URI'], '/admin') === 0) {
    if (isset($_SESSION['guide'])) {
        $guide = $_SESSION['guide'];
        $tpl = gs_tpl::get_instance();
        $tpl->assign('Guide', get_object_vars($guide));
    } else {
        header('Location: /admin/');
        exit();
    }
}
if (isset($_SESSION['user'])) {
    $myuser = $_SESSION['user'];
    $myuser = record_by_id($myuser->userID, 'tw_users');
    $smarty = gs_tpl::get_instance();
    $smarty->assign('User', $myuser);
}

$tpl = gs_tpl::get_instance();
$tplDir = realpath(getcwd() . '/html/') . '/';
$tpl->addTemplateDir($tplDir);
cfg_set('tpl_data_dir', $tplDir);

$o_h = new gs_parser($init->data);
$o_h->process();



