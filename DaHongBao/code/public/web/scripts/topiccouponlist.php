<?php
require_once ("../etc/const.inc.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once (__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Template.php");
include_once (__INCLUDE_ROOT."lib/functions/func.Debug.php");
include_once (__INCLUDE_ROOT."lib/classes/class.Search.php");
include_once (__INCLUDE_ROOT."lib/classes/class.Coupon.php");
include_once (__INCLUDE_ROOT."lib/classes/class.topic.php");
include_once (__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Category.php");

require_once (__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$winduser = P_GetCookie("winduser");

$topiccouponid = $_GET['id'];

$topic = new Topic();
$phpwind = new PHPWIND();
$topiclist = $topic->getTopicContentList($topiccouponid);

$couponid = $topiclist['couponid'];

$topicrow = $topic->getTopicRow($topiclist['topicid']);

$oCategory = new Category();

$allCategoryCoupon = $oCategory->getTopicCouponList($couponid);

if (count($_SESSION['digestarray'])) {
	$digestarray = $_SESSION['digestarray'];
} else {
	$digestarray = array ();
}

$oPage = new Page();
$yqlj = "YQLJ_".$_GET['cid'];
$oPage->find($yqlj);
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oCoupon = new Coupon;

$oCategory = new Category($_GET["cid"]);
$categoryList = $oCategory->getCategoryList("SitemapPriority");

$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($_GET["cid"]);

//print_r($categoryList);
for ($j = 0; $j < count($categoryList); $j ++) {
	$categoryForShow[$j]["category_url"] = Utilities :: getURL("category", array ("NameURL" => $categoryList[$j]["NameURL"], "Cid" => $categoryList[$j]["Category_"], "Page" => 1));
	$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

/////////////////////////////****地区
$ericarray = $allCategoryCoupon;
//print_r($ericarray);
for ($j = 0; $j < count($ericarray); $j ++) {
	if (Utilities :: getCity($ericarray[$j]['City']) != "") {
		$cityarray[$ericarray[$j]['CityID']] = Utilities :: getCity($ericarray[$j]['City']);
		//$cityarray[$ericarray[$j]['CityID']]['num'] = $cityarray[$ericarray[$j]['CityID']]['num']+1;
	}
}
$cityarray = array_unique($cityarray);
////////////////////////***********

$merchant_name = "";
$allMerchantInfo = null;
for ($k = 0; $k < 10; $k ++) {
	$current = $k;
	if (isset ($allCategoryCoupon[$current])) {
		if ((strlen($allCategoryCoupon[$current]["Name"]) > 0) == false) {
			continue;
		}

		$infoTmp["detailUrl"] = Utilities :: getURL("couponFree", array ("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));

		if ($allCategoryCoupon[$current]["isFree"] == 0) {
			$infoTmp["couponUrl"] = Utilities :: getURL("couponUnion", array ("Category" => $allCategoryCoupon[$current]["Category_"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
			$infoTmp["printUrl"] = Utilities :: getURL("couponUnion", array ("Category" => $allCategoryCoupon[$current]["Category_"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
		} else {

			$infoTmp["couponUrl"] = Utilities :: getURL("couponFree", array ("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
			if ($allCategoryCoupon[$current]["ImageDownload"] == 1) {
				$printdetail = Utilities :: getURL("couponPrint", array ("Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
				$infoTmp["printUrl"] = "/print.php?url=".$printdetail;
			} else {
				$infoTmp["printUrl"] = Utilities :: getURL("couponFree", array ("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
			}
		}


		if ($allCategoryCoupon[$current]["Name"] == $merchant_name) {
			$infoTmp["merName"] = "";
		} else {
			$infoTmp["merName"] = $allCategoryCoupon[$current]["Name"];
		}
		$merchant_name = $allCategoryCoupon[$current]["Name"];
		if (($allCategoryCoupon[$current]["isFree"] == 0)) {
			$infoTmp["isFree"] = 0;
		} else {
			$infoTmp["isFree"] = 1;
		}
		$infoTmp["couponTitle"] = Utilities :: cutString($allCategoryCoupon[$current]["Descript"], 40);

		$infoTmp["NameURL"] = $allCategoryCoupon[$current]["NameURL"];
		$infoTmp["Merchant_"] = $allCategoryCoupon[$current]["Merchant_"];

		$infoTmp["categoryname"] = $allCategoryCoupon[$current]["categoryname"];
		$infoTmp["Category_"] = $allCategoryCoupon[$current]["Category_"];
		$infoTmp["categorynameurl"] = $allCategoryCoupon[$current]["categorynameurl"];


		if ($allCategoryCoupon[$current]["ExpireDate"] == "0000-00-00") {
			$infoTmp["couponStatus"] = "永久有效";
		} else {
			$infoTmp["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
		}

		if (in_array($allCategoryCoupon[$current]["Coupon_"], $digestarray)) {
			$infoTmp["IsDigest"] = 1;
		} else {
			$infoTmp["IsDigest"] = 0;
		}
		$infoTmp["Coupon_"] = $allCategoryCoupon[$current]["Coupon_"];
		$infoTmp["name1"] = $allCategoryCoupon[$current]["name1"];
		$infoTmp["replies"] = $allCategoryCoupon[$current]["replies"];
		$infoTmp["City"] = Utilities :: getCity($allCategoryCoupon[$current]["City"]);
		$infoTmp["Hasmap"] = $allCategoryCoupon[$current]["Hasmap"];
		$infoTmp["digest"] = $allCategoryCoupon[$current]["digest"];
		$infoTmp["CouponType"] = $allCategoryCoupon[$current]["CouponType"];
		//echo $allCategoryCoupon[$current]["author"];
		$infoTmp["author"] = $allCategoryCoupon[$current]["author"];

		$infoTmp["isExpire"] = $allCategoryCoupon[$current]["isExpire"];

		$infoTmp["authorid"] = $allCategoryCoupon[$current]["authorid"];
		$infoTmp["StartDate"] = $allCategoryCoupon[$current]["StartDate"];

		$t = explode("-",$infoTmp["StartDate"]);
		$tomorrow = mktime(0,0,0,$t[1],$t[2],$t[0]);
		$infoTmp["StartDate"] = date("n月j日",$tomorrow);
		//echo $infoTmp["author"];
		$infoTmp["Detail"] = Utilities :: cutString($allCategoryCoupon[$current]["Detail"], 148);
		$infoTmp["ImageURL"] = Utilities :: getSmallImageURL($allCategoryCoupon[$current]["Coupon_"]);

		$filename = __INCLUDE_ROOT.$infoTmp["ImageURL"];
		//echo $filename;

		if ($allCategoryCoupon[$current]["ImageDownload"] == "1") {
			$infoTmp["HasImage"] = 1;
			$infoTmp["ImageURL"] = __IMAGE_SRC.Utilities :: getSmallImageURL($allCategoryCoupon[$current]["Coupon_"]);
		} else {
			$infoTmp["HasImage"] = 0;
		}
		//echo $infoTmp["ImageURL"]."<BR>";
		$allMerchantInfo[] = $infoTmp;
	} else {
		break;
	}
}
$merName = "";
for ($n = 0; $n < count($allMerchantInfo); $n ++) {
	if ($allMerchantInfo[$n]["merName"] != $merName && $allMerchantInfo[$n]["merName"] != "") {
		$merName = $allMerchantInfo[$n]["merName"];
	}
	if ($allMerchantInfo[$n]["couponUrl"] != "" || $allMerchantInfo[$n]["merUrl"] != "") {
		$merNameArr[$allMerchantInfo[$n]["merName"]] = 1;
	}
}
for ($n = 0; $n < count($allMerchantInfo); $n ++) {
	if ($allMerchantInfo[$n]["merName"] != $merName && $allMerchantInfo[$n]["merName"] != "") {
		$merName = $allMerchantInfo[$n]["merName"];
	}
	if ($merNameArr[$merName] == 1) {
		$allMerchantInfo[$n]["isAbled"] = 1;
	} else {
		$allMerchantInfo[$n]["isAbled"] = 0;
	}
}

$tpl = new sTemplate();
$tpl->setTemplate("new/topiccouponlist.htm");

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("category", $categoryForShow);

$tpl->assign('hotkeywords',$hotkeywords);
$tpl->assign("couponList", $allMerchantInfo);

$tpl->assign("pageString", $pageString);
$tpl->assign("cityarray", $cityarray);
$tpl->assign("nowcityid", $cityid);
$tpl->assign("title", $metaTitle);

$tpl->assign("allhotcategorycouponlist", $hotcategorycouponlist);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("navigation_path", getNavigation(array ($oCategory->get("Name")."电子优惠券、购物折扣券" => "")));
$tpl->assign("categoryName", $oCategory->get("Name"));
$tpl->assign("NameURL", $oCategory->get("NameURL"));

$tpl->assign("cityname", $cityarray[$_COOKIE['cityid']]);

$oPage->find("HOTCOUPON_INCLUDE_IN");
$hotcouponother = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign("hotcouponother", $hotcouponother);

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

$url = empty ($_GET['url']) ? ($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']) : $_GET['url'];
$tpl->assign('url', $url);

$cid = $_GET['cid'];
if ($cid == 95) {
	$tpl->assign("hashotbbs", '1');
	$tpl->assign("hashotinfo", '1');
} else {
	$r = $phpwind->gethotbbs($newsbbs[$cid]);
	$tpl->assign("hotbbs", $r);

	$r = $phpwind->gethotbbs($newsinfo[$cid]);
	//print_r($r);
	$tpl->assign("hotinfo", $r);
}

$indexlist = $phpwind->getforumlist("50");
$tpl->assign("indexlist", $indexlist);
$tpl->assign("topicrow", $topicrow);

$adlist = $phpwind->getad($_GET['cid']);
$tpl->assign("adlist", $adlist);

//$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21
$cityname = $phpwind->getNowCityName($cityid); //get city name by city id
$tpl->assign('nowcityname', $cityname);
$citylist = $phpwind->getCityList(); //city list for city select
$tpl->assign('citylist', $citylist);

$tpl->displayTemplate();
?>