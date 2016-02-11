<?

class DbMysqlLayer
{

    private $_serverID, $_methodsRuntime;

    function DbMysqlLayer()
    {
        try {
            global $_CONF;
            $this->Connect($_CONF[db_host], $_CONF[db_username], $_CONF[db_password], $_CONF[db_port], $_CONF[db_database]);
        } catch (MysqlException $e) {
            $e->getReport();
            exit();
        }

    }

    function Connect($db_host = 'localhost', $db_username, $db_password, $db_port = 3306, $db_database)
    {
        $this->link = @mysql_connect("$db_host:$db_port", $db_username, $db_password, true);

        if (!$this->link) {
            throw new MysqlException("Not connected to  hostname '$db_host:$db_port' : " . mysql_error());
        }

        if (!$this->db_selected = @mysql_select_db($db_database, $this->link)) {
            throw new MysqlException("Can't use database : " . mysql_error($this->link));
        }

        global $_CONF;
        if ($_CONF['db_charset']) {
            $this->query("SET CHARACTER SET " . $_CONF['db_charset']);
            $this->query("SET NAMES " . $_CONF['db_charset']);
        }

        $que = "show variables like 'server_id'";
        $res = mysql_fetch_assoc($this->query($que));
        $this->_serverID = $res[Value];

    }

    function Disconnect()
    {
        return @mysql_close();
    }

    function fetch($que)
    {
        $ret = array();
        $r = $this->query($que);
        if ($r) while ($a = mysql_fetch_assoc($r)) {
            $ret[] = $a;
        }
        return $ret;
    }

    function query($que)
    {

        $t1 = microtime(true);

        global $_SQLDEBUG, $_BACKTRACE;
        if ($_SQLDEBUG) {
            mydump($que);
        }
        if ($_BACKTRACE) {
            mydump(backtrace());
            //debug_print_backtrace();
        }
        if (!$ret = @mysql_query($que, $this->link)) {
            mylog($que, 'dberror');
            mylog(mysql_errno($this->link) . " : " . mysql_error($this->link), 'dberror');
            throw new MysqlException("Error in query $que:" . mysql_error($this->link));
        }
        $t2 = microtime(true);
        $time = ($t2 - $t1);
        $this->totalQueTime += $time;
        if ($_SQLDEBUG) mydump(sprintf("time: %.05f sec", $time));
        if ($_SQLDEBUG) mydump(sprintf("totaltime: %.05f sec", $this->totalQueTime));
        return $ret;
    }

    function queryResult($que, $group = false)
    {
        $ret = array();
        if ($res = $this->query($que)) while ($o = mysql_fetch_assoc($res)) {
            if ($group) {
                foreach ($o as $k => $v) {
                    $ret[$k][] = $v;
                }
            } else {
                $ret[] = $o;
            }
        }
        return $ret;
    }

    function  getRequestInfo()
    {
        $ret = mysql_info($this->link);
        preg_match_all('/([A-Za-z ]+): ([0-9]+) */', $ret, $res);
        foreach ($res[1] as $key => $name) {
            $info[$name] = $res[2][$key];
        }
        return $info;
    }

    function  getAffectedRows()
    {
        return mysql_affected_rows($this->link);
    }

    function deleteRow($table, $idfield, $id, $options = false)
    {
        global $merchant;
        global $_CONF;
        if (is_object($merchant) && $merchant->getID() == $_CONF[demo_merchant_id]) {
            return false;
        }
        $que = sprintf("delete from %s where %s=%s", mysql_real_escape_string($table), mysql_real_escape_string($idfield), mysql_real_escape_string($id));
        if (is_array($options)) $que .= sprintf(" and %s", $this->_formWhereString($table, $options));
        mylog($que, 'dbupdate');
        if ($res = $this->query($que)) {
            return $id;
        } else {
            throw new MysqlException("Can't delete data from table $table: " . mysql_error($this->link));
        }
    }

    function genID()
    {
        $tt = microtime();
        $arr = explode(' ', $tt);
        $id = substr($arr[1] . sprintf('%02d', ($arr[0] * 100)), -10);
        return $id;
    }

