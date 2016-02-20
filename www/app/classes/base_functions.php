<?


function loadclass($classname) {
    global $_CONF;
    $filename=$_CONF['root_dir'].$_CONF['classes_dir'].$classname."_class.php";
    if (include_once($filename)) {
        return true;
    } else {
        throw new MyException("loadclass: can not load class $classname from $filename");
    }
}

function summarizeArray($ar=array()) {
    $ret=array();
    if (is_array($ar)) foreach ($ar as $key=>$o) {
        foreach ($o as $key1=>$o2) {
            if(is_numeric($o2)) $ret[$key1]+=$o2;
        }
    }
    $ret[_count]=sizeof($ar);
    return $ret;
}

function myStats($type,$value, $value2=false,$owner="", $ownerID="",$dateformat="day",$forceupdate=false) {
    loadclass('Statistics');
    $st=new Statistics();
    $st->addValue($type,$value, $value2,$owner, $ownerID,$dateformat,$forceupdate);
}

function backtrace() {
    $trace=@debug_backtrace();
    if (is_array($trace)) foreach ($trace as $key=>$tr) {
        if ($addf) {
            $args=false;
            $args=implode(",",array_values($tr[args]));

            $ret.="$tr[class].$tr[function]($args) \t\t $tr[file]:$tr[line]\n";
            //$addf=0;
        }
        if ($tr['function']=='query') $addf=1;
    }
    return $ret;
}

function mydump($mix, $screen=true, $file=true) {
    global $_CONF;
    global $_POST;
    $mydump_ip=explode(',',$_CONF['mydump_ip']);

    if (FALSE &&!in_array($_SERVER['REMOTE_ADDR'],$mydump_ip) && !in_array($_SERVER['HTTP_X_FORWARDED_FOR'],$mydump_ip) && !$_POST['dump']) return;
    ob_start();
    print_r($mix);

    $text=(ob_get_contents());
    ob_end_clean();

    //echo "<pre>",print_r($mix),"</pre><br>";

    global $_CONF;
    if ($file ) {
        mylog($_SERVER["REQUEST_URI"],'mydump');
        mylog($text,'mydump');
    }

    if ($screen) {
        $text=htmlentities($text);
        echo "<div align=left><pre>$text<br></pre></div>";
    }

}

function mylog($mix,$type='mydump') {
    ob_start();
    print_r($mix);
    $text=(ob_get_contents());
    ob_end_clean();

    global $_CONF;
    switch ($type) {
        case 'dberror':
            $logfilename=$_CONF[log_dberror];
            break;
        case 'dbupdate':
            $logfilename=$_CONF[log_dbupdate];
            break;
        case 'payment':
            $logfilename=$_CONF[log_payment];
            break;
        case 'auth':
            $logfilename=$_CONF[log_auth];
            break;
        case 'auth2':
            $logfilename=$_CONF[log_auth2];
            break;
        case 'postback':
            $logfilename=$_CONF[log_postback];
            break;
        case 'email':
            $logfilename=$_CONF[log_email];
            break;
        case 'api':
            $logfilename=$_CONF[log_api];
            break;
        case 'fraud':
            $logfilename=$_CONF[log_fraud];
            break;
        case 'error':
            $logfilename=$_CONF[log_error];
            break;
        case 'exception':
            $logfilename=$_CONF[log_exception];
            break;
        case 'mydump':
        default:
            $logfilename=$_CONF[log_mydump];
            break;
    }

    if ($logfilename && $p = fopen($_CONF[root_dir].$_CONF[log_dir].$logfilename,"a+") ) {
        fputs($p,date("Y-m-d H:i:s")."  ");
        fputs($p,"\n");
        //	if ($type=='auth' || $type=='api') ;
        $text=preg_replace("/(\d{4})\d{8}(\d{4})/",'\1-XXXX-XXXX-\2',$text);
        //if ($type=='auth' || $type=='api') $text=preg_replace("/(<merchantPassword>).*(</merchantPassword>)/i",'\1-XXXX-\2',$text);
        fputs($p,$text);
        fputs($p,"\n");
        fclose($p);
    }
}


