<? 

class RentabikeSet extends BaseClass {
	function  __construct($id=false) {
		$this->_table="bikeset";
		$this->_idfield="id";

		return parent::__construct($id);
	}

}

