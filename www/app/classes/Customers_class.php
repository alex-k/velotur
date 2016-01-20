<? 

class Customers extends BaseClass {
	function  __construct($id=false) {
		$this->_table="Customers";
		$this->_idfield="customerID";
		$this->_datefield="customerCreationDate";

		$this->_LINKED = array (
		       array (
			       'classname'=>'Invitations',
			       )
		       );


		return parent::__construct($id);
	}


	function checkLogin($login) {
		global $DBCLASS;
		if (!preg_match('/^[A-Za-z0-9_\-\@\.]+$/',$login) || preg_match('/\.\./',$login)) return false;
		$res=$DBCLASS->getRowByField($this->_table,'customerLogin',$login);
		if ($login && $login==$res->customerLogin) {
			return false;
		} 		
		return true;
	}
	function insertIntoDB() {
		if ($this->checkLogin($this->customerLogin)) {
			$ret=parent::insertIntoDB();
		} else {
			throw new MyException("This login $this->customerLogin already used, probably double submit?");
		}
		return $ret;
	}
	function auth($login,$password) {
		global $DBCLASS;
		if (empty($login)) return false;
		$res=$DBCLASS->getRowByField($this->_table,'customerLogin',$login);
		if ($res->customerPassword==$password) {
			return $this->loadFromDB($res->{$this->_idfield});
		}
		return false;
	}
	function updateDB() {
		$this->customerHistory=serialize($this->History);
		return parent::updateDB();
	}
	function getValues() {
		$r=parent::getValues();
		if ($this->History) $r[History]=$this->History;
		return $r;
	}

	function loadFromDB($id) {
		$ret=parent::loadFromDB($id);
		$this->History=@unserialize($this->customerHistory);
		return $ret;
	}


}


?>
