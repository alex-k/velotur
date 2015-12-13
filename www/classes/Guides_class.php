<? 

class Guides extends BaseClass {
	function  __construct($id=false) {
		$this->_table="Guide";
		$this->_idfield="guideID";
		$this->_LINKED = array (
		       array (
			       'classname'=>'Tours',
				'type'=>'many2one',
				'classLinkField'=>'guideID',
				'idField'=>'guideID',
			       )
		       );


		$this->_FIELDTYPES= array(
			'guideID' => 'hidden',
			);
		$this->_CHECKFIELDS= array(
			'guideID' => 'required',
			'guideName' => 'required',
			);
		$this->_FIELDNAMES= array(
			'guideID' => '#',
			'guideName' => 'Имя Гида',
			'guidePhone' => 'Телефон',
			'guideEmail1' => 'Email',
			'guideEmail2' => 'Дополнительный email',
			);




		return parent::__construct($id);
	}

	public function __toString() {
		return $this->getInfo();
	}
	function auth($login,$password) {
		global $DBCLASS;
		if (empty($login)) return false;
		$res=$DBCLASS->getRowByField($this->_table,'guideLogin',$login);
		if ($res->guidePassword==$password) {
			//return $this->loadFromDB($res->{$this->_idfield});
			return $this->__construct($res->{$this->_idfield});
		}
		return false;
	}
	function getValues() {
		$r=parent::getValues();
		$r[tourUserComments]='GUIDE';
		return $r;
	}
	function loadFromHTML($p=null) {
		$ret=parent::loadFromHTML($p);
		$this->userRussianName=sprintf("%s %s %s",$this->userRussianName1,$this->userRussianName2,$this->userRussianName3);
		$this->userLatinName=sprintf("%s %s %s",$this->userLatinName1,$this->userLatinName2);
		return $ret;
	}
	function updateDB() {
		$ret=parent::updateDB();
		$this->loadLinkedFromDB();
		if (is_array($this->Tours)) foreach ($this->Tours as $t) {
			$t->loadFromHTML();
			$t->updateDB();
		}
			
		return $ret;
	}




}


?>
