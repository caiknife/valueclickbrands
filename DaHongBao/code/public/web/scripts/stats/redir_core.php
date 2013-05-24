<?php

/** if request url hasn't any parameters then redirect to home page */
if (!parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
    return;
}

require_once dirname(__FILE__) . '/../../etc/const.inc.php';

require_once __INCLUDE_ROOT.'lib/classes/class.Coupon.php';
require_once __INCLUDE_ROOT.'lib/classes/class.Merchant.php';

$isRecord = true;

//check new user
$nTrafficType = (integer) (isset($_COOKIE['TRACKING_TRAFFIC_TYPE']) ? (integer) $_COOKIE['TRACKING_TRAFFIC_TYPE']  : $nTrafficType);
$nSessionID   = isset($_COOKIE['TRACKING_USER_SESSION']) ? trim($_COOKIE['TRACKING_USER_SESSION'])      : $nSessionID;

//get url queries
$nCategoryID = isset($_GET['c']) ? $_GET['c'] : 0;
$nCouponID   = isset($_GET['p'])? $_GET['p'] : 0;
$nMerchantID = isset($_GET['m'])? $_GET['m'] : 0;

//check valid coupon click
$tag = $nCouponID . $nMerchantID;
$coupon_clk = 0;
if (isset($_COOKIE['offers']) && is_array($_COOKIE['offers']) && array_key_exists($tag, $_COOKIE['offers'])) {
    $coupon_clk = $_COOKIE['offers'][$tag] + 1;
}else{
    $coupon_clk++;
}

//set cookie of offers
setcookie("offers[{$tag}]", $coupon_clk, 0, "/", __T_COOKIE_DOMAIN);

//get redirect type
$nRedirectType = isset($_GET['rdtp']) ? (integer) trim($_GET['rdtp']) : 0;
$para = array(
    'm'             => 0,
    'p'             => 0,
    'c'             => 0,
    'rdtp'          => $nRedirectType
);

switch ($nRedirectType){
    case __REDIRECT_BANNER:
        $url = isset($_GET['url']) ? base64_decode(trim($_GET['url'])) : "";
        $pos = isset($_GET['pos']) ? $_GET['pos'] : 0;
        $para['url'] = $url;
        $para['pos'] = $pos;
        Tracking::getInstance()->addBannerClickLog($para);
        break;

    case __REDIRECT_BLOG:
    case __REDIRECT_SPECIAL:
    case __REDIRECT_SMARTER:
        //get Redirect URL
        $url = isset($_GET['url'])? base64_decode(trim($_GET['url'])) : "";
        $para['url'] = $url;
        break;

    case __REDIRECT_CPA:
    default:
        $para['m'] = $nMerchantID;
        $para['c'] = $nCategoryID;
        $para['p'] = $nCouponID;
        $para['rdtp'] = __REDIRECT_CPA;

        //set traffic type if the clicks great than our defination
        $nTrafficType = ($nTrafficType >= 0 && $coupon_clk > MAX_COUPON_CLK)?  -4 : $nTrafficType;

        if ( $nCouponID > 0 ){ //has coupon
           $oCoupon = new Coupon($nCouponID);
           $nMerchantID = $nMerchantID > 0? $nMerchantID : $oCoupon->get('Merchant_');
           // invalid coupon
           if ($oCoupon->canShow() ==0 || $oCoupon->get('isActive') == 0){
                $redir_url = __DOMAIN_NAME;
                break;
           }

           $url = $oCoupon->get('URL');
            if($url == '' && $nMerchantID > 0){//get merchant url
                $oMerchant = new Merchant($nMerchantID);
                $url = $oMerchant->get('URL');
            }

            if($url == ''){
                $url = __DOMAIN_NAME;
                break;
            }

        }elseif($nMerchantID > 0){// has merchant
            //get merchant url
            $oMerchant = new Merchant($nMerchantID);
            $url = $oMerchant->get('URL');

        }else{ //no coupon no merchant
            $url = __DOMAIN_NAME;
                break;
        }
        break;
}

// create outgoing id
$para['valid'] = $nTrafficType;
$nOutgoingID = Tracking::getInstance()->addOutgoingLog($para);

// format redirect url
$nRedirectUrl = $url;

$para['outid']   = $nOutgoingID;
$para['url']   = $nRedirectUrl;

// set outgoing info
Tracking::getInstance()->setOutgoingLog($para);
