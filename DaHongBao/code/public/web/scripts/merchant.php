<?php
$t_array = explode(' ', microtime());
$P_S_T = $t_array[0] + $t_array[1];
session_start();
require_once ("../etc/const.inc.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once (__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once (__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once (__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once (__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once (__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once (__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

//cache
require_once "Cache/Lite.php";
$options = array ('cacheDir' => __FILE_FULLPATH.'cache/', 'lifeTime' => 0.5 * 60 * 60, 'pearErrorMode' => CACHE_LITE_ERROR_DIE);
$cache = new Cache_Lite($options);

$newsinfo = array ("72" => "17", "70" => "18", "66" => "19", "68" => "20", "76" => "21", "97" => "22", "63" => "23", "65" => "24", "98" => "25", "94" => "26", "86" => "27", "62" => "28", "96" => "29", "77" => "30", "75" => "31", "93" => "32", "99" => "51");

$id = $_GET['id'];
$pgid = $_GET['pg'];
if (empty ($pgid)) {
	$pgid = 1;
}

$merchant = new Merchant($id);
$merchantinfo = $merchant->MerchantInfo;

$merchantmore = $merchant->getMerchantMore($id);
$adname = $merchantinfo['adwhichmore'];
$adwords = $merchantinfo[$adname];

if (count($_SESSION['digestarray'])) {
	$digestarray = $_SESSION['digestarray'];
} else {
	$digestarray = array ();
}

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
if ($id == "899") {
	$oPage->find("KFC_Description");
	$merchantdes = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
}

if ($id == "1015") {
	$oPage->find("MC_Description");
	$merchantdes = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
}

if ($merchantinfo['ImageDownload']) {
	$merhaspic = 1;
	//$merLogPath = __IMAGE_SRC."images/merchants/".$id."/Logo.gif";
} else {
	//$merLogPath = "";
}

$tpl = new sTemplate();
$tpl->setTemplate("new/company.htm");

$tpl->assign('hotkeywords',$hotkeywords);
$tpl->assign("merhaspic", $merhaspic);
$tpl->assign("LINK_ROOT", __LINK_ROOT);

$tpl->assign("merchantURL", $merchantURL);

$spinfo = $merchant->getSpInfo($id);

if ($id == 899) {
	$tpl->assign("showaddressfilter", "1");
}

/*
if($id==899 && ($mercityid==21 || $mercityid==10)){
	$areaarray =  $merchant->getMerchantArea($id,$mercityid);
	$tpl->assign("areaid",$_GET['areaid']);
	$tpl->assign("showareafilter","1");
}
$tpl->assign("mercityid",$mercityid);
$tpl->assign("areaarray",$areaarray);
*/
//print_r($addressarray);

$oCoupon = new Coupon;

$merchantcouponid = "merchant_coupon".$id."pg".$pgid;
if ($couponList = $cache->get($merchantcouponid)) {
	$couponList = unserialize($couponList); //use cache
} else {
	$couponList = $oCoupon->getMerchantCouponList($id,$pgid);
	$re = $cache->save(serialize($couponList), $merchantcouponid);
}

$adcategoryid = 0; //google ads category
for ($i = 0; $i < count($couponList); $i ++) {
	if ($i == 0) {
		$adcategoryid = $couponList[$i]["Category_"];
	}

	$couponInfo[$i]["isFree"] = $couponList[$i]["isFree"];
	if ($couponInfo[$i]["isFree"] == 0) {
		$couponInfo[$i]["couponUrl"] = Utilities :: getURL("couponUnion", array ("Category" => $couponList[$i]["Category_"], "Coupon_" => $couponList[$i]["Coupon_"]));
		$couponInfo[$i]["printUrl"] = Utilities :: getURL("couponUnion", array ("Category" => $couponList[$i]["Category_"], "Coupon_" => $couponList[$i]["Coupon_"]));
	} else {
		$couponInfo[$i]["couponUrl"] = Utilities :: getURL("couponFree", array ("NameURL" => $couponList[$i]["NameURL"], "Coupon_" => $couponList[$i]["Coupon_"]));
		if ($couponList[$i]["ImageDownload"] == 1) {
			$printdetail = Utilities :: getURL("couponPrint", array ("Coupon_" => $couponList[$i]["Coupon_"]));
			$couponInfo[$i]["printUrl"] = "/print.php?url=".$printdetail;
		} else {
			$couponInfo[$i]["printUrl"] = Utilities :: getURL("couponFree", array ("NameURL" => $couponList[$i]["NameURL"], "Coupon_" => $couponList[$i]["Coupon_"]));
		}
	}

	$couponInfo[$i]["detailUrl"] = Utilities :: getURL("couponFree", array ("NameURL" => $couponList[$i]["NameURL"], "Coupon_" => $couponList[$i]["Coupon_"]));
	if (in_array($couponList[$i]["Coupon_"], $digestarray)) {
		$couponInfo[$i]["IsDigest"] = 1;
	} else {
		$couponInfo[$i]["IsDigest"] = 0;
	}

	if ($couponList[$i]["ImageDownload"] == "1") {
		$couponInfo[$i]["HasImage"] = 1;
		$couponInfo[$i]["ImageURL"] = __IMAGE_SRC.Utilities :: getSmallImageURL($couponList[$i]["Coupon_"]);
	} else {
		$couponInfo[$i]["HasImage"] = 0;
	}

	$couponInfo[$i]["digest"] = $couponList[$i]["digest"];
	$couponInfo[$i]["Coupon_"] = $couponList[$i]["Coupon_"];
	$couponInfo[$i]["title"] = Utilities :: cutString($couponList[$i]["Descript"], 50);

	$couponList[$i]["Detail"] = strip_tags($couponList[$i]["Detail"]);
	$couponInfo[$i]["detail"] = Utilities :: cutString($couponList[$i]["Detail"], 148);

	$couponInfo[$i]["tagname"] = Utilities :: getTagSrc($couponList[$i]["tagname"]);
	//$couponInfo[$i]["detail"] = Utilities::filterChar($couponList[$i]["Detail"]);

	$couponInfo[$i]["start"] = ($couponList[$i]["StartDate"] == "0000-00-00" ? $couponList[$i]["AddDate1"] : $couponList[$i]["StartDate"]);


	$t = explode("-",$couponInfo[$i]["start"]);
	$tomorrow = mktime(0,0,0,$t[1],$t[2],$t[0]);
	$couponInfo[$i]["start"] = date("n月j日",$tomorrow);

	if ($couponList[$i]["ExpireDate"] == "0000-00-00") {
		$couponInfo[$i]["end"] = "永久有效";
	} else {
		$couponInfo[$i]["end"] = $couponList[$i]["ExpireDate"];
	}

	$couponInfo[$i]["saveUrl"] = "/account.php?action=save&p=".$couponList[$i]["Coupon_"];
	$couponInfo[$i]["Coupon_"] = $couponList[$i]["Coupon_"];
	$couponInfo[$i]["author"] = $couponList[$i]["author"];
	$couponInfo[$i]["authorid"] = $couponList[$i]["authorid"];
	$couponInfo[$i]["NameURL"] = $couponList[$i]["NameURL"];
	$couponInfo[$i]["Category_"] = $couponList[$i]["Category_"];
	$couponInfo[$i]["cnameurl"] = $couponList[$i]["cnameurl"];
	$couponInfo[$i]["replies"] = $couponList[$i]["replies"];
	$couponInfo[$i]["Name"] = $couponList[$i]["Name"];
	$couponInfo[$i]["City"] = $couponList[$i]["City"];
	$couponInfo[$i]["isExpire"] = $couponList[$i]["isExpire"];

}

$oCategory = new Category($adcategoryid);
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for ($j = 0; $j < count($categoryList); $j ++) {
	$categoryForShow[$j]["category_url"] = Utilities :: getURL("category", array ("NameURL" => $categoryList[$j]["NameURL"], "Cid" => $categoryList[$j]["Category_"], "Page" => 1));
	$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}


//get hotcategorycouponlist
$hotcouponcacheid = "category_hotcategorycouponlist".$adcategoryid;
if ($hotcategorycouponlist = $cache->get($hotcouponcacheid)) {
	$hotcategorycouponlist = unserialize($hotcategorycouponlist); //use cache
} else {
	$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($adcategoryid);
	$re = $cache->save(serialize($hotcategorycouponlist), $hotcouponcacheid);
}


$merchantcouponcount = $oCoupon->getMerchantCouponCountList($id);
$pageCount = ceil($merchantcouponcount / 10);
$pageString = $oCategory->getMerchantNewPageStr($pgid, $pageCount, $merchantinfo['NameURL'], $id);

$metaTitle = $merchant->getMeta('MetaTitle');
$metaDescription = $merchant->getMeta('MetaDescription');
$metaKeywords = $merchant->getMeta('MetaKeywords');
//echo $metaDescription;

//add by menny June 16,2008 func-> get category top merchants
$mer_cid = $adcategoryid ? $adcategoryid : 95; //商家的第一个产品的categroyid 做为获取推荐商家ID
$merchantlistcacheid = "category_topcategorymerchantlist".$mer_cid;
if ($categoryMerchantList = $cache->get($merchantlistcacheid)) {
	$categoryMerchantList = unserialize($categoryMerchantList); //use cache
} else {
	//$oMerchant = new Merchant();
    $categoryMerchantList = $merchant->getCategoryMerchantList($mer_cid);
	$re = $cache->save(serialize($categoryMerchantList), $merchantlistcacheid);
}
$tpl->assign("categoryMerchantList", $categoryMerchantList);  //栏目上商家列表

$tpl->assign("couponlist", $couponInfo);

$tpl->assign("merchantName", $merchantinfo['Name']);
$tpl->assign("pageString", $pageString);
$tpl->assign("start", $start);

$tpl->assign("name1", $merchantinfo['name1']);

if ($merchantinfo['Descript'] == "") {

	$tpl->assign("merchantDescript", $merchantinfo['DescriptMore']);
} else {
	$tpl->assign("merchantDescript", $merchantinfo['Descript']);
}
if (strlen($merchantinfo['URL']) > 0) {
    $merchantURL = Tracking_Uri::build(array(
        Tracking_Uri::BUILD_TYPE    => 'coupon',
        Tracking_Uri::MERCHANT_ID   => $id,
    ));
} else {
	$merchantURL = "";
}

$url = empty ($_GET['url']) ? ($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']) : $_GET['url'];
$tpl->assign('url', $url);

$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if (empty ($winduser)) {
	$tpl->assign('islogon', 0);
} else {
	$user = explode("\t", $winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo", $userinfo[0]);
	$tpl->assign('islogon', 1);
}

$cid = $newsinfo[$adcategoryid];
if ($cid == 95) {
	$tpl->assign("hashotbbs", '1');
	$tpl->assign("hashotinfo", '1');
} else {
	if ($r = $cache->get('category_hotbbs')) {
		$r = unserialize($r); //use cache
	} else {
		$hotallbbs = $phpwind->gethotrealbbs();
		$re = $cache->save(serialize($hotallbbs), 'category_hotbbs');
	}
	$tpl->assign("hotbbs", $r);

	/*$infoid = "category_hotinfo".$cid;
	if ($r = $cache->get($infoid)) {
		$r = unserialize($r); //use cache
	} else {
		$r = $phpwind->gethotbbs($cid);
		$re = $cache->save(serialize($r), $infoid);
	}
	$tpl->assign("hotinfo", $r);
	*/
	$tpl->assign("hashotinfo", '1');
}

$indexlist = $phpwind->getBBSNotify(50);
$tpl->assign("indexlist", $indexlist);

$tpl->assign("merchantdes", $merchantdes);
$tpl->assign("hotcouponin", $hotcouponin);
$tpl->assign("title", $metaTitle);

$tpl->assign("description", $metaDescription);
$tpl->assign("hotmerchantin", $hotmerchantin);
$tpl->assign("keywords", $metaKeywords);

$tpl->assign("spinfo", $spinfo);
$tpl->assign("merchantmore", $merchantmore);
$tpl->assign("merchantURL", $merchantURL);
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("allhotcategorycouponlist", $hotcategorycouponlist);

$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

//add google ads by thomas 07/15/09
//$params = array();
//$adsWords = new AdWordsDao($merchantinfo['Name'], 8);
//$adsResult = $adsWords -> dispatch($params);

$splitCountArr = array(3, 5);
$baiduParams = array('splitCountArr' => array(-3, -5));
$adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $merchantinfo['Name'], "IsHighlight" => true);
$adsResult = AdWordsDao::getAdsScript($adsParams, $baiduParams);

$tpl->assign("adsResult", $adsResult);

$tpl->assign('nowcityname', $cityarray['cityname']);
$tpl->assign('citylist', $citylist);
$tpl->assign('__IMAGE_SRC', __IMAGE_SRC);

$tpl->displayTemplate();
?>

