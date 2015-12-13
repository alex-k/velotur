<?php

class gs_filter_calendar_vt extends gs_filter_like {
	static function month($n=NULL) {
		if (!$n) return "";
		//setlocale(LC_TIME, 'ru_RU.UTF8');
		$timestamp = mktime(0, 0, 0, $n, 1, 2005);
		return strftime("%B", $timestamp);
	}
	function applyFilter($options,$rs) {
		if (empty($this->value)) return $options;
		$dates=array_map('trim',explode(' - ',$this->value));
		$date1=array_shift($dates);
		$date2=$dates ? array_shift($dates) : $date1;

		$year=gs_filters_handler::value('year');
		if (!$year) {
			$year=date('Y');
			if (date('m')>$this->value) $year++;
		}
		$date=strtotime("$year-01-01 +".($this->value-1)." month");
		$start_date=strtotime('-3 day',$date);
		$end_date=strtotime('+1 month +3 day',$date);

		$to=array(
			'type'=>'condition',
			'condition'=>'OR',
		);
		foreach ($this->fields as $field) {
			$to[]=array(
				'type'=>'condition',
				'condition'=>'AND',

				array(
					'type'=>'value',
					'field'=>$field,
					'value'=>date(DATE_ATOM,$start_date),
					'case'=>'>=',
				),
				array(
					'type'=>'value',
					'field'=>$field,
					'value'=>date(DATE_ATOM,$end_date),
					'case'=>'<',
				),
			);

		}


		$options[$this->name]=$to;
		return $options;
	}
}
class gs_filter_calendar_vt_year extends gs_filter_like {
	function applyFilter($options,$rs) {
		if (empty($this->value)) return $options;
		$year=$this->value;
		$date=strtotime("$year-01-01");
		$start_date=strtotime('-1 month',$date);
		$end_date=strtotime('+1 year +1 month',$date);

		$to=array(
			'type'=>'condition',
			'condition'=>'OR',
		);
		foreach ($this->fields as $field) {
			$to[]=array(
				'type'=>'condition',
				'condition'=>'AND',

				array(
					'type'=>'value',
					'field'=>$field,
					'value'=>date(DATE_ATOM,$start_date),
					'case'=>'>=',
				),
				array(
					'type'=>'value',
					'field'=>$field,
					'value'=>date(DATE_ATOM,$end_date),
					'case'=>'<=',
				),
			);

		}


		$options[$this->name]=$to;
		return $options;
	}
}

