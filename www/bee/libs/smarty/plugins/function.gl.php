<?php
function smarty_function_gl($params, $template) {
	$p=$template->getTemplateVars('_gsparams');
	$d=$template->getTemplateVars('_gsdata');
	if (isset($params['module'])) $p['module_name']='module_'.$params['module'];
	if (isset($params['module_name'])) $p['module_name']=$params['module_name'];

	    return cfg('www_root').'/'.ltrim(call_user_func($p['module_name'].'::gl',key($params),current($params),$d['gspgid']),'/');
}

?>