    function insertRow($table, $idfield, $values, $dbtypes = false)
    {
        global $merchant;
        global $_CONF;
        if (!is_array($dbtypes)) {
            $dbtypes = $this->getTableInfo($table);
        }
        if (is_array($values)) foreach ($values as $key => $val) {
            if (!empty($dbtypes[$key]) && $key != 'server_id' && $key != $idfield && !is_null($val) && $val != '') {
                switch ($dbtypes[$key]->Type) {
                    case 'int':
                    case 'tinyint':
                    case 'float':
                        $val = mysql_real_escape_string($val + 0);
                        break;
                    case 'datetime':
                    case 'date':
                        if (empty($val) || $val == '0000-00-00 00:00:00') {
                            $val = 'now()';
                        } else {
                            $val = sprintf("'%s'", mysql_real_escape_string($val));
                        }
                        break;
                    case 'set':
                        unset($setval);
                        if (is_array($val)) {
                            foreach ($val as $setkey => $setvalue) {
                                if (!empty($setvalue))
                                    $setval[] = $setvalue;
                            }
                            $val = sprintf("'%s'", is_array($setval) ? mysql_real_escape_string(implode(',', $setval)) : '');
                        } else {
                            $val = false;
                        }
                        break;
                    default:
                        $val = sprintf("'%s'", mysql_real_escape_string($val));
                        break;
                }
                if (!empty($val)) {
                    $qfields .= sprintf("%s,", mysql_real_escape_string($key));
                    $qvalues .= sprintf("%s,", $val);
                }
            }
        }
        global $_CONF;
        if ($_CONF[db_use_generated_id] == "auto_increment") {
        } else if ($_CONF[db_use_generated_id] == "TRUE") {
            $id = $this->genID();
            $qfields .= sprintf("%s,", mysql_real_escape_string($idfield));
            $qvalues .= sprintf("%s,", $id);
        } else {
            $que = sprintf("select %s as max_id  from %s order by %s desc limit 1", mysql_real_escape_string($idfield), mysql_real_escape_string($table), mysql_real_escape_string($idfield));
            $res = mysql_fetch_assoc($this->query($que));
            $id = $res[max_id] + 1;
            $qfields .= sprintf("%s,", mysql_real_escape_string($idfield));
            $qvalues .= sprintf("%s,", $id);
        }

        if ($_CONF[db_add_serverID]) {
            $qfields .= sprintf("%s,", mysql_real_escape_string('server_id'));
            $qvalues .= sprintf("%s,", $this->_serverID);
        }


        $qfields = substr($qfields, 0, -1);
        $qvalues = substr($qvalues, 0, -1);
        $que = sprintf("insert into %s (%s) values (%s)", mysql_real_escape_string($table), $qfields, $qvalues);
        mylog($que, 'dbupdate');
        try {
            if ($res = $this->query($que)) {
                return mysql_insert_id($this->link) ? mysql_insert_id($this->link) : $id;
            } else {
                throw new MysqlException("Can't insert data into table $table: " . mysql_error($this->link));
            }
        } catch (MysqlException $e) {
            if ($_CONF[db_use_generated_id] == "TRUE" && mysql_errno($this->link) == 1062) {
                mydump($e->getReport());
                usleep(rand(200, 400));
                $this->insertRow($table, $idfield, $values, $dbtypes);
            } else {
                throw $e;
            }
        }

    }