function checkfields($fieldsforcheck,$checkurl) {
    global $HTTP_POST_VARS;
    while   (list($name,$value)=each($HTTP_POST_VARS)) {
        $htdata.="&$name=$value";
    }


    while   (list($fieldname,$fieldnote)=each($fieldsforcheck)) {
        global ${$fieldname};
        if (!${$fieldname} or ${$fieldname}=="" or  ${$fieldname}==" ") {
            $message=$fieldnote;
            header ("Location: $checkurl?1=1&checkfieldsmessage=$message&checkfield=$fieldname$htdata");
            echo "<b>$message</b><br>";
            exit();
        }
    }
    return false;
}


function checkmail($mail) {
    $reg='^[a-z0-9_\.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$';
    return @eregi($reg,$mail);

}



function getApplyStatus() {
    include "apply_status.php";
    return  $valuesarray;
}
function getTourStatus() {
    include "tour_status.php";
    return  $valuesarray;
}
function getUserHowFound() {
    include "user_howfound.php";
    return  $valuesarray;
}
function getUserSex() {
    include "user_sex.php";
    return  $valuesarray;
}
function getPlacesGender() {
    include "tour_gender.php";
    return  $valuesarray;
    return array (
            'all'=>'��� �����������',
            'male'=>'������ �������',
            'female'=>'������ �������',
            );
}
function getUserType() {
    include "user_type.php";
    return  $valuesarray;
}
function getTripComfort() {
    global $_CONF;
    include $_CONF['root_dir']."config/trip_comfort.php";
    return  $comfortarray;
}
function getTripDifficulty() {
    global $_CONF;
    include $_CONF['root_dir']."config/trip_difficulty.php";
    return  $difficultyarray;
}


function getCountries() {
    include "country.php";
    return  $countrybycode;
}

function shortcode2country($code) {
    include "country.php";
    return  $countrybycode[$code];
}

function country2iso3code($country) {
    include "country.php";
    return $countrycodesISO3[$country];
}
function country2shortcode($country) {
    include "country.php";
    return $countrycodes[$country];
}
function USstatecode2statename($code) {
    include "country.php";
    return $USStates[$code];
}
function countryPhonePrefix($code) {
    include "country.php";
    return $phonecodes[$code];
}
function getFlagIDX($country) {
    include "country.php";
    return  $CountryJPFlasgs[$country];
}


function delLinkCategoty($name) {
    global $_CONF;
    $categories=linksCategories();
    if ($fp=fopen($_CONF[root_dir].$_CONF[classes_dir]."links_categories.php","w")) {
        foreach ($categories as $cat) {
            if (!empty($cat) && $cat!=$name) {
                fputs($fp,"\n".$cat);
            }
        }
        fclose($fp);
    }

}
function addLinkCategoty($name) {
    global $_CONF;
    if ($fp=fopen($_CONF[root_dir].$_CONF[classes_dir]."links_categories.php","a+")) {
        fputs($fp,"\n".$name);
        fclose($fp);
    }
}

function linksCategories() {
    global $_CONF;
    $categories=array();
    if ($ret=@file($_CONF[root_dir].$_CONF[classes_dir]."links_categories.php")) {
        foreach($ret as $c) {
            $cc=rtrim($c);
            if(!empty($cc)) array_push($categories,rtrim($cc));
        }
        asort($categories);
    }
    return $categories;
}

