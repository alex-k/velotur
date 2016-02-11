<?php
gs_dict::append(array(
	));

class module{%$MODULE_NAME%} extends gs_base_module implements gs_module {
	function __construct() {
	}
	function install() {
		foreach(array(
					'projectfriends',				) as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	function get_menu() {
		$ret=array();
		$item=array();
		$item[]='<a href="/admin/projectfriends/">project friends</a>';
					$item[]='<a href="/admin/projectfriends/projectfriends">projectfriends</a>';				$ret[]=$item;
		return $ret;
	}
	
	static function get_handlers() {
		$data=array(
'get'=>array(
'/admin/projectfriends/projectfriends'=>array(
  'gs_base_handler.show:name:adm_projectfriends.html', 
),
'/admin/projectfriends/projectfriends/delete'=>array(
  'gs_base_handler.delete:{classname:projectfriends}', 
  'gs_base_handler.redirect', 
),
'/admin/projectfriends/projectfriends/copy'=>array(
  'gs_base_handler.copy:{classname:projectfriends}', 
  'gs_base_handler.redirect', 
),
),
'handler'=>array(
'/admin/form/projectfriends'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:form_projectfriends_projectfriends.html:classname:projectfriends:form_class:g_forms_table}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
'/admin/inline_form/projectfriends'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:inline_form.html:classname:projectfriends}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
),
		);
		return self::add_subdir($data,dirname(__file__));
	}

	static function gl($alias,$rec,$data) {
		$fname=dirname(__FILE__).DIRECTORY_SEPARATOR.'gl.php';
		if (file_exists($fname)) {
			$x=include($fname);
			return $x;
		}
		return parent::gl($alias,$rec,$data);
	}

	/*
	static function gl($alias,$rec) {
		if(!is_object($rec)) {
			$obj=new tw{%$MODULE_NAME%};
			$rec=$obj->get_by_id(intval($rec));
		}
		switch ($alias) {
			case '___show____':
				return sprintf('/{%$MODULE%}/show/%s/%d.html',
						date('Y/m',strtotime($rec->date)),
						$rec->get_id());
			break;
		}
	}
	*/
}
/*
class handler{%$MODULE_NAME%} extends gs_base_handler {
}
*/


class projectfriends extends gs_recordset_short {
		public $no_urlkey=true; 	public $no_ctime=true; 	public $orderby="id"; 
	function __construct($init_opts=false) { parent::__construct(array(

				
			'User'=>'lOne2One tw_users verbose_name="User"   widget="parent_list"  required=true  foreign_field_name=userID  ',

						),$init_opts);

						$this->structure['fkeys']=array(
								array('link'=>'User','on_delete'=>'CASCADE','on_update'=>'CASCADE'),
				
				     );
		
			
		
	}
			
	
	
}






?>