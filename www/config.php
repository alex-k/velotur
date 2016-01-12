<?php

$this->use_handler_cache = 0;

ini_set('error_reporting',E_ALL ^E_NOTICE);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('error_log','/var/log/apache2/velotur_error.log');
/*
*/

ini_set('max_execution_time', 1600);

$this->install_key = '12345'; // run site.com/install.php?install_key=12345
//$this->admin_ip_access='127.0.0.1, 192.168.1.102';
//$this->admin_user_name='ilia';
//$this->admin_password='cucer71';

DEFINE('DEBUG', 0);
DEFINE('UDP_DEBUG', 0);

$this->admin_ip_access = isset($this->admin_ip_access) ? array_map('trim', explode(',', $this->admin_ip_access)) : array();

$this->gs_connectors = array(
    'mysql' => array(
        'db_type' => 'mysql',
        'db_hostname' => 'mysql',
        'db_port' => '3306',
        'db_username' => 'activeinfo_newvt',
        'db_password' => 'vt123',
        'db_database' => 'activeinfo_newvt',
        'codepage' => 'utf8',
    ),
    'wizard' => array(
        'db_type' => 'mysql',
        'db_hostname' => 'mysql',
        'db_port' => '3306',
        'db_username' => 'activeinfo_newvt',
        'db_password' => 'vt123',
        'db_database' => 'activeinfo_newvt',
        'codepage' => 'utf8',
    ),
    'file_public' => array(
        'db_type' => 'file',
        'db_root' => $this->document_root . 'files',
        'www_root' => '/files',
    ),
    'handlers_cache' => array(
        'db_type' => 'file',
        'db_root' => $this->var_dir . 'handlers_cache/',
    ),
);

if (function_exists('posix_getuid') && posix_getuid() == fileowner(__FILE__)) {
    $this->created_files_perm = 0644;
    $this->created_dirs_perm = 0755;
}

$this->modules_priority = 'wizard,packagemanager';

date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.UTF-8');
$this->mail_smtp_host = '127.0.0.1';
$this->mail_smtp_port = '25';
$this->mail_smtp_username = '';
$this->mail_smtp_password = '';
$this->mail_smtp_auth = 0;
$this->mail_from = 'info';
$this->mail_type = 'smtp';

$this->languages = NULL;
//$this->languages=array('ru'=>'RUS','en'=>'ENG');
//$this->languages='tw_languages';
//$this->multilang_default_language_id=2; //default record in languages recordset
//$this->multilang_default_language='en'; //use prior of handler_multilang_base::setlocale_handler

$this->widget_MultiPowUpload_upload_thubnail = true;
$this->widget_MultiPowUpload_thubnail_size = 1600;
$this->widget_MultiPowUpload_license = '00823632444116221712318214925491291871610181';
//$this->widget_MultiPowUpload_watermark='/i/watermark.png';
//$this->watermark_filename=$this->document_root.'i/watermark.png';
//$this->widget_MultiPowUpload_thubnail_quality=90;


setlocale(LC_NUMERIC, 'POSIX');


$this->tpl_data_dir = $this->root_dir . 'HTML';
$this->tpl_var_dir = '/tmp/velotur/templates_c/templates_c' . DIRECTORY_SEPARATOR . basename($this->tpl_data_dir);