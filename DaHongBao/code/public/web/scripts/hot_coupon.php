<?php
set_time_limit(3600);
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

//此程序已废弃(Fan, 2008-02-01)
redirect301("/");
exit;

$tpl = new sTemplate();
$tpl->setTemplate("hot_coupon.tpl");

//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

//Meta
$oPage->find("hot_coupon");
$metaTitle = $oPage->getMeta("MetaTitle");
$metaDescription = $oPage->getMeta("MetaDescription");
$metaKeywords = $oPage->getMeta("MetaKeywors");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category", 
	array("NameURL" => $categoryList[$j]["NameURL"],"Name" => $categoryList[$j]["NameURL"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}
$oCoupon = new Coupon();
$hotcoupon = $oCoupon->getHotCouponList();
$merchant_name = array();

$s = 0;
for($i=0;$i<count($hotcoupon); $i++) {
	if(strlen($hotcoupon[$i]["Name"]) < 1)  continue;
	if(in_array($hotcoupon[$i]["Name"],$merchant_name)) continue;
	$merchant_name[] = $hotcoupon[$i]["Name"];
	$oCoupon->set("Coupon_", $hotcoupon[$i]["Coupon_"]);
//	$oCoupon->loadRunk();
//	$hotCouponFinal[$s]["yesterday"] = $oCoupon->get("Runk2") > 0 ? $oCoupon->get("Runk2") : "未定";
//	$hotCouponFinal[$s]["today"] = $s;
	if($hotcoupon[$i]["isShow"] == 1) {
		$hotCouponFinal[$s]["merchantURL"] = Utilities::getURL("merchant", array("NameURL" => $hotcoupon[$i]["NameURL"]));
	} else {
		$hotCouponFinal[$s]["merchantURL"] = "";
	} 
	$hotCouponFinal[$s]["merchantName"] = $hotcoupon[$i]["Name"];
//	$hotCouponFinal[$s]["Amount"] = $hotcoupon[$i]["Amount"];
	if($hotcoupon[$i]["isFree"] == 0) {
		$hotCouponFinal[$s]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $hotcoupon[$i]["Merchant_"],
				                        "Coupon_" => $hotcoupon[$i]["Coupon_"]));
	} else {
		$hotCouponFinal[$s]["couponURL"] = Utilities::getURL("couponFree", array("NameURL" => $hotcoupon[$i]["NameURL"],
				                        "Coupon_" => $hotcoupon[$i]["Coupon_"]));
	} 
	$hotCouponFinal[$s]["couponTitle"] = Utilities::cutString($hotcoupon[$i]["Descript"],40);
	$hotCouponFinal[$s]["status"] = Utilities::getCouponStatus($hotcoupon[$i]["ExpireDate"]);
	$hotCouponFinal[$s]["City"] = Utilities::getCity($hotcoupon[$i]["City"]);
	//$hotCouponFinal[$s]["direction"] = ($oCoupon->get("Runk2")<=0 || $i==$oCoupon->get("Runk2")) ? "--" : ($oCoupon->get("Runk2")<$i ? "<img src=\"".__LINK_ROOT."images/runk_down.gif\">" : "<img src=\"".__LINK_ROOT."images/runk_up.gif\">");
	if($s++ >= 29) break;;
}

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array("最热门的折扣优惠券" => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("couponList", $hotCouponFinal);


$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21
$cityname = $phpwind->getNowCityName($cityid);  //get city name by city id
$tpl->assign('nowcityname',$cityname);
$citylist = $phpwind->getCityList();   //city list for city select
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();
?>