    function updateRow($table, $id, $idfield, $values, $dbtypes = false)
    {
        global $merchant;
        global $_CONF;
        if ($merchant && $merchant->getID() == $_CONF[demo_merchant_id]) {
            return false;
        }
        if (!is_array($dbtypes)) {
            $dbtypes = $this->getTableInfo($table);
        }
        if (is_array($values)) foreach ($values as $key => $val) {
            if (!empty($dbtypes[$key]) && $key != 'server_id' && $key != $idfield && !is_null($val) && (is_string($val) || is_numeric($val) || $dbtypes[$key]->Type == 'set')) {
                switch ($dbtypes[$key]->Type) {
                    case 'int':
                    case 'tinyint':
                    case 'float':
                        $val = mysql_real_escape_string($val + 0);
                        break;
                    case 'datetime':
                        if ($val == '0000-00-00 00:00:00') {
                            $val = 'now()';
                        } else {
                            $val = sprintf("'%s'", mysql_real_escape_string($val));
                        }
                        break;
                    case 'set':
                        unset($setval);
                        if (is_array($val)) {
                            foreach ($val as $setkey => $setvalue) {
                                if (!empty($setvalue) && array_key_exists($setvalue, $dbtypes[$key]->SetValues))
                                    $setval[] = $setvalue;
                            }
                        }
                        if (sizeof($setval) > 0) {
                            $val = sprintf("'%s'", mysql_real_escape_string(implode(',', $setval)));
                        } else {
                            $val = "''";
                        }
                        break;
                    default:
                        $val = sprintf("'%s'", mysql_real_escape_string($val));
                        break;
                }
                $qvalues .= sprintf(" %s=%s,", mysql_real_escape_string($key), $val);
            }
        }
        $qvalues = substr($qvalues, 0, -1);
        $que = sprintf("update %s set %s where %s=%s", mysql_real_escape_string($table), $qvalues, mysql_real_escape_string($idfield), mysql_real_escape_string($id));
        mylog($que, 'dbupdate');
        if ($res = $this->query($que)) {
            return $res;
        } else {
            throw new MysqlException("Can't update data for table $table and $idfield $id: " . mysql_error($this->link));
        }
    }

    function _formWhereString($table, $fields = array(), $type = 'and', $skipwhere = false)
    {
        if (!is_array($fields)) return;
        $tablefields = $this->getTableInfo($table);
        foreach ($fields as $field => $mvalue) {
            if (is_array($mvalue) && !empty($mvalue[value])) {
                $value = $mvalue[value];
                $eq = $mvalue[eq];
                $field = $mvalue[field];
            } else if (is_array($mvalue) && !empty($tablefields[$field])) {
                $field = key($mvalue);
                $eq = '=';
                $value = current($mvalue);
            } else if (is_array($mvalue) && !isset($mvalue[value]) && $field != 'strings_OR') {
                $where .= sprintf(" (%s) $type", substr($this->_formWhereString($table, $mvalue), 7));
            } else {
                $value = $mvalue;
                $eq = '=';
            }
            if (!empty($tablefields[$field])) {
                switch ($eq) {
                    case 'SET':
                        $set_values = "";
                        foreach ($value as $setkey => $setvalue) {
                            $set_values .= sprintf("'%s',", mysql_real_escape_string($setvalue));
                        }
                        $set_values = substr($set_values, 0, -1);
                        //$where.=sprintf(" find_in_set(%s,'%s')>0 $type",mysql_real_escape_string($field),$set_values);
                        $where .= sprintf(" %s in (%s) $type", mysql_real_escape_string($field), $set_values);
                        break;

                    case 'ARRAY':
                        $set_values = "";
                        foreach ($value as $setkey => $setvalue) {
                            $set_values .= sprintf("'%s',", mysql_real_escape_string($setvalue));
                        }
                        $set_values = substr($set_values, 0, -1);
                        $where .= sprintf(" find_in_set(%s,%s)>0 $type", $set_values, mysql_real_escape_string($field));
                        break;

                    case 'STRONGLIKE':
                        $where .= sprintf(" %s like '%s' $type", mysql_real_escape_string($field), mysql_real_escape_string($value));
                        break;
                    case 'LIKE':
                        $where .= sprintf(" %s like '%%%%%s%%%%' $type", mysql_real_escape_string($field), mysql_real_escape_string($value));
                        break;

                    case 'LIMIT':
                        $limit_string = sprintf(" limit %d ", mysql_real_escape_string($value));
                        break;

                    case 'GROUPBY':
                        $group_string = sprintf(" group by %s ", mysql_real_escape_string($value));
                        break;

                    case 'ORDERBY':
                        $order_string = sprintf(" order by %s ", mysql_real_escape_string($value));
                        break;


                    default:
                        switch ($tablefields[$field]->Type) {
                            case 'float':
                                $where .= sprintf(" format(%s,2) %s format(%s,2) $type", mysql_real_escape_string($field), $eq, mysql_real_escape_string($value));
                                break;
                            case 'set':
                                $where .= sprintf(" %s like '%%%s%%' $type", mysql_real_escape_string($field), mysql_real_escape_string($value));
                                break;
                            default:
                                if (is_array($mvalue) && $mvalue['type'] == 'field') {
                                    $where .= sprintf(" %s %s %s $type", mysql_real_escape_string($field), $eq, mysql_real_escape_string($value));
                                } else {
                                    $where .= sprintf(" %s %s '%s' $type", mysql_real_escape_string($field), $eq, mysql_real_escape_string($value));
                                }
                        }
                        break;
                }
            }
        }
        if (is_array($fields[strings_OR])) {
            $where .= sprintf(" (%s) $type", substr($this->_formWhereString($table, $fields[strings_OR], 'or'), 7));
        }
        $where = substr($where, 0, -3);
        $addwhere = $skipwhere ? ' ' : "where";
        if (!empty($where)) $where = "$addwhere $where";
        else $where = "$addwhere 1=1";
        $where .= "$group_string $order_string $limit_string";
        return $where;
    }