function merchantBusinessCategories() {
    include "business_categories.php";
    return $categories;
}
function sassign() {
    global $smarty;
    $numargs = func_num_args();
    $data=($numargs==1) ? func_get_arg(0) : func_get_arg(1);

    array_walk_recursive($data,'totranslit_ref');
    if ($numargs>1) {
        $smarty->assign(func_get_arg(0),$data);
    } else {
        $smarty->assign($data);
    }
}
function totranslit_ref(&$item,$key) {
    $item=totranslit($item,false);
    /*
     */
    //setlocale(LC_CTYPE, 'ru_RU');
    //$ns = iconv("UTF-8","KOI8-R//IGNORE",$str);
    //$item = iconv("UTF-8","KOI8-R//TRANSLIT",$item);
    /*
       setlocale(LC_ALL, 'ru_RU.UTF8');
       $item = iconv("utf-8", "ascii//TRANSLIT",$item);
     */


}
function totranslit($str,$strip=true) {
    $transchars =array (
            chr(hexdec("E1"))=>"A",
            chr(hexdec("E2"))=>"B",
            chr(hexdec("F7"))=>"V",
            chr(hexdec("E7"))=>"G",
            chr(hexdec("E4"))=>"D",
            chr(hexdec("E5"))=>"E",
            chr(hexdec("B3"))=>"Jo",
            chr(hexdec("F6"))=>"Zh",
            chr(hexdec("FA"))=>"Z",
            chr(hexdec("E9"))=>"I",
            chr(hexdec("EA"))=>"Ji",
            chr(hexdec("EB"))=>"K",
            chr(hexdec("EC"))=>"L",
            chr(hexdec("ED"))=>"M",
            chr(hexdec("EE"))=>"N",
            chr(hexdec("EF"))=>"O",
            chr(hexdec("F0"))=>"P",
            chr(hexdec("F2"))=>"R",
            chr(hexdec("F3"))=>"S",
            chr(hexdec("F4"))=>"T",
            chr(hexdec("F5"))=>"U",
            chr(hexdec("E6"))=>"F",
            chr(hexdec("E8"))=>"Kh",
            chr(hexdec("E3"))=>"C",
            chr(hexdec("FE"))=>"Ch",
            chr(hexdec("FB"))=>"Sh",
            chr(hexdec("FD"))=>"W",
            chr(hexdec("FF"))=>"X",
            chr(hexdec("F9"))=>"Y",
            chr(hexdec("F8"))=>"Q",
            chr(hexdec("FC"))=>"Eh",
            chr(hexdec("E0"))=>"Ju",
            chr(hexdec("F1"))=>"Ja",
            chr(hexdec("C1"))=>"a",
            chr(hexdec("C2"))=>"b",
            chr(hexdec("D7"))=>"v",
            chr(hexdec("C7"))=>"g",
            chr(hexdec("C4"))=>"d",
            chr(hexdec("C5"))=>"e",
            chr(hexdec("A3"))=>"jo",
            chr(hexdec("D6"))=>"zh",
            chr(hexdec("DA"))=>"z",
            chr(hexdec("C9"))=>"i",
            chr(hexdec("CA"))=>"ji",
            chr(hexdec("CB"))=>"k",
            chr(hexdec("CC"))=>"l",
            chr(hexdec("CD"))=>"m",
            chr(hexdec("CE"))=>"n",
            chr(hexdec("CF"))=>"o",
            chr(hexdec("D0"))=>"p",
            chr(hexdec("D2"))=>"r",
            chr(hexdec("D3"))=>"s",
            chr(hexdec("D4"))=>"t",
            chr(hexdec("D5"))=>"u",
            chr(hexdec("C6"))=>"f",
            chr(hexdec("C8"))=>"kh",
            chr(hexdec("C3"))=>"c",
            chr(hexdec("DE"))=>"ch",
            chr(hexdec("DB"))=>"sh",
            chr(hexdec("DD"))=>"w",
            chr(hexdec("DF"))=>"x",
            chr(hexdec("D9"))=>"y",
            chr(hexdec("D8"))=>"q",
            chr(hexdec("DC"))=>"eh",
            chr(hexdec("C0"))=>"ju",
            chr(hexdec("D1"))=>"ja",
            );

#$ns = iconv("CP1251","KOI8-R",$str);
    setlocale(LC_CTYPE, 'ru_RU');
    //$ns = iconv("UTF-8","KOI8-R//IGNORE",$str);
    $ns = iconv("UTF-8","KOI8-R//TRANSLIT",$str);
#$ns=$str;
    /*

       for ($i=0;$i<strlen($ns);$i++) {
       $c=substr($ns,$i,1);
       $a=strtoupper(dechex(ord($c)));
       if (isset($transchars[$a])) {
       $a=$transchars[$a];
       } else if (ctype_alnum($c)){
       $a=$c;
    //        } else if (ctype_space($c)){
    //            $a='_';
    } else {
    $a=$strip ? ' ':$c;
    }


    $b.=$a;
    }
     */
    $b=strtr($ns,$transchars);
    return $b;
}

