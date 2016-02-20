<?php
class velotur_module extends gs_base_module implements gs_module {
	function __construct() {}
	
	function install() {
		$n=new tw_handlers;
		$n->install();
		$n=new tw_handlers_cache;
		$n->install();
	}
	
	static function get_handlers() {
		$data=array(
			'get_post'=>array(
				''=>'gs_base_handler.show:{name:newcalendar.html}',
				'/admin'=>'admin_handler.show:{name:admin_page.html}',
				'/admin/logout'=>array(
					  'admin_handler.post_logout:return:true',
					  'gs_base_handler.redirect',
					),
				'*'=>'gs_base_handler.show404:{name:404.html}',
			),
			'handler'=>array(
				'/admin/menu'=>'admin_handler.show_menu',
				'/admin/login'=>array(        
					  'admin_handler.check_login:return:true^show', 
					  'show'=> 'gs_base_handler.show:name:admin_login.html',
				  ),              
				 '/admin/form/login'=>array(
					  'admin_handler.post_login:return:true:form_class:form_admin_login',
					  'gs_base_handler.redirect',
				  ),


				'/filter'=>'gs_filters_handler.init',
				'/filter/show'=>'gs_filters_handler.show',
				'/debug'=>'debug_handler.show',
			),
		);
		return self::add_subdir($data,dirname(__file__));
	}
	static function gl($name,$record,$data) {
		switch ($name) {
			case 'save_cancel':
				return $data['handler_key_root'];
			case 'save_continue':
				return $data['gspgid_root'];
			case 'save_return':
				return $data['handler_key_root'];
			break;
			}
		return null;
	}
}
