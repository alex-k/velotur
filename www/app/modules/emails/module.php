<?php
gs_dict::append(array(
	));

class module_emails extends gs_base_module implements gs_module {
	function __construct() {
	}
	function install() {
		foreach(array(
					'mailhistory',				) as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	function get_menu() {
		$ret=array();
		$item=array();
		$item[]='<a href="/admin/emails/">Рассылки</a>';
					$item[]='<a href="/admin/emails/mailhistory">mailhistory</a>';				$ret[]=$item;
		return $ret;
	}
	
	static function get_handlers() {
		$data=array(
'handler'=>array(
'mailhistory'=>array(
 'rec'=> 'gs_base_handler.rec_by_id:classname:tw_tours', 
  'gs_base_handler.show:name:mailhistory.html:gl:gspgid', 
),
'/admin/form/mailhistory'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:admin_form.html:classname:mailhistory:form_class:g_forms_table}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
'/admin/inline_form/mailhistory'=>array(
  'gs_base_handler.redirect_if:gl:save_cancel:return:true', 
  'gs_base_handler.post:{name:inline_form.html:classname:mailhistory}', 
  'gs_base_handler.redirect_if:gl:save_continue:return:true', 
  'gs_base_handler.redirect_if:gl:save_return:return:true', 
),
),
'get'=>array(
'mailhistory/show'=>array(
  'gs_base_handler.rec_by_id:classname:mailhistory:return:gs_record^e404', 
  'gs_base_handler.show:name:mailhistory_details.html', 
 'end'=> 'end', 
 'e404'=> 'gs_base_handler.show404', 
),
'/admin/emails/mailhistory'=>array(
  'gs_base_handler.show:name:adm_mailhistory.html', 
),
'/admin/emails/mailhistory/delete'=>array(
  'gs_base_handler.delete:{classname:mailhistory}', 
  'gs_base_handler.redirect', 
),
'/admin/emails/mailhistory/copy'=>array(
  'gs_base_handler.copy:{classname:mailhistory}', 
  'gs_base_handler.redirect', 
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
			$obj=new tw_emails;
			$rec=$obj->get_by_id(intval($rec));
		}
		switch ($alias) {
			case '___show____':
				return sprintf('/emails/show/%s/%d.html',
						date('Y/m',strtotime($rec->date)),
						$rec->get_id());
			break;
		}
	}
	*/
}
/*
class handler_emails extends gs_base_handler {
}
*/


class mailhistory extends gs_recordset_short {
			public $no_urlkey=1;
			public $orderby="id"; 
			function __construct($init_opts=false) { parent::__construct(array(

		
			'mailSubject'=>'fString verbose_name="Subject"     required=false        ',

		
			'mailText'=>'fText verbose_name="Text"     required=false        ',

		
			'mailFile'=>'fFile verbose_name="File"     required=false        ',

		
			'mailStatus'=>'fString verbose_name="Status"     required=false        ',

		
			'mailCheckedEmails'=>'fText verbose_name="Emails"     required=false        ',

		
			'mailTestMode'=>'fCheckbox verbose_name="Test mode"     required=false        ',

		
			'recipients'=>'fText verbose_name="recipients"     required=false        ',

		
			'fromEmail'=>'fString verbose_name="From"     required=false        ',

				
			'Tour'=>'lOne2One tw_tours verbose_name="Tour"    required=false  foreign_field_name=tourID local_field_name=tourID  ',

						),$init_opts);

						$this->structure['fkeys']=array(
						
				     );
		
			
		
	}
			
	
	
}






?>
