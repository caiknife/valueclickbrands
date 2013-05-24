<?php

/*** *** ***
 *  new tracking by patrick 02/20/2006
 *
 *  method of fetching coupons clicked: p1@p2@p3@pN
 **/

// set new session tag : we suppost it were new session when the script was included!
$_newSession = 1;// 1: create;

// set coupon string
$nCouponStr = "";
$nCouponArr = array();

// get coupon
$nCouponID = 0;
$nCouponID = $_GET['p'] > 0 ? $_GET['p'] : $_POST['p'];

// get http_referer
$nHttpReferer = isset($_GET['r'])? base64_decode($_GET['r']) : "";
if($nHttpReferer == "" && isset($_SERVER['HTTP_REFERER'])){
    $nHttpReferer = $_SERVER['HTTP_REFERER'];
}

// get env request uri
$nRequestURI = $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : "";

// get cookie traffic type
$nTrafficType = isset($_COOKIE['TRACKING_TRAFFIC_TYPE'])? (integer) $_COOKIE['TRACKING_TRAFFIC_TYPE'] : 0;

$nSessionID = isset($_COOKIE['TRACKING_USER_SESSION'])? (integer) $_COOKIE['TRACKING_USER_SESSION'] : 0;
if($nSessionID > 0){
    if($_isPartner == 1 && $nCouponID > 0){
        if( isset($_COOKIE['CK_COUPON'])){
            $nCouponArr = explode("@", $_COOKIE['CK_COUPON']);
            if(is_array($nCouponArr) && in_array($$nCouponID, $nCouponArr)){
                $_newSession = 0;
                // do not create new session
            }
        }
    }else{
        $_newSession = 0;
    }

    $cookieSource  = isset($_COOKIE['TRACKING_SOURCE']) ? $_COOKIE['TRACKING_SOURCE'] : '';
    $requestSource = getSourceTag($nRequestURI);
    if($requestSource!='' && $requestSource != $cookieSource) {
        $_newSession = 1;
    }
}

//create new session
if($_newSession == 1){
    //get env ip
    $nIP = getIp();

    //get env user agent
    $nHttpUserAgent = getHttpUserAgent();

    //get source
    $nSource = getSourceTag($nRequestURI);

    //get source group
    $nSourceGroup = getSourceGroup($nSource);

    //check traffic type
    $arrSpiders       = getRobotsList();
    $arrIgnoreIPs     = getFraudIPList();
    $nTrafficType = 0;
    //user Agent empty
    if ($nTrafficType == 0){
        if($nHttpUserAgent == ""){
            $nTrafficType = -3;
        }
    }

    //robots
    if ($nTrafficType == 0){
        foreach($arrSpiders as $v){
            if(strpos(strtolower($nHttpUserAgent), strtolower($v)) === false){
            }else{
                $nTrafficType = -1;
                break;
            }
        }
    }

    //ignored IP
    if ($nTrafficType == 0){
        foreach($arrIgnoreIPs as $v){
            if($v == $nIP){
                $nTrafficType = -2;
                break;
            }
        }
    }

    // private IP
    if ($nTrafficType == 0){
        if (isPrivateIP($nIP)) {
            $nTrafficType = -4;
        };
    }
    if (Tracking_Session::getInstance()->getUiType() === null) {
        Tracking_Session::getInstance()->setUiType(mt_rand(1, 20));
    }
    $nStatTplType = Tracking_Session::getInstance()->getUiType();
    setcookie('TRACKING_TPL_TYPE', $nStatTplType, 0, '/', __T_COOKIE_DOMAIN);
    $_COOKIE["TRACKING_TPL_TYPE"]            = $nStatTplType;
    $GLOBALS['TplType']                        = $nStatTplType;

    /** For SEM Would like to add referer based google subtag mapping **/
    setLandingReferer($nHttpReferer);

    //add new session
    $logIncoming = array(
        'clientIp'      => $nIP,
        'valid'         => $nTrafficType,
        'source'        => $nSource,
        'sourceGroup'   => $nSourceGroup,
        'referer'       => $nHttpReferer,
        'requestUri'    => $nRequestURI,
        'userAgent'     => $nHttpUserAgent,
        'channelId'     => __T_CHANNEL,
        'tplType'       => $nStatTplType
    );
    $nSessionID = Tracking::getInstance()->addIncomingLog($logIncoming);

    // set coupon cookie
    if($_isPartner == 1 && $nCouponID > 0){
        //add new coupon into coupon array
        array_push($_couponArr, $nCouponID);
        $nCouponStr = implode("@", $nCouponArr);
        setcookie('CK_COUPON', $nCouponStr, 0, '/', __T_COOKIE_DOMAIN);
    }

    //set incoming cookie
    setcookie('TRACKING_USER_SESSION', $nSessionID,   0, '/', __T_COOKIE_DOMAIN);
    setcookie('TRACKING_TRAFFIC_TYPE', $nTrafficType, 0, '/', __T_COOKIE_DOMAIN);
    setcookie('TRACKING_SOURCE_GROUP', $nSourceGroup, 0, '/', __T_COOKIE_DOMAIN);
    setcookie('TRACKING_SOURCE',       $nSource,      0, '/', __T_COOKIE_DOMAIN);

    //set landing search keyword
    $isSemLanding = empty($nSource) ? 0 : 1;
    if ($isSemLanding && ($semKeyword = fetchSemKeyword())) {
        setcookie('TRACKING_LANDING_SEARCH', $semKeyword, 0, '/', __T_COOKIE_DOMAIN);
    }
}

$preRandStr = isset($_COOKIE['TRACKING_PRE_RANDSTR'])? $_COOKIE['TRACKING_PRE_RANDSTR'] : "";
$curRandStr = createRandNum();
if($nTrafficType >= 0){
    $logPageVisit = array(
        'sessionId'     => $nSessionID,
        'visitOrder'    => getMicrosecond(),
        'requestUri'    => $nRequestURI,
        'referer'       => $nHttpReferer,
        'curRequestId'  => $curRandStr,
        'channelId'     => __T_CHANNEL,
    );
    Tracking::getInstance()->addPageVisitLog($logPageVisit);

    if (filterPreRandstr()) {
        $preRandStr = $curRandStr;
        setcookie("TRACKING_PRE_RANDSTR", $curRandStr, 0, "/", __T_COOKIE_DOMAIN);
    }
}