<?
require_once("../../config/init.php");

require_once("xajax_core/xajax.inc.php");
$xajax = new xajax();

$xajax->registerFunction("showInfo");
$xajax->registerFunction("closeInfo");
$xajax->registerFunction("editInfo");
$xajax->registerFunction("submitForm");
$xajax->processRequest();


$xajax->printJavascript();

function submitForm($data) {
	$_POST=$data;
	$obj=$_SESSION['_quickObject'];
	$form=$obj->_quickForm;
	$el=$obj->_quickElement;

	$form->updateSubmitValues();

	$form->setDefaults($_POST);

	if (!$form->validate()) {
		return editInfo($obj);
	}
	
	switch ($obj->_quickAction) {	
		case 'actionAdd' :
			$obj->loadFromHTML($_POST);
			$obj->insertIntoDB();
			//return sdump($obj,$obj);
			break;
		case 'actionUpdate' :
			$obj->loadFromHTML($_POST);
			$obj->updateDB();
			break;
	}

	return loadInfo($obj);

}


function editInfo($classname,$id=false,$el=false,$action=false) {
	if (is_object($classname)) {
		$obj=$classname;
		$el=$obj->_quickElement;
		$action=$obj->_quickAction;
	} else {
		$obj=new $classname($id);
		$obj->_quickElement=$el;
		$obj->_quickAction=$action;
		$_SESSION['_quickObject']=$obj;
	}

	//$obj=$_SESSION['_quickObject'];
	$el=$obj->_quickElement;
	$obj->getEditHtml();

	$objResponse = new xajaxResponse();
	$objResponse->assign($el.'_infoPanel',"innerHTML", $obj->_quickForm->toHtml());
	$objResponse->assign($el.'_infoPanel',"style.display", '');
	//$objResponse->append($el.'_infoPanel',"innerHTML", "<a href='' onCLick=\"return xajax_editInfo();\">edit</a>");


	$_SESSION['_quickObject']=$obj;

	return $objResponse;
}

function closeInfo($objResponse=null) {
	if (!is_object($_SESSION['_quickObject'])) return false;
	$obj=$_SESSION['_quickObject'];
	$el=$obj->_quickElement;

	if (!$objResponse) $objResponse = new xajaxResponse();
	$objResponse->assign($el.'_infoPanel',"innerHTML",'');
	$objResponse->assign($el.'_infoPanel',"style.display", 'none');

	return $objResponse;

}

function showInfo($classname,$id=false,$el=false) {
	return loadInfo($classname,$id,$el);
}

function loadInfo($classname,$id=false,$el=false) {
	$objResponse = new xajaxResponse();

	closeInfo($objResponse);

	if (is_object($classname)) {
		$obj=$classname;
		$el=$obj->_quickElement;
	} else {
		$obj=  new $classname($id);
		$obj->_quickElement=$el;
		$_SESSION['_quickObject']=$obj;
	}
	$objResponse->assign($el.'_infoPanel',"innerHTML", (string)$obj);
	$objResponse->assign($el.'_infoPanel',"style.display", '');
	$objResponse->append($el.'_infoPanel',"innerHTML", "<input class=input type='button' onCLick=\"return xajax_editInfo('$classname',$id,'$el','actionUpdate');\" value='edit'>");
	$v=$obj->getValues();
	foreach ($v as $k=>$r) {
		$objResponse->assign($el.'_'.$k,"innerHTML", (string)$r);
	}

	return $objResponse;
}


function sdump($obj,$var) {
	$objResponse = new xajaxResponse();
	$el=$obj->_quickElement;
	$objResponse->assign('guideAdd'.'_infoPanel',"innerHTML", '<pre>'.var_dump($var,1).'</pre>');
	return $objResponse;
}

					


?>
