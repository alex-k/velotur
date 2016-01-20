<?php
class module_discounts extends gs_base_module implements gs_module {
	function install() {
		foreach(array('tw_promotions','tw_payments','tw_promotions','tw_tour_promotions') as $r){
			$this->$r=new $r;
			$this->$r->install();
		}
	}
	
	static function get_handlers() {
		$data=array(
		'default'=>array(
			'default'=>'gs_base_handler.show404:{name:404.html}',
		),
		'get'=>array(
			''=>'gs_base_handler.show',
			'*'=>'gs_base_handler.show:{name:discount_info.html}',
			'/promotion'=>'gs_base_handler.show',
			'/admin/promotions'=>'gs_base_handler.show',
			'/admin/promotions/delete'=>array(
					'gs_base_handler.delete:{classname:tw_promotions}',
					'gs_base_handler.redirect',
					),
			'/admin/discounts'=>'gs_base_handler.show:{name:adm_discounts.html:classname:tw_discount}',
			'/admin/discounts/delete'=>array(
					'gs_base_handler.delete:{classname:tw_discount}',
					'gs_base_handler.redirect',
					),
			'/admin/payments/delete'=>array(
					'gs_base_handler.delete:{classname:tw_payments}',
					'gs_base_handler.redirect',
					),
			'/partners/payments/delete'=>array(
					'gs_base_handler.delete:{classname:tw_payments}',
					'gs_base_handler.redirect',
					),
			'/admin/tour_promotions/delete'=>array(
					'gs_base_handler.delete:{classname:tw_tour_promotions}',
					'gs_base_handler.redirect',
					),
			'/admin/tour_promotions'=>'gs_base_handler.show',
		),
		'handler'=>array(
			'/user_tour_promotions/'=>'gs_base_handler.show',
			'/admin/form/tw_discount'=>'gs_base_handler.postform:{name:form_payments.html:classname:tw_discount:href:/admin/discounts:form_class:g_forms_divbox}',
			'/admin/form/tw_tour_promotions/*'=>array(
					'gs_base_handler.post:{name:form_tour_promotions.html:classname:tw_tour_promotions:form_class:g_forms_table}',
					//'gs_base_handler.redirect_gl:gl:adm_upd_tour',
					'gs_base_handler.redirect',
					),
			'/admin/*/form/tw_payments/*'=>array(
					'gs_base_handler.post:{name:form_payments.html:classname:tw_payments:form_class:g_forms_divbox}',
					'gs_base_handler.redirect_gl:gl:adm_upd_payments',
					),
			'/admin/form/tw_promotions'=>array(
					'gs_base_handler.post:{name:form.html:classname:tw_promotions:form_class:g_forms_table}',
					'gs_base_handler.redirect',
					),
			'/admin/discounts/select'=>'gs_base_handler.show:{name:adm_discounts_select.html}',
			'/admin/payment'=>'gs_base_handler.show:{name:adm_payment.html}',
			'/admin/payment_js'=>'gs_base_handler.show:{name:adm_payment_js.html}',
			'/payments/total'=>'discounts_handler.total',
			'/user_payments'=>'gs_base_handler.show:{name:user_payments.html}',
			'/admin/tour_promotions_js'=>'gs_base_handler.show',
			/*
			'/admin/tour_promotions'=>'gs_base_handler.show',
			*/
		),
	);
		return self::add_subdir($data,dirname(__file__));
	}
	static function gl($alias,$rec,$data) {
		switch ($alias) {
			case 'adm_upd_payments':
				return sprintf('/'.$data['gspgid_a'][1].'/tourinfo.php?tourID=%d',$rec->tourID);
			break;
			case 'adm_upd_tour':
				return sprintf('/admin/tour_edit.php?_id=%d',$rec->Tour_id);
			break;
		}
	}
}

class discounts_handler extends gs_base_handler {
	function total() {
		$ps=new tw_payments();
		return $ps->calc_total($this->data['gspgid_va'][0],$this->data['gspgid_va'][1]);
		//$ps->find_records(array('tourID'=>$this->data['gspgid_va'][0], 'userID'=>$this->data['gspgid_va'][1] ));

	}
}

