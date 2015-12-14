<? 
function sort_tours($a,$b) {
	$sort_order=array('apply','WL', 'completed', 'deleted');
	$ka=array_search($a['tourUserType'],$sort_order);
	$kb=array_search($b['tourUserType'],$sort_order);
	return strcmp($ka,$kb);
}

class Tours extends BaseClass {
	function  __construct($id=false) {
		$this->_table="Tour";
		$this->_idfield="tourID";

		$this->_LINKED = array (
				array (
					'classname'=>'Guides',
					'type'=>'one2one',
					'classLinkField'=>'guideID',
					'idField'=>'guideID',
					),
				array (
					'classname'=>'Users',
					'type'=>'many2many',
					'linkTable'=>'TourUsers',
					'classLinkField'=>$this->_idfield,
					'addFields'=>'tourUserType,tourUserDate,tourUserComments,tourUserCommentsUser,tourUserCommentsRegVia,tourUserCommentsPrepay,tourUserCommentsTicket,tourUserAddName1,tourUserAddValue1,tourUserAddName2,tourUserAddValue2,tourUserAddName3,tourUserAddValue3,tourUserAddName4,tourUserAddValue4,tourUserAddName5,tourUserAddValue5,tourUserAddEmail1,tourUserAddEmail2,tourUserRooming,tourUserRoomingComment,tourUserRoomingType,tourUserRoomingNo,tourUserModifyDate',
					'idField'=>'userID',
					'options'=>array(array('field'=>'tourUserRoomingNo','eq'=>'ORDERBY','value'=>'tourUserRoomingNo')),
					),
				);

		return parent::__construct($id);
	}

	public function __toString() {
		return $this->getInfo();
	}
	function getAllFutureYears() {
		global $DBCLASS;
		$r=array();
		$ret=$DBCLASS->query('select distinct year(tourStartDate) as year from Tour where year(tourStartDate)>=year(curdate())');
		while ($y=mysql_fetch_assoc($ret)) {
			$r[$y['year']]=$y['year'];
		}
		return $r;
	}
	function getAllYears() {
		global $DBCLASS;
		$r=array();
		$ret=$DBCLASS->query('select distinct year(tourStartDate) as year from Tour');
		while ($y=mysql_fetch_assoc($ret)) {
			$r[$y['year']]=$y['year'];
		}
		return $r;
	}
	function loadFromHTML($data=false) {
		global $_POST;
		if (isset($_POST[tourTitle])) {
			$this->tourShowAvPlaces=0;
			$this->tourBlockTransfers=0;
			$this->langRU=0;
			$this->langEN=0;
		}
		$ret=parent::loadFromHTML($data);
		$g=new Guides($this->guideID);
		$this->guideName=$g->guideName;
		$g=new Guides($this->guideID2);
		$this->guideName2=$g->guideName;
		$g=new Guides($this->guideKuratorID);
		$this->guideKuratorName=$g->guideName;
		$this->guideKuratorEmail=$g->guideEmail1;
		return $ret;
	}
	function deleteFromDB() {
		unset($this->Users);
		$this->storeLinkedInDB();
		return parent::deleteFromDB();
	}

	function insertIntoDB() {
		$ret=parent::insertIntoDB();
		$this->updateDB();
		return($ret);
	}


	function updateDB() {
		$this->loadLinkedFromDB();
		$usersCnt=0;
		$usersWLCnt=0;
		if (is_array($this->Users)) {
			foreach ($this->Users as $u) {
				if ($u->tourUserType=='apply') $usersCnt++;
				if ($u->tourUserType=='WL') $usersWLCnt++;
			}
		}
		$this->tourAvPlaces=$this->tourPlaces - $usersCnt;
		$this->tourUsersApply=$usersCnt;
		$this->tourUsersWL=$usersWLCnt;
		$ret=parent::updateDB();
		return $ret;
	}

	function loadFromDB($arg) {
		$ret=parent::loadFromDB($arg);
		$st=getTourStatus();
		$this->tourStatusText=$st[$this->tourStatus];
		$this->tourAllowApply=$this->_allowApply();
		$this->tourAllowWL=$this->_allowWaitinglist();
		$this->tourAllowPreApply=$this->_allowPreApply();
		return $ret;
	}

	function _allowPreApply() {
		return $this->_allowWaitinglist() && strtotime($this->tourAppStartDate)>strtotime("now");
	}

	function _allowWaitinglist() {
		return $this->tourStatus=='normal' || 
			$this->tourStatus=='waitinglist'
			;
	}

	function _allowApply() {
		$rs=new tw_tours;
		return $rs->_allowApply($this);

		/*
		$dt=date("Y-m-d");

		if ($this->tourStatus!='normal') return false;
		if (!($this->tourAvPlaces>0)) return false;
		if (strtotime($this->tourAppStartDate)!=0 && strtotime($dt)<strtotime($this->tourAppStartDate)) return false;
		//if (strtotime($this->tourAppEndDate)!=0 && strtotime($dt)>strtotime($this->tourAppEndDate)) return false;

		return true;

		*/
	



		/*
		return $this->tourStatus=='normal' && 
			($this->tourAvPlaces>0 ) && 
			(strtotime($this->tourAppStartDate)==0 || strtotime($dt)>=strtotime($this->tourAppStartDate)) &&
			(strtotime($this->tourAppEndDate)==0 || strtotime($dt)<=strtotime($this->tourAppEndDate)) 
			;
		*/
	}

	function getAddValuesArray() {
		$addvaluesArray=array();
		for($i=1;$i<=5;$i++) {
			$n="tourAddValue$i";
			if (!empty($this->$n)) {
				$v=$this->$n;
				$vv=array_map(trim,preg_split('/[,;]/',$v));
				if (sizeof($vv)>1) {
					$addvaluesArray[$n]=$vv;
				}
			}
		}
		return $addvaluesArray;
	}



}


?>
