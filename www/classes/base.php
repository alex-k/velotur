<?
class BaseClass {
    public $_EQUALS ;
    private $_DBTYPES;
    private $_DBDEFAULTS;
    private $_DBSETVALUES;
    private $_message;
    static $_tablesinfo;
    protected $_loaded;
    function initUsingDbValues() {
        try {
            global $DBCLASS;
	    if (empty($this->_tablesinfo[$this->_table])) {
		    $this->_tablesinfo[$this->_table]=$DBCLASS->getTableInfo($this->_table);
	    }
	    $tinfo=$this->_tablesinfo[$this->_table];
            foreach ($tinfo as $key=>$iobject) {
                $this->_EQUALS[$iobject->Field]=$iobject->Field;
                $this->_DBTYPES[$iobject->Field]=$iobject->Type;
                if (is_array($iobject->SetValues)) $this->_DBSETVALUES[$iobject->Field]=$iobject->SetValues;
                $this->{$iobject->Field}=$iobject->Default ? $iobject->Default : false;
                switch ($iobject->Type) {
                case 'int':
                    case 'tinyint':
                    settype($this->{$iobject->Field},"integer");
                    break;
                case 'real':
                case 'float':
                    settype($this->{$iobject->Field},"float");
                    break;
		case 'set':
		   $this->{$iobject->Field}=$iobject->SetValues;
                default:
                    break;
                }
            }

        } catch (MysqlException $e) {
            $e->getReport();
        }

    }
    function messageString($type) {
	    $strings[deleteFromDB]="object '".$this->getClass()."' have been deleted drom DB, id #".$this->getID();
	    $strings[insertIntoDB]="object '".$this->getClass()."' inserted into DB, id #".$this->getID();
	    $strings[updateDB]="object '".$this->getClass()."' updated in DB, id #".$this->getID();
	    return $strings[$type];
    }
    function getClass() {
        return get_class($this);
    }
    function exists() {
        return $this->getID()>0 && $this->_loaded;
    }
    function getID() {
        return $this->{$this->_idfield};
    }
    function setID($id) {
        return $this->{$this->_idfield}=$id;
    }
    function getTitle() {
        return $this->{$this->_titlefield};
    }
    function setMessage($message,$code=false) {
        $this->_message=$message;
	$this->_message_code=$code;
    }
    /*
    function getHtmlOptions($name) {
        $ret=array();
        if (is_array($this->$name))  foreach ($this->$name as $key=>$obj) {
            if (is_object($obj)) {
                $ret[$obj->getID()]=$obj->getTitle();
            }
        }
        return $ret;
    }
    */
    function getMessageCode() {
        return $this->_message_code;
    }
    function getMessage() {
        return $this->_message;
    }
    function searchObjectsFromDB ($fields,$load_linked=false, $nosort=true, $cache=false) {
        $ret=$this->searchFromDB($fields,array($this->_idfield));
        $classname=$this->getClass();
        if (is_array($ret)) foreach ($ret as $key=>$obj) {
		if ($cache) {
		    $nobj=new $classname();
		    $nobj->loadFromHTML($obj);
		    $arr[$obj[$this->_idfield]]=$nobj;
		} else {
		    $arr[$obj[$this->_idfield]]=new $classname($obj[$this->_idfield]);
		}
            if ($load_linked) $arr[$obj[$this->_idfield]]->loadLinkedFromDB($load_linked);
        }
	if (is_array($arr) && !$nosort) ksort($arr);
        return $arr;
    }
    function mod_searchFromDB ($fields=array()) {
        $this->_loaded=false;
        global $DBCLASS;
        $data=$DBCLASS->mod_getRowArrayByArray($this->_table,$fields);
	$ret=array();
        if (is_array($data)) foreach ($data as $key=>$row) {
		$ret[$row[$this->_idfield]]=$row;
	}
        return $ret;
    }
    function searchFromDB ($fields=array(),$selfields=array()) {
        $this->_loaded=false;
        global $DBCLASS;
        $data=$DBCLASS->getRowArrayByArray($this->_table,$fields,$selfields);
	$ret=array();
	/*
        if (is_array($data)) foreach ($data as $key=>$row) {
		$ret[$row[$this->_idfield]]=$row;
	}*/
	$ret= is_array($data) ? $data : array();
        return $ret;
    }
    function loadFromDB ($id) {
        $this->_loaded=false;
	global $_CONF;
	if ($_CONF[db_cache_results]) {
	}
        try {
            global $DBCLASS;
            $data=$DBCLASS->getRow($this->_table,$id,$this->_idfield);
            if (is_object($data)) {
                if (is_array($this->_EQUALS)) {
                    foreach ($this->_EQUALS as $key=>$field) {
			if (is_array($this->_DBSETVALUES[$field])) {
				$this->$field=$this->_DBSETVALUES[$field];
				$setvalues=explode(',',$data->$field);
				if (is_array($setvalues)) foreach ($setvalues as $setkey=>$setvalue) {
					if(array_key_exists($setvalue,$this->_DBSETVALUES[$field])) $this->{$field}[stripslashes($setvalue)]=stripslashes($setvalue);
				}
			} else {
				if (!unserialize($data->$field)) {
					$this->$field=stripslashes($data->$field);
				} else {
					$this->$field=$data->$field;
				}
			}
                    }
                    if ($data->{$this->_idfield}==$id) {
                        $this->setID($id);
                        $this->_loaded=true;
                    }
                } else {
                    throw new MyException("Try to load data before initialization: ".get_class($this)." id=$id");
                }
            } else {

                //if ($id>0) throw new MyException("Cant load data for ".get_class($this)." $this->_idfield=$id");
            }
        } catch (MysqlException $e) {
            $e->getReport();
        } catch (MyException $e) {
            $e->getReport();
        }
        return $this->getID();
    }
    function deleteFromDB() {
        $this->_loaded=false;
        try {
            global $DBCLASS;
            if ($id=$DBCLASS->deleteRow($this->_table,$this->_idfield,$this->getID())){
                $this->setMessage($this->messageString('deleteFromDB'),'OK');
            }
        } catch (MyException $e) {
            $e->getReport();
        }
    }
    function insertIntoDB() {
        $this->_loaded=false;
            global $DBCLASS;
            $id=$DBCLASS->insertRow($this->_table,$this->_idfield,$this->getValues());
            if ($id>0) {
                $this->loadFromDB($id);
                $this->setMessage($this->messageString('insertIntoDB'),'OK');
            }
	return $id;
    }
    function updateDB() {
        $this->_loaded=false;
        try {
            global $DBCLASS;
            if ($DBCLASS->updateRow($this->_table,$this->getID(),$this->_idfield,$this->getValues())) {
                $this->loadFromDB($this->getID());
                $this->setMessage($this->messageString('updateDB'),'OK');
            }
        } catch (MysqlException $e) {
            $e->getReport();
        }
    }
    function getValues() {
        if (is_array($this->_EQUALS)) {
            $rr=get_object_vars($this);
            foreach ($rr as $key=>$value) {
                if (is_string($value) || is_numeric($value) || is_bool($value)) {
                    $ret[$key]=$value;
                } else if (is_array($this->_DBSETVALUES[$key])) {
                    $ret[$key]=$value;
		    $ret[$key.'_Names']=array_keys($value);
		    //$ret[$key.'_Values']=array_values($value);
		}
            }
            /*
            foreach ($this->_EQUALS as $key=>$field) {
            	$ret[$field]=$this->$field;
            }
            */
        } else {
            throw new MyException("Try to get data before initialization: ".get_class($this));
        }
        if (is_array($this->_LINKED)) foreach ($this->_LINKED as $mkey=>$link) {
	    
            if (is_array($this->{$link[classname]}) && sizeof($this->{$link[classname]}>0)) foreach ($this->{$link[classname]} as $key=>$linkobj) {
	    	$parents=class_parents($link['classname']);
                if ($parents['BaseClass']) $ret[$link[classname]][$key]=$linkobj->getValues();
            } else {
		$linkobj=$this->{$link[classname]};
		if(isset($linkobj)) {
			$parents=class_parents($link['classname']);
			if ($parents['BaseClass']) $ret[$link[classname]]=$linkobj->getValues();
		}
	    }
        }
        return $ret;
    }
    function loadFromHTML($values=false) {
        if (!is_array($values)) $values=array_merge($_GET,$_POST);
        if (is_array($this->_EQUALS)) {
            foreach ($this->_EQUALS as $key=>$field) {
                if (isset($values[$field])) {
			if (is_array($this->_DBSETVALUES[$field]) && is_array($values[$field])) {
				$this->$field=$this->_DBSETVALUES[$field];
				foreach ($values[$field] as $setkey=>$setvalue) {
					if (array_key_exists($setvalue,$this->$field)) {
						$this->{$field}[$setvalue]=$setvalue;
					}
				}
			} else {
				$this->$field=$values[$field];
			}
		}
            }
        } else {
            throw new MyException("Try to load data from HTML FORM before initialization: ".get_class($this));
        }
    }
    function storeLinkedInDB($linked_classes=false,$cache=false) {
        global $DBCLASS;
        if (is_array($this->_LINKED)) foreach ($this->_LINKED as $key=>$link) {
            //if (!is_array($linked_classes) || in_array($link[classname],$linked_classes)) {
                $classname=$link[classname];
		$obj=new $classname();
                switch ($link[type]) {
                case 'many2many':
		    $DBCLASS->deleteRow($link[linkTable],$link[classLinkField],$this->{$link[classLinkField]});
		    if (is_array($this->{$classname}))
			    foreach ($this->{$classname} as $key=>$obj) {
			    $values=array();
			    $values[$link[idField]]=$obj->$link[idField];
			    $values[$link[classLinkField]]=$this->$link[classLinkField];
				if (!empty($link[addFields])) {
					$addfields=explode(',',$link[addFields]) ;
					foreach($addfields as $af) {
						$values[$af]=$obj->$af;
					}
				}

			    $DBCLASS->insertRow($link[linkTable],false,$values);
		    }

                    break;
                }
            //}
        }
    }
    function loadLinkedFromDB($linked_classes=false,$cache=false) {
        global $DBCLASS;
        if (is_array($this->_LINKED)) foreach ($this->_LINKED as $key=>$link) {
            if (!is_array($linked_classes) || in_array($link[classname],$linked_classes)) {
                $classname=$link[classname];
                $obj=new $classname();
                switch ($link[type]) {
                case 'one2one':
                    unset($this->$classname);
		    if ($cache) {
			    $res=$DBCLASS->getRowArrayByField($obj->_table,$link[classLinkField],$this->{$link[idField]},'=',$link[options],$cache);

			    if (is_array($res)) {
				    $data=current($res);
				    $clobj=new $classname();
				    $clobj->loadFromHTML(get_object_vars($data));
				    $this->$classname=$clobj;
			    }

		    } else {
			    $this->$classname=new $classname($this->{$link[idField]});
		    }
                    break;
                case 'many2one':
                    unset($this->$classname);
                    $res=$DBCLASS->getRowArrayByField($obj->_table,$link[classLinkField],$this->{$link[idField]},'=',$link[options],$cache);
                    if (is_array($res)) foreach ($res as $key=>$data) {
			    if ($cache) {
				    $clobj=new $classname();
				    $clobj->loadFromHTML(get_object_vars($data));
				       $this->{$classname}[$data->{$obj->_linked_idfield}]=$clobj;
			    } else {
				$this->{$classname}[$data->{$obj->_linked_idfield}]=new $classname($data->{$obj->_idfield});
			    }
                    }
                    //if(is_array($this->{$classname})) ksort($this->{$classname});
                    break;
                case 'many2many':
                    unset($this->$classname);
                    $res=$DBCLASS->getRowArrayByField($link[linkTable],$link[classLinkField],$this->{$link[classLinkField]},'=',$link[options],$cache);
                    if (is_array($res)) foreach ($res as $key=>$data) {
			    if ($cache) {
				    $clobj=new $classname();
				    $clobj->loadFromHTML(get_object_vars($data));
				       $this->{$classname}[$data->{$obj->_linked_idfield}]=$clobj;
			    } else {
				$this->{$classname}[$data->{$obj->_linked_idfield}]=new $classname($data->{$obj->_idfield});
			    }
			if (!empty($link[addFields])) {
				$addfields=explode(',',$link[addFields]) ;
				foreach($addfields as $af) {
					$this->{$classname}[$data->{$obj->_linked_idfield}]->$af=$data->$af;
				}
			}
                    }
                    //if(is_array($this->{$classname})) ksort($this->{$classname});
                    break;
                }
            }
        }
    }



