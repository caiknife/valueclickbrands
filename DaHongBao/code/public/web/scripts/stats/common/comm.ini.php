<?php
if (__ROOT_PATH == '/') {
    define("__TRACK_PATH", substr(realpath(dirname(__FILE__)), 0, -6));
}else{
    define("__TRACK_PATH", __ROOT_PATH ."scripts/stats/");
}

define("T_ROBOTS_FILE_PATH", __TRACK_PATH .'config/robots.txt');
define("T_IGNOREDIP_FILE_PATH", __TRACK_PATH .'config/IgnoredIP.txt');

define("__SITE_ID",             38 );
define("__SPONSOR_TYPE",        2  );
define("__DOMAIN_NAME",         '/');

//define redirect type
define("__REDIRECT_CPC", 1);
define("__REDIRECT_CPA", 2);
define("__REDIRECT_SL", 3);
define("__REDIRECT_BANNER", 4);
define("__REDIRECT_SPECIAL", 5);
define("__REDIRECT_BLOG", 6);
define("__REDIRECT_SMARTER", 7);
define("__REDIRECT_NORMAL", 1);

//define affilite id
define("__AFF_BEFREE", 1);
define("__AFF_CJ", 2);
define("__AFF_LINKSHARE", 3);
define("__AFF_PERFORMICS", 4);

//define cmus affiliate tag
define("__SITE_AFF", '_s' . __SITE_ID);

//the number of valid coupon clicks
define('MAX_COUPON_CLK', 4);

//define cookie domain
$arrDomain = explode(".", $_SERVER['SERVER_NAME']);
$Domain = "." . $arrDomain[count($arrDomain)-2]. "." . $arrDomain[count($arrDomain)-1];
define("__T_COOKIE_DOMAIN", $Domain);

//set channel id
$gChannelArr = array('www'=>1, 'blog'=>2);
define("__T_CHANNEL", array_key_exists(strtolower($arrDomain[0]), $gChannelArr)? $gChannelArr[strtolower($arrDomain[0])] : 0);

$gAffHostIDs = array(
    '.linksynergy.com'      => __AFF_LINKSHARE,
    '.linkshare.com'        => __AFF_LINKSHARE,
    '.silhouettes.com'      => __AFF_LINKSHARE,
    '.blimpie.com'          => __AFF_LINKSHARE,
    'clickserve.cc-dt.com'  => __AFF_PERFORMICS,
    '.shareasale.com'       => __AFF_PERFORMICS,
    '.bfast.com'            => __AFF_BEFREE,
    '.qksrv.net'            => __AFF_CJ,
    '.tkqlhce.com'          => __AFF_CJ,
    '.jdoqocy.com'          => __AFF_CJ,
    '.anrdoezrs.net'        => __AFF_CJ,
    '.dpbolvw.net'          => __AFF_CJ,
    '.kqzyfj.com'           => __AFF_CJ,
    '.cvs.com'              => __AFF_CJ,
    'ad.zanox.com'          => __AFF_CJ,
);

$gAffTags= array(
    __AFF_LINKSHARE         => '&u1=',
    __AFF_PERFORMICS        => '&mid=',
    __AFF_BEFREE            => '&bfinfo=',
    __AFF_CJ                => '?SID=',
);

require_once __TRACK_PATH . '/lib/func.tracking.php';
require_once __TRACK_PATH . '/lib/db-mysql.inc.php';
require_once __TRACK_PATH . '/lib/tracking.inc.php';
require_once __TRACK_PATH . 'incoming.php';
