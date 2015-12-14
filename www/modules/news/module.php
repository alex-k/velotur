<?php
gs_dict::append(array(
		'LOAD_IMAGES'=>'добавить картинки',
	));

class module_news extends gs_base_module implements gs_module {
	function __construct() {
	}
	function install() {
		foreach(array('tw_news','tw_news_stats') as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	function get_menu() {
		return '<a href="/admin/news/">Новости</a>';
	}
	
	static function get_handlers() {
		$data=array(
		'default'=>array(
			'default'=>'gs_base_handler.show404:{name:404.html}',
		),
		'get_post'=>array(
			''=>'gs_base_handler.show:{name:news.html}',
			'*'=>'gs_base_handler.show:{name:news_show.html}',
			'/admin/news'=>'gs_base_handler.show:{name:adm_news.html:classname:tw_news}',
			'/admin/form/tw_news'=>'gs_base_handler.postform:{name:form.html:classname:tw_news:href:/admin/news:form_class:g_forms_table}',
			'/admin/news/delete'=>'admin_handler.deleteform:{classname:tw_news}',
			'images'=>'handler_news.many2one:{name:images.html}',
			'images/show'=>'handler_news.show_images',
			'img/show'=>'handler_news.show_images',
			/*
			'/admin/form/tw_news_images'=>'gs_base_handler.postform:{name:form.html:classname:tw_news_images:form_class:g_forms_table}',
			'/admin/news/iframe_gallery'=>'gs_base_handler.many2one:{name:iframe_gallery.html}',
			*/
		),
		'handler'=>array(
			'/admin/form/tw_news'=>array(
					'gs_base_handler.post:{name:form.html:form_class:g_forms_table:classname:tw_news:return:gs_record}',
					'gs_base_handler.redirect:{href:/admin/news}',
					),
		),
	);
	return self::add_subdir($data,dirname(__file__));
	}

}

class handler_news extends gs_base_handler {
	
 function show_images() {
		 $rs_name=$this->data['gspgid_va'][0];
		 $size=$this->data['gspgid_va'][1];
		 $img_id=$this->data['gspgid_va'][2];
		 $rec=new $rs_name();
		 $rec=$rec->get_by_id($img_id);
		 $gd=new vpa_gd($rec->file_data,false);
		 if ($size>0) {
			  $gd->set_bg_color(255,255,255);
			  $gd->resize($size,$size,'use_box');
		 }
		 $gd->show();
		 exit();
	}
}

//class tw_news extends gs_recordset_short {
class tw_news extends gs_recordset_short {
	const superadmin=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
		'date'=>"fDatetime дата",
		'subject'=>"fString заголовок",
		'text'=>"fText текст widget=wysiwyg images_key=Images",
		//'Images'=>"lMany2One tw_news_images:Parent 'Картинки' widget=iframe_gallery",
		//'Images'=>"fFile Images",
					'Images'=>"lMany2One tw_news_images:Parent 'Картинки' widget=gallery  counter=false",
				'hot'=>"fCheckbox горячая",
		'hidden'=>"fCheckbox спрятать",
		),$init_opts);
		$this->structure['triggers']['before_delete']='stat_news';
		$this->structure['triggers']['before_insert']='stat_news';
		$this->structure['triggers']['before_update']='stat_news';
	}
	
	function stat_news($rec,$type) {
		$o=new tw_news_stats;
		$search=array(
			'year'=>date('Y',strtotime($rec->date)),
			'month'=>date('m',strtotime($rec->date)),
		);
		
		$search_old=array(
			'year'=>date('Y',strtotime($rec->get_old_value('date'))),
			'month'=>date('m',strtotime($rec->get_old_value('date'))),
		);
		switch ($type) {
			case 'before_insert':
				$o->find_records($search)->first(true)->num++;
				$o->commit();
			break;
			case 'before_delete':
				$o->find_records($search)->first(true)->num--;
				$o->commit();
			break;
			case 'before_update':
				if($search==$search_old) break;
				$o->find_records($search_old)->first(true)->num--;
				$o->commit();
				$o->find_records($search)->first(true)->num++;
				$o->commit();
			break;
		}
	}
}

class tw_news_stats extends gs_recordset_short {
	const superadmin=0;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
		'year'=>"fInt 'Год'",
		'month'=>"fInt 'Месяц'",
		'num'=>"fInt 'Количество'",
	),$init_opts);
	}
}

/*
class tw_news_images extends tw_images {
	function __construct($init_opts=false) {
		$this->fields['Parent']="lOne2One tw_news mode=link";
		parent::__construct($this->fields,$init_opts);
		$this->structure['fkeys']=array(
			array('link'=>'Parent','on_delete'=>'CASCADE','on_update'=>'CASCADE'),
		);
	}
        function record_as_string($rec) {
                if (strpos($rec->File_mimetype,'image')===0) {
                        return sprintf('<img src="/img/show/%s/%d.jpg" alt="%s" title="%s">',base64_encode(sprintf('tw_news_images/b/100/100/%d',$rec->get_id())),$rec->File_filename,$rec->File_filename,$rec->get_id());
                }
                return parent::record_as_string($rec);
        }
        public function __toString() {
                return implode(' ',$this->recordset_as_string_array());
        }

}
*/






?>
