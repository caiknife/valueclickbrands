<?php
/*
 * Private IP 2006-7-25
 * 10.0.0.0 - 10.255.255.255
 * 172.16.0.0 - 172.31.255.255
 * 192.168.0.0 - 192.168.255.255
 */
function isPrivateIP($ip){
	if(ip2long($ip) <> false && ip2long($ip) <> -1){
		$arrIpSeg = explode(".", $ip);
		if(strcmp($arrIpSeg[0], "10") == 0)	{
			return true;
		}else if(strcmp($arrIpSeg[0], "192") == 0 && strcmp($arrIpSeg[1], "168") == 0)	{
			return true;
		}else if(strcmp($arrIpSeg[0], "172") == 0 && intval($arrIpSeg[1]) >= 16 && intval($arrIpSeg[1]) <= 31)	{
			return true;
		}else{
			return false;
		}
	}
	return false;
}

function noticeEmail($IP){
    $mailTo			= array('Paterick@mezimedia.com', 'lee@mezimedia.com');
	$mailSubject    = "Doubtful IP";
	$mailContentStr	= "$IP need notarizing!!";
	$mailToStr = implode(",", $mailTo);
	$headers .= "From: CM AUTO Tracking \r\n";

	return mail($mailToStr, $mailSubject, $mailContentStr, $headers);
}

function getRedirUrl($rAffTag, $rUrl, $rOutgingID){
	$rAffTag = $rAffTag . $rOutgingID . __SITE_AFF;
    //format correct redirect url
    $arrUrls = parse_url($rUrl);
	if ($rAffTag != "") {
		if (isset($arrUrls['query']) && $arrUrls['query'] != "" && strpos($rAffTag, '?') === 0) {// has query argv, is cj
			$rUrl = $arrUrls['scheme'] .'://'. $arrUrls['host'] . $arrUrls['path']. $rAffTag ."&". $arrUrls['query'];

		}elseif ((isset($arrUrls['query']) && $arrUrls['query'] != "") || strpos($rAffTag, '?') === 0){ // has query argv or is cj
			$rUrl = $rUrl . $rAffTag;

		}else{  // no query argv, except cj
			$rUrl = $rUrl ."?". $rAffTag;
		}
	}

	return $rUrl;
}

list($microSecond, $intSecond) = explode(' ', microtime());

/**
 * return string
 */
function getTimeStr(){
    global $intSecond;
    return date('Y-m-d H:i:s', $intSecond);
}

/**
 * return integer
 */
function getMicrosecond(){
    global $microSecond;
    return (integer) (1000 * $microSecond);
}

/**
 * filter the configuration param
 * filter begin with "#"
 * call back function
 * @param var
 * return bool
 */
function filter($var){
    return (substr(trim($var), 0, 1) != "#") && (trim($var) != "");
}

/**
 * filter the configuration param
 * trim space
 * call back function
 * @param var
 * return bool
 */
function filterSpace($var){
    return trim($var);
}

/**
 * get robot list configuration
 * @param void
 * return array
 */
function getRobotsList(){
    $tmpArr = file(T_ROBOTS_FILE_PATH);
    if(!$tmpArr){
        return '';
    }else{
        $tmpArr = array_filter($tmpArr, "filter");
        $tmpArr = array_map("filterSpace", $tmpArr);
        return $tmpArr;
    }
}

/**
 * get fraud IP list configuration
 * @param void
 * return array
 */
function getFraudIPList(){
    $tmpArr = file(T_IGNOREDIP_FILE_PATH);
    if(!$tmpArr){
        return "";
    }else{
        $tmpArr = array_filter($tmpArr, "filter");
        $tmpArr = array_map("filterSpace", $tmpArr);
        return $tmpArr;

    }
}

/**
 * get the client IP
 * @param void
 * return string
 */
function getIp()
{
    //return $_SERVER["REMOTE_ADDR"];
	if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !=""){
		return $_SERVER["HTTP_RLNCLIENTIPADDR"];
	}else{
		return $_SERVER['REMOTE_ADDR'];
	}
}

/**
 * get the client user agent
 * @param void
 * return string
 */
