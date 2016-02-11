<? 

class Partners extends BaseClass {
	function  __construct($id=false) {
		$this->_table="Partners";
		$this->_idfield="partnerID";
		$this->_LINKED = array (
		       );


		$this->_FIELDTYPES= array(
			'partnerID' => 'hidden',
			);
		$this->_CHECKFIELDS= array(
			'partnerID' => 'required',
			'partnerName' => 'required',
			);
		$this->_FIELDNAMES= array(
			'partnerID' => '#',
			'partnerName' => 'Имя/название',
			);




		return parent::__construct($id);
	}

	function auth($login,$password) {
		global $DBCLASS;
		if (empty($login)) return false;
		$res=$DBCLASS->getRowByField($this->_table,'partnerLogin',$login);
		if ($res->partnerPassword==$password) {
			return $this->__construct($res->{$this->_idfield});
		}
		return false;
	}
}


?>
