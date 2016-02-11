<? 

class Trips extends BaseClass {
	function  __construct($id=false) {
		$this->_table="Trip";
		$this->_idfield="tripID";
		$this->_LINKED = array (
		       array (
			       'classname'=>'Tours',
			       )
		       );
		return parent::__construct($id);
	}

	public function __toString() {
		return $this->getInfo();
	}
	function loadFromHTML($data=false) {
		global $_POST;
		if (isset($_POST[formSubmit])) {
			$this->tripShowAvPlaces=0;
			$this->tripHidden=0;
			$this->langRU=0;
			$this->langEN=0;
		}
		return parent::loadFromHTML($data);
	}



}


?>