function getHttpUserAgent()
{
    return $_SERVER["HTTP_USER_AGENT"];
}

/**
 * get the source from URL
 * @param string
 * return string
 */
function getSourceTag($url){
    $tmpUrl = rtrim($url);
    if(strpos($tmpUrl, "source=") === false){
        return "";
    }else{
        $pos = strpos($tmpUrl, "source=");
        $tmpUrl = substr($tmpUrl, $pos);
        if((strpos($tmpUrl, "&") !== false)){
            $position = strpos($tmpUrl, "&");
            $source = substr($tmpUrl, 7, $position-7);
            if(!$source){
                $source = "none";
            }
        }else{
            $source = substr($tmpUrl, 7);
            if(!$source){
                $source = "none";
            }
        }
        return $source;
    }
}

/**
 * get the sourceGroup from $source
 * @param string
 * return string
 */
function getSourceGroup($source){
    if(!$source){
        return "";
    }else{
        $tmpArr = explode("_", $source);
        return $tmpArr[0];
    }
}

function getSessionId() {
    global $nSessionID;
    return (integer) (isset($_COOKIE['TRACKING_USER_SESSION']) ? trim($_COOKIE['TRACKING_USER_SESSION'])   : $nSessionID);
}

function getPreRandStr() {
	global $preRandStr;
    return isset($_COOKIE['TRACKING_PRE_RANDSTR'])   ? trim($_COOKIE['TRACKING_PRE_RANDSTR'])    : $preRandStr;
}

function getCurRandStr() {
    global $curRandStr;
    return isset($_COOKIE['TRACKING_CUR_RANDSTR'])   ? trim($_COOKIE['TRACKING_CUR_RANDSTR'])    : $curRandStr;
}

function getTrafficType() {
	global $nTrafficType;
	return (integer) (isset($_COOKIE['TRACKING_TRAFFIC_TYPE'])  ? trim($_COOKIE['TRACKING_TRAFFIC_TYPE'])   : $nTrafficType);
}

define('MAX_REFERER_LENGTH',    512);

/**
 * set httpreferer for session
 *
 * @return Tracking_Session
 */
function setLandingReferer($referer = null)
{
    global $nLandingReferer;

    if(empty($referer)) { return; }

    $nLandingReferer = substr($referer, 0, MAX_REFERER_LENGTH);
    setcookie('TRACKING_LANDING_REFERER', base64_encode(gzdeflate($nLandingReferer)), 0, '/', __T_COOKIE_DOMAIN);
}

/**
 * get httpreferer from session
 *
 * @return string
 */
function getLandingReferer()
{
    global $nLandingReferer;

    return isset($_COOKIE['TRACKING_LANDING_REFERER'])
        ? (string) gzinflate(base64_decode($_COOKIE['TRACKING_LANDING_REFERER']))
        : $nLandingReferer;
}


function statKeyword()
{
    global $semKeyword;
    return isset($_COOKIE['TRACKING_LANDING_SEARCH'])? $_COOKIE['TRACKING_LANDING_SEARCH'] : $semKeyword;
}

function statSourceGroup()
{
    global $nSourceGroup;
    return isset($_COOKIE['TRACKING_SOURCE_GROUP'])   ? trim($_COOKIE['TRACKING_SOURCE_GROUP'])    : $nSourceGroup;
}

function statSource()
{
    global $nSource;
    return isset($_COOKIE['TRACKING_SOURCE'])   ? trim($_COOKIE['TRACKING_SOURCE'])    : $nSource;
}

/**
 * send mail
 * @param array  $to
 * @param string $subject
 * @param string $message
 * @param string $from
 * @param string $reply
 * @param string $xmail
 * return bool
 */
function send_mail($to, $subject, $message, $from = "", $reply = "", $xmail = ""){
    if(!is_array($to)){
        return false;
    }
    $toStr     = implode(",", $to);
    $subject   = $subject ? $subject : "undefined mail subject";
    $subject   = $subject ? $subject : "undefined mail contents";
    $from      = $from ? $from : "trackingMonitor@".$_SERVER['SERVER_NAME'];
    $reply     = $reply ? $reply : "(no reply)trackingMonitor@".$_SERVER['SERVER_NAME'];
    $xmail     = $xmail ? $xmail : "PHP Xmail ".phpversion();
    $headerStr = "From: ".$from."\r\n"."Reply-To: ".$reply."\r\n"."X-Mailer: ".$xmail;
    if(mail($toStr, $subject, $message, $headerStr)){
        return true;
    }else{
        return false;
    }
}