    function getRowArrayByArray($table, $fields = array(), $selfields = array())
    {
        if (sizeof($selfields) > 0) {
            $fldlist = implode(',', $selfields);
        } else {
            $fldlist = "*";
        }
        $where = $this->_formWhereString($table, $fields);
        $ret_post = array();
        if (!empty($fields['paging_name'])) {
            global $_POST;
            $que = sprintf("select count(*) as cnt from %s %s", mysql_real_escape_string($table), $where);
            $res = $this->query($que);
            $rt = mysql_fetch_assoc($res);
            $total_items = $rt['cnt'];

            $sourcename = $fields['paging_name'];
            $itemsperpage = $fields['itemsperpage'];
            $firstitemname = "firstitem_$sourcename";
            $first_item = is_numeric($_POST[$firstitemname]) && $_POST[$firstitemname] <= $total_items ? $_POST[$firstitemname] : 1;
            $where .= sprintf(" LIMIT %d, %d", $first_item - 1, $itemsperpage);
            $ret = array_fill(0, $first_item - 1, null);
            if ($first_item + $itemsperpage < $total_items - 1) $ret_post = array_fill($first_item + $itemsperpage, $total_items - 1, null);

        }
        $que = sprintf("select %s from %s %s", $fldlist, mysql_real_escape_string($table), $where);
        if ($res = $this->query($que)) {
            //$ret=Array();
            if (mysql_num_rows($res)) while ($rt = mysql_fetch_assoc($res)) {
                $ret[] = $rt;
            }
            //return $ret;
            return @array_merge($ret, $ret_post);
        } else {
            throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
        }
    }

    function mod_getRowArrayByArray($table, $fields = array())
    {
        $que = sprintf("select * from %s %s", mysql_real_escape_string($table), $this->_formWhereString($table, $fields));
        if ($res = $this->query($que)) {
            return;
            $ret = Array();
            if (mysql_num_rows($res)) while ($rt = mysql_fetch_assoc($res)) {

                $ret[] = $rt;
            }
            return $ret;
        } else {
            throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
        }
    }

