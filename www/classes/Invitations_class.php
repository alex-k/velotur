<? 

class Invitations extends BaseClass {
	function  __construct($id=false) {
		$this->_table="Invitations";
		$this->_idfield="invitationID";
		$this->_datefield="invitationDate";

		return parent::__construct($id);
	}


	function gererateCode() {
		$rand = mt_rand(0, 32);
		$code = md5($rand . time());
		$this->invitationCode=$code;
		return $code;
	}

	function loadFromDB($id) {
		$ret=parent::loadFromDB($id);
		global $_CONF;
		$this->invitationExpired=strtotime($this->invitationDate) < strtotime('now');
		$this->Active=$this->invitationActive && !$this->invitationExpired;
		return $ret;
	}

}


?>