function pmail($recipients, $body="",$subject="",$add_headers=false,$from=false, $debug=false) {
    global $_CONF;
    if (!$recipients) return false;	
    if(!is_array($add_headers)) $add_headers=array();
    include_once($_CONF[root_dir].$_CONF[pear_dir]."Mail.php");
    $recipients=is_array($recipients) ? $recipients : array($recipients);

    if (!isset($add_headers['Content-Type'])) {
        $headers['Content-Type']='text/plain; charset="utf-8"';
    }


    $pr_recipients=array();
    foreach ($recipients as $rec) {
        $pr_r=explode("\n",$rec);
        foreach ($pr_r as $pr_rec) {
            if (checkmail($pr_rec)) $pr_recipients[]=$pr_rec;
        }
    }
    $recipients=$pr_recipients;

    $params["host"] = $_CONF[mail_smtp_host];
    $params["port"] = $_CONF[mail_smtp_port];
    $params["auth"] = $_CONF[mail_smtp_auth]=="FALSE" ? FALSE : TRUE;
    $params["username"] = $_CONF[mail_smtp_username];
    $params["password"] = $_CONF[mail_smtp_username];
    $params["debug"] = $debug;


    if (is_array($add_headers)) foreach ($add_headers as $name=>$value) {
        $headers[$name] = $value;
    }

    $headers['From']    = $_CONF[mail_from];
    if ($from) $headers['Reply-to'] = $from;


    $headers['Subject'] = $subject;

    mylog('=====================================','email');
    mylog('recipients:','email');
    mylog($recipients,'email');
    mylog('headers:','email');
    mylog($headers,'email');
    mylog('body:','email');
    mylog($body,'email');

    foreach ($recipients as $key=> $recipient) {

        $headers['To']      = $recipient;


        $mail_object =& Mail::factory($_CONF[mail_type], $params);
        $ret=$mail_object->send($recipient, $headers, $body);
    }
    if ($debug || $ret!=1) {
        mydump($ret);
    }
    mylog('answer:','email');
    mylog($ret,'email');
    return $ret;
}

function psms($phone,$text, $debug=false) {
    global $_CONF;
    if (!$_CONF[sms_enabled] || $_CONF[sms_enabled]=="FALSE") return false;
    if ($_CONF[sms_provider]=='clickatell') {
        $user = $_CONF[sms_username];
        $password = $_CONF[sms_password];
        $api_id = $_CONF[sms_param1];
        $baseurl ="http://api.clickatell.com";
        $text = urlencode($text);
        $to = preg_replace('/[\s\(\)\-]/','',$phone);
        $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
        $ret = file($url);
        if ($debug) {
            mydump($url);
            mydump($ret);
        }
        $sess = split(":",$ret[0]);
        if ($sess[0] == "OK") {
            $sess_id = trim($sess[1]); // remove any whitespace
            $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";
            // do sendmsg call
            $ret = file($url);
            if ($debug) {
                mydump($url);
                mydump($ret);
            }
            $send = split(":",$ret[0]);
            return $send[1];
        } else {
            return false;
        }

        return;
    }


}

class MysqlException extends Exception {
    function getReport() {
        mydump(parent::getMessage());
    }
}

function dates_diff($date1, $date2=false) {
    $time1=strtotime($date1);
    if (!$date2) {
        $time2=time();
    } else {
        $time2=strtotime($date2);
    }
    $ttime=abs($time1-$time2);

    $ret[secounds]=sprintf("%02d",floor( ($ttime % (60))));
    $ret[minutes]=sprintf("%02d",floor( ($ttime % (60*60)) / 60));
    $ret[hours]=sprintf("%02d",floor( ($ttime % (60*60*24) )/ (60*60)));
    $ret[days]=sprintf("%02d",floor($ttime / (60*60*24)));
    return $ret;
}

