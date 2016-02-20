<?php
class module_guides extends gs_base_module implements gs_module  {
	function __construct() { }
	function install() {
		foreach(array('tw_guide',
				//'tw_guide_images',
				) as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	static function get_handlers() {
		$data=array(
		'default'=>array(
			'default'=>'gs_base_handler.show404:{name:404.html}',
		),
		'get_post'=>array(
			''=>'gs_base_handler.show',
			'*'=>'gs_base_handler.show:{name:guide_info.html}',
			'/admin/guide'=>'gs_base_handler.show:{name:adm_guide.html:classname:tw_guide}',
			'/admin/guides'=>'gs_base_handler.show:{name:adm_guides.html:classname:tw_guide}',
			'/admin/guides/history'=>array(
						'gs_base_handler.rec_by_id:classname:tw_guide',
						'gs_base_handler.show:{name:adm_guides_history.html:classname:tw_guide}',
						),
			'/admin/guides/delete'=>'admin_handler.deleteform:{classname:tw_guide}',
			'/admin/guides/iframe_gallery'=>'gs_base_handler.many2one:{name:iframe_gallery.html}',
		),
		'handler'=>array(
			'/admin/form/tw_guide'=>'gs_base_handler.postform:{name:form.html:classname:tw_guide:href:/admin/guides:form_class:g_forms_table}',
			'/admin/form/guide'=>'gs_base_handler.postform:{name:form.html:classname:tw_guide:href:/admin/guide:form_class:g_forms_table}',
			//'/admin/form/tw_guide_images'=>'gs_base_handler.postform:{name:form.html:classname:tw_guide_images:form_class:g_forms_table}',
		),
	);
	return self::add_subdir($data,dirname(__file__));
	}
}
class tw_partner extends gs_recordset_short {
	const superadmin=1;
	var $id_field_name='partnerID';
	var $table_name='Partners';
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
			'partnerLogin'=>"fString Login",
			'partnerPassword'=>"fString Password",
			'partnerName'=>"fString Name",
		),$init_opts);
	}
}
	

class tw_guide extends gs_recordset_short {
	const superadmin=1;
	var $id_field_name='guideID';
	var $table_name='Guide';
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
			//'guideID'=>"fInt",
			'guideLogin'=>"fString Login unique=true",
			'guidePassword'=>"fString Password",
			'guideType'=>"fSelect Type values='guide,admin'",
			'guideName'=>"fText widget=input Имя required=true",
			'guideNameEn'=>"fText widget=input 'Name (en)' required=false",
			'langRU'=>"fCheckbox 'Показывать на RU'",
			'langEN'=>"fCheckbox 'Показывать на EN'",
			'guideAbstract'=>"fText Аннотация required=false",
			'guideAbstractEn'=>"fText 'Аннотация (en)' required=false",
			'guideCV'=>"fText 'Краткая биография' required=false",
			'guideCVEn'=>"fText 'Краткая биография (en)' required=false",
			'guidePhone'=>"fText widget=input Phone required=false",
			'guideEmail1'=>"fText widget=email Email1 required=false",
			'guideEmail2'=>"fText widget=email Email2 required=false",
			'guideWebSite'=>"fString WebSite required=false",
			'guideSkype'=>"fString Skype required=false",
			'guideTwitter'=>"fString Twitter required=false",
			'guideOtherContacts'=>"fText 'Прочие котнакты' required=false",
			'guideHideInfo'=>"fCheckbox 'Скрыть инфу'",
			'userRussianName'=>"fString",
			'userRussianName1'=>"fString Фамилия required=false",
			'userRussianName2'=>"fString Имя required=false",
			'userRussianName3'=>"fString Отчество required=false",
			'userLatinName'=>"fString required=false",
			'userLatinName1'=>"fString 'Фамилия латиницей' required=false",
			'userLatinName2'=>"fString 'Имя латиницей' required=false",
			'userLatinName3'=>"fString 'Отчество латиницей' required=false",
			'userBirthDay'=>"fString 'Дата рождения (yyyy-mm-dd)' required=false",
			'userCitizenship'=>"fString Гражданство required=false",
			'userSex'=>"fSelect Пол values='Male,Female' required=false",
			'userCountry'=>"fString Country required=false",
			'userCity'=>"fString City required=false",
			'userAddress'=>"fText Address required=false",
			'userJob'=>"fText 'Место работы' required=false",
			'userPassport'=>"fString 'Номер паспорта' required=false",
			'userPassportIssuedBy'=>"fText widget=input 'кем выдан' required=false",
			'userPassportValidThrow'=>"fString 'годен до' required=false",
			//'userPhone'=>"fString Phone required=false",
			'userVPNumber'=>"fString 'номер ВП' default='GUIDE' required=false",
			'guideToursCount'=>"fInt default=00 index=true",
			//'Images'=>"lMany2One tw_guide_images:Parent 'Фотографии' widget=iframe_gallery local_field_name=guideID counter=false",
			'Tours'=>"lMany2One tw_tours:Guide local_field_name=guideID counter=false",
			'Tours2'=>"lMany2One tw_tours:Guide2 local_field_name=guideID counter=false",
			'Reports'=>'lMany2One reports:Guide local_field_name=guideID counter=false',

							'Images'=>"lMany2One tw_guides_images:Parent 'Картинки' widget=gallery  counter=false",
			
		),$init_opts);
                $this->structure['triggers']['before_insert']='before';
                $this->structure['triggers']['before_update']='before';

		$this->structure['recordsets']['Images']['local_field_name']='guideID';
		$this->structure['htmlforms']['Images']['options']['local_field_name']='guideID';

		//$this->id_field_name='guideID';
		//$this->structure['recordsets']['Customer']['local_field_name']='transactionCustomerID';
		//$this->structure['recordsets']['Customer']['foreign_field_name']='customerID';
	}

	function before($rec) {
		$rec->userRussianName=sprintf("%s %s %s",$rec->userRussianName1,$rec->userRussianName2,$rec->userRussianName3);
		$rec->userLatinName=sprintf("%s %s %s",$rec->userLatinName1,$rec->userLatinName2,$rec->userLatinName3);
	}
	
}
class tw_guides extends tw_guide {}

/*
class tw_guide_images extends tw_images {
	function __construct($init_opts=false) {
		$this->fields['Parent']="lOne2One tw_guide mode=link";
		parent::__construct($this->fields,$init_opts);
		$this->structure['fkeys']=array(
			array('link'=>'Parent','on_delete'=>'CASCADE','on_update'=>'CASCADE'),
		);
	}
        function record_as_string($rec) {
                if (strpos($rec->File_mimetype,'image')===0) {
                        return sprintf('<img src="/img/show/%s/%d.jpg" alt="%s" title="%s">',base64_encode(sprintf('%s/b/100/100/%d',get_class($this),$rec->get_id())),$rec->File_filename,$rec->File_filename,$rec->get_id());
                }
                return parent::record_as_string($rec);
        }
        public function __toString() {
                return implode(' ',$this->recordset_as_string_array());
        }

}
*/
?>
