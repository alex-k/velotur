<?php

chdir(dirname(__DIR__));

if (!defined('PHPBEE_VAR_DIR')) {
    if (getenv('PHPBEE_VAR_DIR') !== FALSE) {
        define('PHPBEE_VAR_DIR', getenv('PHPBEE_VAR_DIR'));
    } else {
        define('PHPBEE_VAR_DIR', '/tmp/velotur/var/');
    }
}


require "vendor/phpbee/phpbee/libs/config.lib.php";

$gs_node_id=1;
$init=new gs_init();
$cfg=gs_config::get_instance();

set_time_limit(300);
$cfg->check_install_key();
$init->init(LOAD_CORE | LOAD_STORAGE | LOAD_TEMPLATES | LOAD_EXTRAS);
gs_eventer::clean_subscribes();
$init->clear_cache();
$init->compile_modules();
$init->load_modules();
# $init->install_modules();
$init->save_handlers();
gs_fkey::update_fkeys();

gs_logger::dump();

echo "install done";