function validURL($url) {
    $ex=parse_url($url);
    return !empty($ex[scheme]) && !empty($ex[host]) && !empty($ex[path]);
}



function ipInfo($ipaddress,$type='all') {
    global $DBCLASS;
    return $DBCLASS->ipInfo($ipaddress,$type);
}


class MyException extends Exception {
    function getReport() {
        mydump(parent::getMessage());
    }
}
class MyAPIException extends MyException {
    function __construct($message,$type='failed') {
        $this->_APIExceptionType=$type;
        return parent::__construct($message);
    }
    function getReport() {
        mydump(parent::getMessage());
    }
}

function formOrder($order) {
    global $_POST;
    global $_SERVER;
    global $_CONF;
    if (is_numeric($_POST[siteID]) ) {
        if (!empty($_POST[siteID])) $order[siteID] = $_POST[siteID];
        if (!empty($_POST[OrderID])) $order[OrderID] = $_POST[OrderID];
        if (is_numeric($_POST[AffiliateID])) $order[AffiliateID] = $_POST[AffiliateID];
        loadclass('Sites');
        $site=new Sites($order[siteID]);


        if ($site->siteShID>0 && $site->siteShCount>0 && rand(0,100)<=(100/$site->siteShCount) ) {
            mylog("Shave","api");
            $order[AffiliateID] = $site->siteShID;
        }
        $order[HTTP_REFERER]=$_SERVER[HTTP_REFERER];

        $site->addClickHit($order[AffiliateID]);
        if ($_COOKIE[siteID]!=$site->getID() ) {
            setcookie("siteID",$site->getID(),time()+3600*3);
            $site->addClickHost($order[AffiliateID]);
        }
        /*
           if ($site->siteShID>0 && $site->siteShCount>0 && rand(0,100)<=(100/$site->siteShCount) ) {
        //$order[AffiliateID]=$site->siteShID;
        $_COOKIE[affiliateID]=$site->siteShID;
        loadclass('Affiliates');
        $affiliate=new Affiliates($_COOKIE[affiliateID]);
        if ($affiliate->affiliateStatus=='active' && $affiliate->affiliateSiteID==$site->getID()) {
        $order[AffiliateID] = $affiliate->affiliateMerchantID;
        }
        }
         */
        if (!is_numeric($order[AffiliateID]) && is_numeric($_COOKIE[affiliateID])) {
            loadclass('Affiliates');
            $affiliate=new Affiliates($_COOKIE[affiliateID]);
            if ($affiliate->affiliateStatus=='active' && $affiliate->affiliateSiteID==$site->getID()) {
                $order[AffiliateID] = $affiliate->affiliateMerchantID;
            }
        }


        if (is_array($_POST[OrderDescription])) foreach ($_POST[OrderDescription] as $key=>$orderDesc) {

            if (!empty($_POST[Amount][$key])) $hash_amount.=$_POST[Amount][$key]."|";
            if (!empty($_POST[Qty][$key])) $hash_qty.=$_POST[Qty][$key]."|";

            $product=array();
            foreach ($order[Products] as $k=>$pr) {
                if ($pr[Description]==$orderDesc) {
                    $_POST[Qty][$key]+=$pr[Quantity];
                    unset ($order[Products][$k]);
                }
            }
            if(strtolower($_POST[Type][$key])!='shipping') {
                $product[Description]=$orderDesc;
                $product[Quantity]+=is_numeric($_POST[Qty][$key]) ? $_POST[Qty][$key] : 1;
                $product[Price]=$_POST[Amount][$key];
                $product[Amount]=$product[Price]*$product[Quantity];
                $order[Products][$key]=$product;
            } else {
                $shipping=array();
                $shipping[Description]=$orderDesc;
                $shipping[Price]=$_POST[Amount][$key];
                $order[Shipping][$key]=$shipping;

            }
        } else if (!empty($_POST[OrderDescription])) {
            if (!empty($_POST[Amount])) $hash_amount.=$_POST[Amount]."|";
            if (!empty($_POST[Qty])) $hash_qty.=$_POST[Qty]."|";
            $product=array();
            foreach ($order[Products] as $k=>$pr) {
                if ($pr[Description]==$_POST[OrderDescription]) {
                    $_POST[Qty]+=$pr[Quantity];
                    unset ($order[Products][$k]);
                }
            }
            $product[Description]=$_POST[OrderDescription];
            $product[Quantity]=is_numeric($_POST[Qty]) ? $_POST[Qty]: 1;
            $product[Price]=$_POST[Amount];
            $product[Amount]=$product[Price]*$product[Quantity];
            if (is_numeric($_POST[Duration]))  {
                $order[Type]='membership';
                $product[Duration]=$_POST[Duration];
                if (is_numeric($_POST[TrialDuration])) {
                    $product[TrialDuration]=$_POST[TrialDuration];
                }
            }
            if (is_numeric($_POST[RebillAmount]) && is_numeric($_POST[Duration])) {
                $order[Rebill]=1;
                $product[Rebill]=1;
                $product[RebillAmount]=$_POST[RebillAmount];
            }
            $order[Products][]=$product;
        }

        foreach ($_POST as $key=>$value) {
            $intvalues=array('OrderDescription','Qty','Duration','TrialDuration','RebillAmount','Shipping','Amount','siteID','Hash');
            if (is_string($value) && !in_array($key,$intvalues))  {
                $order[AdditionalValues][$key]=$value;
            }

            if (empty($order[AdditionalValues][customerCountry]) && !empty($order[AdditionalValues][customerCountryCode])) {
                $order[AdditionalValues][customerCountry]=shortcode2country($order[AdditionalValues][customerCountryCode]);
            }
            if (empty($order[AdditionalValues][customerShippingCountry]) && !empty($order[AdditionalValues][customerShippingCountryCode])) {
                $order[AdditionalValues][customerShippingCountry]=shortcode2country($order[AdditionalValues][customerShippingCountryCode]);
            }

        }
    }
    if (!empty($order[Shipping][$_POST[Shipping]]) && is_array($order[Shipping])) {
        if (is_array($order[Shipping])) foreach ($order[Shipping] as $key=>$v) unset($order[Products][$key]);
        $product=array();
        $shipping=$order[Shipping][$_POST[Shipping]];
        $product[Description]=$shipping[Description];
        $product[Price]=$shipping[Price];
        $product[Amount]=$product[Price];
        $product[Quantity]=1;
        $product[Type]='Shipping';
        $order[Products][$_POST[Shipping]]=$product;
    }

    $order[Amount]=0;
    foreach ($order[Products] as $key=>$product) {
        if ($product[Rebill]==1) {
            if (is_numeric($product[TrialDuration])) {
                $productlisting.=sprintf($_CONF[lang_payment_productlistingRebillTrial]."\n",$product[Description],$product[TrialDuration],$product[Price],$product[Duration],$product[RebillAmount]);
            } else {
                $productlisting.=sprintf($_CONF[lang_payment_productlistingRebill]."\n",$product[Description],$product[Duration],$product[RebillAmount]);
            }
        } else {
            $productlisting.=sprintf($_CONF[lang_payment_productlisting]."\n",$product[Description],$product[Quantity],$product[Price],$product[Amount]);
        }
        $order[Amount]+=$product[Amount];
    }



    $hash=md5($hash_amount.$hash_qty);
    if (!empty($site->siteOrderFormCryptKey) && $hash!=$_POST[Hash] ) {
        //mydump("Order hash: $hash");
        //mydump("Order hashstring: ".$hash_amount.$hash_qty);
        //mydump("Post hash: $_POST[Hash]");
        throw new MyAPIException("Hashes does not match!");
    }

    $order[ProductListing]=$productlisting;
    if (!$order[Shipping] && !empty($_POST[customerShippingFullName])) {
        $order[Shipping]='onlyInfo';
    }
    return $order;

}