    function getRowArrayByField($table, $field, $value, $eq = '=', $options = false, $cache = false)
    {
        $tinfo = $this->getTableInfo($table);
        /*
        unset($orderfield);
        foreach ($tinfo as $tkey=>$info) {
            if ($info->Type=='datetime' or $info->Type=='date') {
                $orderfield=$info->Field;
            }
        }
        */

        if ($cache && $eq == '=') {
            $que = sprintf("select * from %s where 1 ", mysql_real_escape_string($table));
            if (is_array($options)) {
                $que .= sprintf(" and %s", $this->_formWhereString(mysql_real_escape_string($table), $options, 'and', 'skip'));
            }
            if (!empty($orderfield)) $que .= sprintf(" order by %s", mysql_real_escape_string($orderfield));
            $_qid = md5($que);
            if (empty($this->_querycahe[$_qid])) {

                if ($res = $this->query($que)) {
                    $ret = Array();
                    while ($rt = mysql_fetch_object($res)) {
                        $this->_querycahe[$_qid][$rt->$field][] = $rt;
                    }
                } else {
                    throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
                }
            }
            return ($this->_querycahe[$_qid][$value]);

        } else {
            $que = sprintf("select * from %s where %s %s '%s'", mysql_real_escape_string($table), mysql_real_escape_string($field), mysql_real_escape_string($eq), mysql_real_escape_string($value));
            if (is_array($options)) {
                $que .= sprintf(" and %s", $this->_formWhereString(mysql_real_escape_string($table), $options, 'and', 'skip'));
            }
            if (!empty($orderfield)) $que .= sprintf(" order by %s", mysql_real_escape_string($orderfield));
            if ($res = $this->query($que)) {
                $ret = Array();
                while ($rt = mysql_fetch_object($res)) {
                    $ret[] = $rt;
                }
                return $ret;
            } else {
                throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
            }
        }
    }

    function getTableValue($table, $values, $criterias)
    {
        if (is_string($values)) {
            $val[] = $values;
            $values = $val;
        }
        foreach ($values as $key => $value) {
            $vs .= "$value,";
        }
        $vs = substr($vs, 0, -1);
        if (is_array($criterias)) foreach ($criterias as $key => $criteria) {
            $cr .= sprintf("%s %s '%s'", mysql_real_escape_string($criteria[field]), mysql_real_escape_string($criteria[eq]), mysql_real_escape_string($criteria[value])) . " and ";
        }
        $cr = substr($cr, 0, -4);
        $que = sprintf("select %s from %s where %s", $vs, mysql_real_escape_string($table), $cr);


        if ($res = $this->query($que)) {
            return mysql_fetch_object($res);
        } else {
            throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
        }

    }

    function getRowByField($table, $field, $value, $eq = '=')
    {
        $que = sprintf("select * from %s where %s %s '%s' LIMIT 1", mysql_real_escape_string($table), mysql_real_escape_string($field), mysql_real_escape_string($eq), mysql_real_escape_string($value));
        if ($res = $this->query($que)) {
            return mysql_fetch_object($res);
        } else {
            throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
        }
    }

    function getRow($table, $id, $idfield)
    {
        $que = sprintf("select * from %s where %s='%s' LIMIT 1", mysql_real_escape_string($table), mysql_real_escape_string($idfield), mysql_real_escape_string($id));
        if ($res = $this->query($que)) {
            $ret = mysql_fetch_object($res);
            mysql_free_result($res);
            return $ret;
        } else {
            throw new MysqlException("Can't get data for table $table and $idfield $id: " . mysql_error($this->link));
        }
    }

    function getTableInfo($table)
    {
        if (empty($this->_tableinfo[$table])) {
            $que = sprintf("SHOW COLUMNS from %s", mysql_real_escape_string($table));
            if ($res = $this->query($que)) {
                if ($res) while ($row = mysql_fetch_object($res)) {
                    preg_match("/[a-z]+/", $row->Type, $dbtype);
                    if ($dbtype[0] == 'set') {
                        $type = $dbtype[0];
                        preg_match_all("/'[A-Za-z0-9\-\_]+'/", $row->Type, $dbset);
                        $row->SetValues = array();
                        if (is_array($dbset[0])) foreach ($dbset[0] as $key => $value) {
                            $row->SetValues[trim($value, "'")] = false;
                        }
                    }
                    $row->Type = $dbtype[0];
                    $ret[$row->Field] = $row;
                }
                $this->_tableinfo[$table] = $ret;
            } else {
                throw new MysqlException("Can't get columns info for table $table: " . mysql_error($this->link));
            }
        }

        $ret = $this->_tableinfo[$table];

        return $ret;
    }

