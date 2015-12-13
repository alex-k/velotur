<?php
class module_tours extends gs_base_module implements gs_module  {
	function __construct() { }
	function install() {
		foreach(array(//'tw_user',
				//'tw_user_images',
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
			'/admin/tourinfo'=>'gs_base_handler.show:{name:adm_tourinfo.html}',
			'/admin/list/finance'=>'gs_base_handler.show:{name:adm_list_finance.html}',
		),
		'handler'=>array(
		),
	);
	return self::add_subdir($data,dirname(__file__));
	}
}

class tw_trip extends gs_recordset_short{
	var $table_name='Trip';
	var $id_field_name='tripID';
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) {
		parent::__construct(array(
			'tripTitle'=>'fString',
			'tripTitleEn'=>'fString',
			'langRU'=>"fCheckbox",
			'langEN'=>"fCheckbox",
			'tripDescriptionURL'=>'fString',
			'tripHidden'=>'fCheckbox',
			'tripRoute'=>'fText',
			'tripRouteEn'=>'fText',
			'tripDescription'=>'fText',
			'tripDescriptionEn'=>'fText',
			'tripDescriptionURL'=>'fString',
			'tripDifficulty'=>'fString',
			'tripComfort'=>'fString',
			'tripPrice1'=>'fString Price',
			'tripPriceTitle1'=>'fString',
			'tripPriceEn'=>'fString PriceEn',
			'Tours'=>'lMany2One tw_tours:Trip local_field_name=tripID foreign_field_name=tourID counter=false',
			'Reports'=>'lMany2One reports:Trip local_field_name=tripID',
			'Countries'=>'lMany2Many countries:m2m_countries_tw_trip local_field_name=tripID Страны required=false  widget=lMany2Many_chosen counter=false',
		),$init_opts);
	}
}

class tw_tours extends gs_recordset_short{
	var $table_name='Tour';
	var $id_field_name='tourID';
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) {
		parent::__construct(array(
			'tourTitle'=>'fString',
			'tourTitleEn'=>'fString',
			'langRU'=>"fCheckbox",
			'langEN'=>"fCheckbox",
			'tourStartDate'=>'fDateTime',
			'tourEndDate'=>'fDateTime',
			'tourPrice1'=>'fString',
			'tourPriceTitle1'=>'fString',
			'guideName'=>'fString',
			'guideName2'=>'fString',
			'tourDescriptionURL'=>'fString',
			'tourDifficulty'=>'fString',
			'tourComfort'=>'fString',
			'tourStatus'=>'fString',
			'tourAppStartDate'=>'fDateTime',
			'tourAppEndDate'=>'fDateTime',
			'tourAppStartDate'=>'fDateTime',
			'tourAvPlaces'=>'fInt',
			'tourShowAvPlaces'=>'fInt',
			'tourApplyOnlyExpired'=>'fInt',
			'Guide'=>'lOne2One tw_guide local_field_name=guideID',
			'Guide2'=>'lOne2One tw_guide local_field_name=guideID2',
			'Promotions'=>'lMany2One tw_tour_promotions:Tour local_field_name=tourID',
			'Payments'=>'lMany2One tw_payments:Tour local_field_name=tourID',
			'Users'=>'lMany2One tw_tour_users:Tour local_field_name=tourID counter=false',
			'Trip'=>'lOne2One tw_trip local_field_name=tripID foreign_field_name=tripID',
//			'Countries'=>'lMany2Many countries:m2m_countries_tw_trip:tw_trip_id local_field_name=tripID Страны required=false  widget=lMany2Many_chosen counter=false',
		),$init_opts);
	}
	function record_as_string($rec) {
		if(!$rec->tourTitle) return '';	
		return $rec->tourDescriptionURL>'' ? sprintf('<a href="%s">%s</a>',$rec->tourDescriptionURL,$rec->tourTitle) : $rec->tourTitle;
	}
	public function __toString() {
                return implode(', ',array_unique($this->recordset_as_string_array()));
	}

	function getTourStatus() {
		if (!isset($this->tour_status)) {
			$fname=realpath(dirname(__FILE__).'/../../../classes/tour_status.php');
			include($fname);
			$this->tour_status=$valuesarray;
		}
		return $this->tour_status;
	}


	function fill_values($rec,$data) {
		$st=$this->getTourStatus();
		$rec->tourStatusText=$st[trim($rec->tourStatus)];
		$rec->tourAllowApply=$this->_allowApply($rec);
		$rec->tourAllowWL=$this->_allowWaitinglist($rec);
		$rec->tourAllowPreApply=$this->_allowPreApply($rec);

	}

	function _allowPreApply($rec) {
		return $this->_allowWaitinglist($rec) && strtotime($rec->tourAppStartDate)>strtotime("now");
	}

	function _allowWaitinglist($rec) {
		return $rec->tourStatus=='normal' || 
			$rec->tourStatus=='waitinglist'
			;
	}

	function _allowApply($rec) {
		$dt=date("Y-m-d");

		if ($rec->tourStatus!='normal') return false;
		if (!($rec->tourAvPlaces>0)) return false;
		if (strtotime($rec->tourAppStartDate)!=0 && strtotime($dt)<strtotime($rec->tourAppStartDate)) return false;
		//if (strtotime($rec->tourAppEndDate)!=0 && strtotime($dt)>strtotime($rec->tourAppEndDate)) return false;

		return true;
	}
	
}
class tw_tour_users extends gs_recordset_short {
	var $id_field_name='userID';
	var $table_name='TourUsers';
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
			//'userID'=>"fInt",
			//'User'=>'lOne2One tw_users local_field_name="userID" foreign_field_name="userID"',
			'Tour'=>'lOne2One tw_tours local_field_name="tourID" foreign_field_name="tourID"',
			'tourID'=>"fInt",
			'tourUserCommentsPrepay'=>"fString",
			'tourUserType'=>"fString",
		),$init_opts);
	}
}