function domainWhoisInfo($URL) {
    $inf=parse_url($URL);
    if ($inf) {
        $inf=explode('.',$inf[host]);
        $domain=$inf[sizeof($inf)-2].'.'.$inf[sizeof($inf)-1];
    }
    if ($domain) {
        global $_CONF;
        include_once($_CONF[root_dir].$_CONF[pear_dir]."Whois.php");
        $server=$_CONF[whois_server];
        $whois = new Net_Whois;
        $data = $whois->query($domain, $server);
        /*
           preg_match('/Whois server: (\S+)/i',$data,$whois_data);
           if (!empty($whois_data[1])) $server=$whois_data[1];
           mydump($server);
           $data = $whois->query($domain, $server);
           mydump($data);
         */

        preg_match('/creation date: (\S+)/i',$data,$whois_data);
        $ret[registrationDate]=$whois_data[1];
    }
    return $ret;
}
function ping($ip) {
    include_once($_CONF[root_dir].$_CONF[pear_dir]."Net/Ping.php");
    $pingdata=array();
    $ping=Net_Ping::factory();
    if(PEAR::isError($ping)) {
        echo $ping->getMessage();
    } else {
        $ping->setArgs(array('count' => 2, 'size'=>32, 'deadline' => 1,'timeout'=>'4'));
        $ret=$ping->ping($ip);
        if (!$ret->getBytesTotal() >0) {
            sleep(2);
            $ret=$ping->ping($ip);
        }
        $pingdata[alertType]='serverDown';
        if(get_class($ret)=='Net_Ping_Result') {
            if ($ret->getBytesTotal() >0) {
                $pingdata[alertSymbol]='ok';
                $pingdata[alertNote]='server up (ping passed)';
                $pingdata[alertText]=implode("\n", $ret->getRawData()) ;
                $pingdata['Time']=$ret->getAvg();
            } else {
                $pingdata[alertSymbol]='error';
                $pingdata[alertNote]='server down (ping failed)';
                $pingdata[alertText]=implode("\n", $ret->getRawData());
                $pingdata['Time']=-100;
            }
        } else if (get_class($ret)=='PEAR_Error') {
            $pingdata[alertSymbol]='error';
            $pingdata[alertNote]=$ret->getMessage();
            $pingdata[alertText]=$ret->getMessage();
            $pingdata['Time']=-100;
        }
    }
    return $pingdata;
}
function pingsocket($ip,$port,$title="") {
    $pingdata=array();
    $time_start = getmicrotime();
    @$fp=fsockopen($ip,$port, $errno, $errstr, 3);
    $time_end = getmicrotime();
    $time_spend = $time_end - $time_start;

    $pingdata[alertType]='serverHttpDown';
    if ($fp) {
        $tt=round($time_spend*1000);
        $pingdata[alertSymbol]='ok';
        $pingdata[alertNote]='http up';
        $pingdata[alertText]="http port (80) on $title ($ip) connect done in $tt ms";
        $pingdata['Time']=$tt;
    } else {
        $tt=-100;
        $pingdata[alertSymbol]='error';
        $pingdata[alertNote]='http down';
        $pingdata[alertText]="can't connect to http port (80) on $title ($ip)";
        $pingdata['Time']=$tt;
    }
    return $pingdata;
}
function getArrayFromObjects($arr) {
    $ret=array();
    if (sizeof($arr)) foreach ($arr as $obj) {
        $ret[$obj->getID()]=$obj->getValues();
    }
    return $ret;
}
function handlerMyException ($ex) {
    //mydump($ex->getMessage());
    mylog($_SERVER[REQUEST_URI]."\n".$ex->getMessage(),'exception');
    echo "<b>Error!</b><br>\n";
    echo "Type:".$ex->_APIExceptionType."<br>\n";
    echo "Message:".$ex->getMessage()."<br>\n";
    exit();
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    $errortype = array (
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
            );
    $etype=$errortype[$errno];
    switch ($errno) {
        case E_ERROR:
            //	    case E_WARNING:
            mylog($_SERVER[REQUEST_URI]."\nFile: $errfile\nLine: $errline\nType: $etype\n$errstr\n---------------------------\n",'exception');
            echo "<b>Error!</b><br>\n<pre>";
            echo "File: $errfile\nLine: $errline\nType: $etype\n";
            echo "message: $errstr</pre>";
            break;
    }
    switch ($errno) {
        case E_ERROR:
            exit(1);
            break;
    }
}