    function getLastError()
    {
        return array('error' => mysql_error($this->link), 'errno' => mysql_errno($this->link));
    }

    function quickStats()
    {
        $ques[] = sprintf("select count(userID) as users from Users");
        $ques[] = sprintf("select count(albumID) as albums from Albums");
        $ques[] = sprintf("select count(albumID) as deletedAlbums from Albums where albumChecked='deleted'");
        $ques[] = sprintf("select count(distinct albumPerformer) as performers from Albums");
        foreach ($ques as $que) {
            if ($r = mysql_fetch_assoc($this->query($que))) {
                $k = array_keys($r);
                $v = array_values($r);
                $ret[$k[0]] = $v[0];
            }

        }
        return ($ret);
    }


    function getPerformers($options)
    {
        $wh = $this->_formWhereString('Albums', $options, 'and', true);
        $que = sprintf("select albumPerformer, count(*) as albumCount from Albums where %s", $wh);
        if ($res = $this->query($que)) while ($r = mysql_fetch_assoc($res)) {
            $ret[] = $r;
        }
        return $ret;

    }

    function expireUsers()
    {
        $que = sprintf("delete from User where userLatinName='' and userRussianName='' and userRegistrationDate<curdate()-interval 3 day");
        return $this->query($que);
    }

    function Dot2LongIP($IPaddr)
    {
        if ($IPaddr == "") {
            return 0;
        } else {
            $ips = split("\.", "$IPaddr");
            return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
        }
    }

    function ipInfo($ipaddress, $type = 'all')
    {
        $ipno = $this->Dot2LongIP($ipaddress);
        $query2 = "SELECT * FROM ipstate WHERE (ipFROM <= " . $ipno . ") AND (ipTO >= " . $ipno . ")";
        //$result2 = mysql_query($query2);
        $result2 = $this->query($query2);
        if ($result2) {
            $fv = mysql_fetch_object($result2);
            mysql_free_result($result2);
        } else {
            mylog($query2, 'dberror');
        }

        $ret[ipdbCountryCode] = $fv->countrySHORT != '-' ? $fv->countrySHORT : '';
        $ret[ipdbCountry] = $fv->countryLONG != '-' ? $fv->countryLONG : '';
        $ret[ipdbState] = $fv->state != '-' ? $fv->state : '';
        $ret[ipdbCity] = $fv->city != '-' ? $fv->city : '';


        return $ret;

    }

    function mailUsers($_POSTDATA)
    {
        $ret = array();

        if ($_POSTDATA['userSubscribeNews']) {
            $que = sprintf("select distinct User.* from User where userSubscribeNews=1");
            $ret = array_merge($ret, $this->queryResult($que));
        }


        if (!is_array($_POSTDATA['mailTour'])) {
            $que_tour = "and FALSE";
        } else if (!in_array('none', $_POSTDATA['mailTour'])) {
            $que_tour = " and tourID in (" . implode(',', $_POSTDATA['mailTour']) . ")";
        }
        function t(&$a)
        {
            $a = sprintf("'%s'", $a);
        }

        if (!is_array($_POSTDATA['mailStatus'])) {
            $que_satus = "and FALSE";
        } else if (!in_array('all', $_POSTDATA['mailStatus'])) {
            if (in_array('failed', $_POSTDATA['mailStatus'])) {
                $_POSTDATA['mailStatus'][] = 'deleted';
                $_POSTDATA['mailStatus'][] = 'WL';
            }
            array_walk($_POSTDATA['mailStatus'], 't');
            $que_satus = " and tourUserType in (" . implode(',', $_POSTDATA['mailStatus']) . ")";
        }

        $que = sprintf("select distinct User.* from User left join TourUsers on (User.userID=TourUsers.userID) where 1=1 %s   %s", $que_tour, $que_satus);
        $res = $this->query($que);
        if (mysql_num_rows($res)) while ($rt = mysql_fetch_assoc($res)) {
            $ret[] = $rt;
        }

        return ($ret);


    }


}


?>
