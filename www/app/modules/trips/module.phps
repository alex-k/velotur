<?php
gs_dict::append(array(
	));

class module{%$MODULE_NAME%} extends gs_base_module implements gs_module {
	function __construct() {
	}
	function install() {
		foreach(array(
					'countries',				) as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	function get_menu() {
		$ret=array();
		$item=array();
		$item[]='<a href="/admin/trips/">Маршруты</a>';
					$item[]='<a href="/admin/trips/countries">countries</a>';				$ret[]=$item;
		return $ret;
	}
	
	static function get_handlers() {
		$data=array(
'get'=>array(
''=>array(
  'gs_base_handler.show:name:trips.html', 
),
'/admin/trips/tw_trip'=>array(
  'gs_base_handler.show:name:adm_tw_trip.html', 
),
'/admin/trips/tw_trip/delete'=>array(
  'gs_base_handler.delete:{classname:tw_trip}', 
  'gs_base_handler.redirect', 
),
'/admin/trips/tw_trip/copy'=>array(
  'gs_base_handler.copy:{classname:tw_trip}', 
  'gs_base_handler.redirect', 
),
'/calendar'=>array(
  'gs_base_handler.show:name:newcalendar.html:gl:gspgid', 
),
'/admin/trips/countries'=>array(
  'gs_base_handler.show:name:adm_countries.html', 
),
'/admin/trips/countries/delete'=>array(
  'gs_base_handler.delete:{classname:countries}', 
  'gs_base_handler.redirect', 
),
'/admin/trips/countries/copy'=>array(
  'gs_base_handler.copy:{classname:countries}', 
  'gs_base_handler.redirect', 
),
'/calendar.php'=>array(
  'gs_base_handler.show:name:newcalendar.html:gl:gspgid', 
),
'/'=>array(
  'gs_base_handler.show:name:index_page.html:gl:gspgid', 
),
'/c'=>array(
  'gs_base_handler.show:name:index_page.html:gl:gspgid', 
),
),
'handler'=>array(
'form/trip'=>array(
  'gs_base_handler.post:{name:form_trips_countries.html:classname:tw_trip:form_class:g_forms_html}', 
  'gs_base_handler.redirect', 
),
'/admin/form/tw_trip'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:admin_form.html:classname:tw_trip:form_class:g_forms_table}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
'/admin/inline_form/tw_trip'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:inline_form.html:classname:tw_trip}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
'/newcalendar'=>array(
  'gs_base_handler.show:name:newcalendar_inc.html', 
),
'/admin/form/countries'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:admin_form.html:classname:countries:form_class:g_forms_table}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
'/admin/inline_form/countries'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:inline_form.html:classname:countries}', 
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


class countries extends gs_recordset_handler {
			public $no_urlkey=1;
		public $no_ctime=true; 	public $orderby="name"; 
			function __construct($init_opts=false) { parent::__construct(array(

		
			'name'=>'fString verbose_name="name"     required=true unique=true index=true      ',

		
			'name_en'=>'fString verbose_name="name_en"     required=true unique=true index=true      ',

		
			'iso2'=>'fString verbose_name="iso2"    options="2"  required=true unique=true index=true      ',

				
			'Trips'=>'lMany2Many tw_trip verbose_name="Trips"    required=false    ',

						),$init_opts);

				
			
		
	}
			
	
	
}






?>