/**
 * add an userAgent to robots list
 * @param string  $userAgent
 * return bool
 */
function add2RobotsList($userAgent){
    $fp = fopen(T_ROBOTS_FILE_PATH, "a");
    $str = "# add by system ".date("Y-m-d H:i:s")."\n";
    $str .= "\"".$userAgent."\"\n";
    if(fwrite($fp, $str)){
        return false;
    }else{
        return true;
    }
}

/**
 * add an ip to fraudIP list
 * @param string  $ip
 * return bool
 */
function add2FraudIPList($ip){
    $fp = fopen(T_IGNOREDIP_FILE_PATH, "a");
    $str = "# add by system ".date("Y-m-d H:i:s")."\n";
    $str .= "\"".$ip."\"\n";
    if(fwrite($fp, $str)){
        return false;
    }else{
        return true;
    }
}

/**
 * get current visitor availability
 *
 * @param  none
 * @return bool
 */
function getVisitorAvailability(){
    global $nStatTrafficType;
    $tmp              = (int)$nStatTrafficType;
    $res              = ($tmp === 0) ? true : false;
    return $res;
}

//get rand number
function createRandNum(){
	return md5(uniqid(rand(), true));
}

function cleanUrlSource($requestUrl){
	$tpl = '/source=[^&]*/';
	$url = preg_replace($tpl, "", $requestUrl, 1);
	$url = str_replace('&&', '&', $url);
	$url = str_replace('?&', '?', $url);
	$tpl = '/[&|\?]$/';
	$url = preg_replace($tpl, "", $url, 1);
	return $url;
} // end func

function filterPreRandstr(){
	if(strpos($_SERVER['REQUEST_URI'], 'redir.php') === false
		&& strpos($_SERVER['REQUEST_URI'], 'send_friend.php') === false
		&& strpos($_SERVER['REQUEST_URI'], '/add') !== 0
		&& strpos($_SERVER['REQUEST_URI'], '/images') !== 0
		&& strpos($_SERVER['REQUEST_URI'], '/notfound.html') !== 0){
		return true;
	}
	return false;
}

function redirectUrl($url, $isPermanent=0){
	$url = str_replace("\n", "", $url);
	$url = str_replace("\r", "", $url);
	$url = str_replace("\t", "", $url);
	if ( $_GET['test'] == 'yes' ){
		echo("Location: ". $url);
	}else{
		if($isPermanent == 1){
			header("HTTP/1.1 301 Moved Permanently");
		}
		header("Location: ". $url);
	}
	exit;
}

function fetchSemKeyword()
{
    return isset($_GET['q']) ? $_GET['q'] : null;
}

function fetchSearchKwd($url=''){
	$m = array();
	if (isset($_REQUEST['searchText']) && $_REQUEST['searchText'] != '') {
		return md5($_REQUEST['searchText']);

	}elseif ($url !='' && preg_match('/coupon-codes--se-(.*)\\.html/', $url, $m)){
		return md5(urldecode($m[1]));

	}elseif ($url !='' && preg_match('/coupon-codes--se-(.*)--p/', $url, $m)){
		return md5(urldecode($m[2]));
	}

	return false;
}

/**
 * generate random user interface
 *
 * @param integer $min default = 1
 * @param integer $max default = 20
 * @return integer
 */
function getUserInterface($min = 1, $max = 20)
{
	return mt_rand($min, $max);
}

/**
 * get rand num from cookie
 *
 * @return str
 */
function getTplTypeNum(){
	return (isset($_COOKIE['TRACKING_TPL_TYPE']) && $_COOKIE['TRACKING_TPL_TYPE'] > 0)? $_COOKIE['TRACKING_TPL_TYPE'] : $GLOBALS['TplType'];
}