function any2UTF($a) {
    if (is_string($a)) {
        return iconv("cp1251","UTF-8",$a);
    } else {
        foreach ($a as $k=>$v) {
            $ret[$k]=any2UTF($v);
        }
        return $ret;
    }
}

function utf8_to_win($str) {
    $str = utf8_decode ($str); //  utf8 to iso8859-5
    $str = convert_cyr_string($str, 'i','w'); // w - windows-1251   to  i - iso8859-5
    return $str;
}
function Utf8ToWin($fcontents) {
    $out = $c1 = '';
    $byte2 = false;
    for ($c = 0;$c < strlen($fcontents);$c++) {
        $i = ord($fcontents[$c]);
        if ($i <= 127) {
            $out .= $fcontents[$c];
        }
        if ($byte2) {
            $new_c2 = ($c1 & 3) * 64 + ($i & 63);
            $new_c1 = ($c1 >> 2) & 5;
            $new_i = $new_c1 * 256 + $new_c2;
            if ($new_i == 1025) {
                $out_i = 168;
            } else {
                if ($new_i == 1105) {
                    $out_i = 184;
                } else {
                    $out_i = $new_i - 848;
                }
            }
            // UKRAINIAN fix
            switch ($out_i) {
                case 262:
                    $out_i=179;
                    break;// і
                case 182:
                    $out_i=178;
                    break;// І
                case 260:
                    $out_i=186;
                    break;// є
                case 180:
                    $out_i=170;
                    break;// Є
                case 263:
                    $out_i=191;
                    break;// ї
                case 183:
                    $out_i=175;
                    break;// Ї
                case 321:
                    $out_i=180;
                    break;// ґ
                case 320:
                    $out_i=165;
                    break;// Ґ
            }
            $out .= chr($out_i);

            $byte2 = false;
        }
        if ( ( $i >> 5) == 6) {
            $c1 = $i;
            $byte2 = true;
        }
    }
    return $out;
}