    function BaseClass($id=false) {
        $this->_loaded=false;
        $this->initUsingDbValues();
	if (!isset($this->_linked_idfield)) $this->_linked_idfield=$this->_idfield;
        if(is_numeric($id)) $this->loadFromDB($id);
        return $this->getID();
    }

    public function __toString() {
	    return (String)$this->getID();
    }
/*

    function getEditHtml($type='default') {
	    	if (is_object($this->_quickForm)) return false;
		require_once 'HTML/QuickForm.php';
////
		$form = new HTML_QuickForm('signupForm', 'post', 'javascript:void(null);');

		// Set defaults for the form elements
		$form->setDefaults($this->getValues());


		// Add some elements to the form
			$form->addElement('hidden', '_id');
			$form->addElement('hidden', '_classname');
			$form->setDefaults(array('_id'=>$this->getID(),'_classname'=>$this->getClass()));
		foreach ($this->_FIELDNAMES as $k => $f ) {
			$form->addElement($this->_FIELDTYPES[$k] ? $this->_FIELDTYPES[$k] :'text', $k, $f, array('size' => 30, 'maxlength' => 255,'class'=>'input'));
			if (array_key_exists($k,$this->_CHECKFIELDS)) $form->addRule($k, "укажите $f", $this->_CHECKFIELDS[$k]);
		}

//		$form->addElement('button', 'form_submit', 'Send',array('class'=>'input', 'onClick'=>'xajax_submitForm(xajax.getFormValues("signupForm")); return false;'));
		$form->addElement('submit', 'form_submit2', 'Ok',array('class'=>'input'));
		$form->addElement('button', 'form_submit', 'Cancel',array('class'=>'input','onClick'=>"return xajax_closeInfo();"));


		//return $form->toHtml().htmlspecialchars($form->toHtml());
		$this->_quickForm=$form;
		return false;



    }

	function getInfo() {
		$ret=get_object_vars($this);
		foreach ($ret as $k=>$r) {
			if (array_key_exists($k,$this->_FIELDNAMES)) $ret2.=sprintf("%s:\t%s\n",$this->_FIELDNAMES[$k],$r);
		}
		return nl2br($ret2);
	}
*/
	function getHTMLSelect($fieldname,$options=array()) {
		$name=$this->getClass();
		$obj=new $name();
		$arr=$obj->searchObjectsFromDB($options);
		foreach ($arr as $k=>$o) {
			$ret[$o->getID()]=$o->$fieldname;
		}
		return $ret;
	}

}


?>