class tw_discount extends gs_recordset_short {
	var $no_ctime=1;
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
			'Type'=>'fSelect Тип values="скидка,оплата,услуга,задолженность,комиссия" widget=select',
			'Title'=>'fString Название required=false',
			'Amount'=>'fFloat "цена" required=false',
			'AmountType'=>'fSelect Тип values="евро,%"',
			//'Auto'=>'fCheckbox "применять автоматически"',
			'AutoKey'=>'fString "идентификатор(для авторежима)" required=false',
			'ShowInList'=>'fCheckbox "выводить в списке" default=1',
			'ListOrder'=>'fInt "номер в списке" required=false',
			'Hidden'=>'fCheckbox "служебная"',
		),$init_opts);

	}
}
class tw_promotions extends gs_recordset_short {
	var $no_urlkey=1;
	var $no_ctime=1;
	function __construct($init_opts=false) { parent::__construct(array(
			//'Type'=>'fSelect Тип values="рецидивист,ранняя бронь" widget=select',
			'Title'=>'fString Название',
			'Text'=>'fText Описание required=false',
			'Amount'=>'fFloat "цена" required=false',
			'AmountType'=>'fSelect " " values="евро,%"',
			//'EndDate'=>'fString "дата окончания ( 1 week, 5 days)" required=false',
			'AutoKey'=>'fString "идентификатор(для авторежима)" required=false',
		),$init_opts);

	}
}

class tw_tour_promotions extends gs_recordset_short{
	var $no_urlkey=1;
	var $no_ctime=1;
	function __construct($init_opts=false) { parent::__construct(array(
			'Title'=>'fString Название',
			'Text'=>'fText Описание required=false',
			'Type'=>'fSelect default="скидка"',
			'Amount'=>'fFloat "цена" required=false',
			'AmountType'=>'fSelect " " values="евро"',
			'EndDate'=>'fDateTime "дата окончания" required=false',
			'AutoKey'=>'fString "идентификатор(для авторежима)" required=false',
			'Tour'=>'lOne2One tw_tours',
		),$init_opts);

	}

	function filter() {
		$ret=new tw_tour_promotions();

		foreach ($this as $p) {
			if (in_array($p->AutoKey, array('early2','earlybooking')) && strtotime($p->EndDate)>=strtotime('today')) {
				$ret->add_element($p);
				continue;
			}
		}


		return $ret;
	}
}

class tw_payments extends gs_recordset_short {
	var $no_urlkey=1;
	function __construct($init_opts=false) { parent::__construct(array(
			'Type'=>'fSelect verbose_name=" " values="оплата,скидка,долг,инфо" widget=select cssclass=hidden',
			'Amount'=>'fFloat "сумма" ',
			'Title'=>'fString описание',
			'Note'=>'fString "примечание (например номер квитанции или визы)" required=false',
			'Hidden'=>'fCheckbox " " cssclass=hidden',
			//'tourID'=>'fInt',
			//'userID'=>'fInt',
			'Tour'=>'lOne2One tw_tours local_field_name="tourID" foreign_field_name="tourID"',
			'User'=>'lOne2One tw_users local_field_name="userID" foreign_field_name="userID"',
			'Guide'=>'lOne2One tw_guide foreign_field_name="guideID" required=false',
			'Partner'=>'lOne2One tw_partner foreign_field_name="partnerID" required=false',
			'Discount'=>'lOne2One tw_discount  required=false',
		),$init_opts);

		$this->structure['triggers']['after_update']='after';
		$this->structure['triggers']['after_insert']='after';
		$this->structure['triggers']['before_delete']='after';

		//md($this->structure,1);
	}
	function calc_total($tourID,$userID,$total=NULL) {
		if ($total===NULL) {
			$tw_tour=record_by_id($tourID,'tw_tours');
			$total=$tw_tour->tourPrice1;
		}
		$ps=new tw_payments();
		$ps->find_records(array('tourID'=>$tourID, 'userID'=>$userID));
		foreach ($ps as $p) {
			if ($p->Type=='оплата' || $p->Type=='скидка') $total-=$p->Amount;
				else if ($p->Type!='инфо') $total+=$p->Amount;	
		}

		$ps->after($ps->first(),'after_update');
		return sprintf('%.02f',$total);
	}
	function after($rec,$type) {
		$tourID=$rec->tourID;
		$userID=$rec->userID;


		$ps=new tw_payments;
		$ps->find_records(array('tourID'=>$tourID,'userID'=>$userID,'Hidden'=>0));
		if ($type=='before_delete') $ps=$ps->find(array(array('field'=>'id','case'=>'!=','value'=>$rec->get_id())));

		$tus=new tw_tour_users;
		$tus->find_records(array('tourID'=>$tourID,'userID'=>$userID));

		$tu=$tus->first();
		$tu->tourUserCommentsPrepay=trim($ps);
		$tu->commit();
	}	
	function record_as_string($rec) {
		return "$rec->Amount $rec->Title $rec->Note";
	}
        public function __toString() {
                return implode("\n",$this->recordset_as_string_array());
        }
}
	



?>