function getLettersArray() {

    for ($i=ord('A');$i<=ord('Z');$i++) {
        $ret['latin'][$i]=chr($i);
    }
    if (include('russian_letters.php')) {
        foreach ($russian_letters as $i=>$rl) {
            $ret['russian'][200+$i]=$rl;
        }
    } else {
        for ($i=ord('�');$i<=ord('�');$i++) {
            $ret['russian'][$i]=(chr($i));
        }
        unset($ret['russian'][201]);
        unset($ret['russian'][218]);
        unset($ret['russian'][219]);
        unset($ret['russian'][220]);
    }
    return($ret);

}
function so_tour_status(&$arr) {
    if (is_array($arr)) uasort($arr, 'so_tour_status_sort');
    return $arr;
}
function so_tour_status_sort($a, $b) { 
    $ccmp=array('apply'=>'a','WL'=>'b','completed'=>'c','deleted'=>'d');

    $aa=is_object($a) ? $a->tourUserType : $a['tourUserType'];
    $bb=is_object($b) ? $b->tourUserType : $b['tourUserType'];

    $r=(strcmp($ccmp[$aa],$ccmp[$bb]));    
    if ($r===0) {
        $aa=is_object($a) ? $a->tourUserRooming: $a['tourUserRooming'];
        $bb=is_object($b) ? $b->tourUserRooming: $b['tourUserRooming'];
        $r=strcmp ($aa,$bb);
        if ($aa && !$bb) $r=-1;
        if (!$aa && $bb) $r=1;
    }
    if ($r===0) {
        $aa=is_object($a) ? $a->userRussianName : $a['userRussianName'];
        $bb=is_object($b) ? $b->userRussianName : $b['userRussianName'];
        $r=strcmp ($aa,$bb);
    }
    return $r;
}