class tw_users extends gs_recordset_short {
	const superadmin=1;
	var $id_field_name='userID';
	var $table_name='User';
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
			//'userID'=>"fInt",
			//'userLogin'=>"fString Login",
			'userEmail'=>"fText widget=email Email required=false",
			'userPassword'=>"fString Password",
			'userName'=>"fText widget=input Name required=true",

			//'userAbstract'=>"fText Аннотация required=false",
			//'userCV'=>"fText 'Краткая биография' required=false",
			//'userEmail1'=>"fText widget=email Email1 required=false",
			//'userEmail2'=>"fText widget=email Email2 required=false",
			//'userWebSite'=>"fString WebSite required=false",
			//'userSkype'=>"fString Skype required=false",
			//'userTwitter'=>"fString Twitter required=false",
			//'userOtherContacts'=>"fText 'Прочие котнакты' required=false",
			//'userHideInfo'=>"fCheckbox 'Скрыть инфу'",

			'userRussianName'=>"fString",
			'userRussianName1'=>"fString Фамилия required=false",
			'userRussianName2'=>"fString Имя required=false",
			'userRussianName3'=>"fString Отчество required=false",
			'userLatinName'=>"fString required=false",
			'userLatinName1'=>"fString 'Фамилия латиницей' required=false",
			'userLatinName2'=>"fString 'Имя латиницей' required=false",
			'userLatinName3'=>"fString 'Отчество латиницей' required=false",
			'userBirthDay'=>"fString 'Дата рождения' required=false",
			'userCitizenship'=>"fString Гражданство required=false",
			'userSex'=>"fSelect Пол values='Male,Female' required=false",
			'userCountry'=>"fString Country required=false",
			'userCity'=>"fString City required=false",
			'userAddress'=>"fText Address required=false",
			'userJob'=>"fText 'Место работы' required=false",
			'userPassportType'=>"fString 'Тип паспорта' required=false",
			'userPassport'=>"fString 'Номер паспорта' required=false",
			'userPassportIssuedBy'=>"fText widget=input 'кем выдан' required=false",
			'userPassportIssuedDate'=>"fString 'дата выдачи' required=false",
			'userPassportValidThrow'=>"fString 'годен до' required=false",
			'userPhone'=>"fString Phone required=false",
			'userVPNumber'=>"fString 'номер ВП' default='GUIDE' required=false",
			'userType'=>"fSelect Type values='regular,guard,block' required=false",
			'userReferalID'=>"fInt required=false",
			'userRegistrationDate'=>"fString required=false",
			'userInfoHowFound'=>"fText required=false",
			'userPartnerID'=>"fInt",
			'userCompletedTours'=>"fInt",

			'Reports'=>'lMany2One reports:User index_field_name=Tour_id local_field_name=userID counter=false',
			'Mails'=>'lMany2Many mailhistory:m2m_mailhistory_tw_users local_field_name=userID required=false counter=false widget=lMany2Many_chosen',
			//'Tours'=>'lMany2One tw_tour_users:User local_field_name=userID counter=false',

			
		),$init_opts);
                $this->structure['triggers']['before_insert']='before';
                $this->structure['triggers']['before_update']='before';

	}

	function before($rec) {
		$rec->userRussianName=sprintf("%s %s %s",$rec->userRussianName1,$rec->userRussianName2,$rec->userRussianName3);
		$rec->userLatinName=sprintf("%s %s %s",$rec->userLatinName1,$rec->userLatinName2,$rec->userLatinName3);
	}

	public function record_as_string($rec) {
		return $rec->userRussianName ? $rec->userRussianName : $rec->userLatinName;
	}
	
}
?>
