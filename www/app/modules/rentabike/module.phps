<?php
gs_dict::append(array(
	));

class module{%$MODULE_NAME%} extends gs_base_module implements gs_module {
	function __construct() {
	}
	function install() {
		foreach(array(
					'bike',					'bikeset',					'bikeinfo',				) as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	function get_menu() {
		$ret=array();
		$item=array();
		$item[]='<a href="/admin/rentabike/">rentabike</a>';
					$item[]='<a href="/admin/rentabike/bike">bike</a>';					$item[]='<a href="/admin/rentabike/bikeset">bikeset</a>';					$item[]='<a href="/admin/rentabike/bikeinfo">bikeinfo</a>';				$ret[]=$item;
		return $ret;
	}
	
	static function get_handlers() {
		$data=array(
'get'=>array(
'/admin/rentabike/manager/bike'=>array(
  'gs_base_handler.show:name:bootstrap_table_bike.html', 
),
'/admin/rentabike/manager/bike/delete'=>array(
  'gs_base_handler.delete:{classname:bike}', 
  'gs_base_handler.redirect', 
),
'/admin/rentabike/manager/bike/copy'=>array(
  'gs_base_handler.copy:{classname:bike}', 
  'gs_base_handler.redirect', 
),
'/admin/rentabike/manager/bikeset'=>array(
  'gs_base_handler.show:name:bootstrap_table_bikeset.html', 
),
'/admin/rentabike/manager/bikeset/delete'=>array(
  'gs_base_handler.delete:{classname:bikeset}', 
  'gs_base_handler.redirect', 
),
'/admin/rentabike/manager/bikeset/copy'=>array(
  'gs_base_handler.copy:{classname:bikeset}', 
  'gs_base_handler.redirect', 
),
'manager/bike'=>array(
  'gs_base_handler.show:name:bootstrap_table_bike.html', 
),
'manager/bike/delete'=>array(
  'gs_base_handler.delete:{classname:bike}', 
  'gs_base_handler.redirect', 
),
'manager/bike/copy'=>array(
  'gs_base_handler.copy:{classname:bike}', 
  'gs_base_handler.redirect', 
),
'/admin/rentabike/manager/bikeinfo'=>array(
  'gs_base_handler.show:name:bootstrap_table_bikeinfo.html', 
),
'/admin/rentabike/manager/bikeinfo/delete'=>array(
  'gs_base_handler.delete:{classname:bikeinfo}', 
  'gs_base_handler.redirect', 
),
'/admin/rentabike/manager/bikeinfo/copy'=>array(
  'gs_base_handler.copy:{classname:bikeinfo}', 
  'gs_base_handler.redirect', 
),
'manager/bikeset'=>array(
  'gs_base_handler.show:name:bootstrap_table_bikeset.html', 
),
'manager/bikeset/delete'=>array(
  'gs_base_handler.delete:{classname:bikeset}', 
  'gs_base_handler.redirect', 
),
'manager/bikeset/copy'=>array(
  'gs_base_handler.copy:{classname:bikeset}', 
  'gs_base_handler.redirect', 
),
'manager/bikeinfo'=>array(
  'gs_base_handler.show:name:bootstrap_table_bikeinfo.html', 
),
'manager/bikeinfo/delete'=>array(
  'gs_base_handler.delete:{classname:bikeinfo}', 
  'gs_base_handler.redirect', 
),
'manager/bikeinfo/copy'=>array(
  'gs_base_handler.copy:{classname:bikeinfo}', 
  'gs_base_handler.redirect', 
),
),
'handler'=>array(
'manager/form/bike'=>array(
  'gs_base_handler.post:{name:form__bike.html:classname:bike:form_class:g_forms_table}', 
  'gs_base_handler.redirect_up:level:2', 
),
'manager/form/bikeset'=>array(
  'gs_base_handler.post:{name:form__bikeset.html:classname:bikeset:form_class:g_forms_table}', 
  'gs_base_handler.redirect_up:level:2', 
),
'manager/form/bikeinfo'=>array(
  'gs_base_handler.post:{name:form__bikeinfo.html:classname:bikeinfo:form_class:g_forms_table}', 
  'gs_base_handler.redirect_up:level:2', 
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


class bike extends gs_recordset_short {
			public $no_urlkey=1;
			public $orderby="id"; 
			function __construct($init_opts=false) { parent::__construct(array(

		
			'frame_no'=>'fString verbose_name="frame_no"     required=true  index=true      ',

		
			'size'=>'fString verbose_name="size"     required=false  index=true      ',

		
			'year'=>'fInt verbose_name="year"     required=true  index=true      ',

		
			'info'=>'fText verbose_name="info"     required=false        ',

				
			'Set'=>'lOne2One bikeset verbose_name="Set"   widget="parent_list"  required=true    ',

		
			'Model'=>'lOne2One bikeinfo verbose_name="Model"   widget="parent_list"  required=true    ',

						),$init_opts);

						$this->structure['fkeys']=array(
								array('link'=>'Set','on_delete'=>'SET_NULL','on_update'=>'CASCADE'),
				
								array('link'=>'Model','on_delete'=>'SET_NULL','on_update'=>'CASCADE'),
				
				     );
		
			
		
	}
			
	
	
}


class bikeset extends gs_recordset_short {
			public $no_urlkey=1;
			public $orderby="id"; 
			function __construct($init_opts=false) { parent::__construct(array(

		
			'name'=>'fString verbose_name="name"     required=true unique=true index=true      ',

				
			'Bikes'=>'lMany2One bike:Set verbose_name="Bikes"    required=false    ',

						),$init_opts);

				
			
		
	}
			
	
	
}


class bikeinfo extends gs_recordset_short {
			public $no_urlkey=1;
			public $orderby="id"; 
			function __construct($init_opts=false) { parent::__construct(array(

		
			'model'=>'fString verbose_name="model"     required=true unique=true index=true      ',

		
			'link'=>'fString verbose_name="link"     required=false        ',

				
			'Bikes'=>'lMany2One bike:Model verbose_name="Bikes"    required=false    ',

						),$init_opts);

				
			
		
	}
			
	
	
}






?>
