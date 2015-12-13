<?php

function smarty_function_beehandler_init($params, &$smarty) {
	if (isset($params['path']) && !class_exists('gs_base_handler',0)) {
		require_once($params['path'].'/libs/config.lib.php');
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
	}
}
function smarty_function_beehandler($params, &$smarty) {
	smarty_function_beehandler_init($params,$smarty);
	$smarty->assign('_gsdata',cfg('init_data'));
	$params['gspgtype'] = $_SERVER['REQUEST_METHOD']=='POST' ? 'post' : 'get';
	$ret=gs_base_handler::process_handler($params,$smarty);

		if (DEBUG) {
			$g=gs_logger::get_instance();
			$g->console();
		}
	return $ret;

}
?>
