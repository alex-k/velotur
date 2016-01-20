<? 

class Users extends BaseClass {
	function  __construct($id=false) {
		$this->_table="User";
		$this->_idfield="userID";
		$this->_datefield="userRegistrationDate";
		$this->_LINKED = array (
				array (
					'classname'=>'Tours',
					'type'=>'many2many',
					'linkTable'=>'TourUsers',
					'classLinkField'=>$this->_idfield,
					'addFields'=>'tourUserType,tourUserDate,tourUserComments,tourUserCommentsUser,tourUserCommentsRegVia,tourUserCommentsPrepay,tourUserCommentsTicket,tourUserAddName1,tourUserAddValue1,tourUserAddName2,tourUserAddValue2,tourUserAddName3,tourUserAddValue3,tourUserAddName4,tourUserAddValue4,tourUserAddName5,tourUserAddValue5,tourUserAddEmail1,tourUserAddEmail2,tourUserRooming,tourUserRoomingComment,tourUserRoomingType,tourUserRoomingNo,tourUserModifyDate',
					'idField'=>'tourID',
					),
				array (
					'classname'=>'Users',
					'type'=>'one2one',
					'classLinkField'=>$this->_idfield,
					'idField'=>'userReferalID',
					),
				);

		return parent::__construct($id);
	}

	function isBlocked() {
		$this->loadFromHTML();
		$options['userType']='block';
		$options['strings_OR']['userEmail']=$this->userEmail;
		$options['strings_OR'][2]['userLatinName']=array('field'=>'userLatinName','eq'=>'LIKE','value'=>trim($this->userLatinName));
		$options['strings_OR'][2]['userBirthDay']=$this->userBirthDay;
		$blocked_users=$this->searchFromDB($options);
		return sizeof($blocked_users)>0;

	}


	function checkLogin($login) {
		global $DBCLASS;
		if (!preg_match('/^[A-Za-z0-9_\-\@\.]+$/',$login) || preg_match('/\.\./',$login)) return false;
		$res=$DBCLASS->getRowByField($this->_table,'userEmail',$login);
		if ($login && $login==$res->userEmail) {
			return false;
		} 		
		return true;
	}
	function insertIntoDB() {
		if ($this->checkLogin($this->userEmail) || $this->userReferalID>0 || $_POST['tourUserCommentsRegVia']) {
			$this->{$this->_datefield}='0';
			$ret=parent::insertIntoDB();
		} else {
			throw new MyException("This login $this->userEmail already used, probably double submit?");
		}
		global $DBCLASS;
		$DBCLASS->expireUsers();

		return $ret;
	}
	function deleteFromDB() {
		$user=new Users($this->getID());
		$user->loadLinkedFromDB();
		unset($this->Tours);
		$this->storeLinkedInDB();
		if (is_array($user->Tours)) foreach ($user->Tours as $tour) {
			$tour->updateDB();
		}
		return parent::deleteFromDB();
	}

	function auth($login,$password) {
		global $DBCLASS;
		if (empty($login)) return false;
		$u=new Users();
		$options=array();
		$options['userEmail']=$login;
		$options['userReferalID']='0';
		$us=$u->searchObjectsFromDB($options);
		if (is_array($us)) $res=current($us);
		//$res=$DBCLASS->getRowByField($this->_table,'userEmail',$login);
		if ($res->userPassword==$password && !$res->userReferalID) {
			//return $this->loadFromDB($res->{$this->_idfield});
			return $this->__construct($res->{$this->_idfield});
		}
		return false;
	}
	function getValues() {
		$r=parent::getValues();
		return $r;
	}
	function loadFromHTML($p=null) {
		$ret=parent::loadFromHTML($p);
		$this->userRussianName=sprintf("%s %s %s",$this->userRussianName1,$this->userRussianName2,$this->userRussianName3);
		$this->userLatinName=sprintf("%s %s %s",$this->userLatinName1,$this->userLatinName2,$this->userLatinName3);
		$this->userName=$this->userRussianName ? $this->userRussianName : $this->userLatinName;
		return $ret;
	}

	function loadFromDB($id) {
		$ret=parent::loadFromDB($id);
		return $ret;
	}
	function loadLinkedFromDB($opt=false,$opt2=false) {
		$ret=parent::loadLinkedFromDB($opt,$opt2);
		if (is_array($this->Tours)) uasort($this->Tours,'_users_so');
		return $ret;
	}
}
	function _users_so ($a, $b) { return (strcmp ($a->tourStartDate,$b->tourStartDate));    }


?>
