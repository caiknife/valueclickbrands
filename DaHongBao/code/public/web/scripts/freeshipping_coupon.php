<?php
set_time_limit(3600);
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Date.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

$tpl = new sTemplate();
$tpl->setTemplate("freeshipping_coupon.tpl");

//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

//Meta
$oPage->find("freeshipping_coupon");
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
$couponListTmp = $oCoupon->getFreeShippingCouponList();
$lastMerName = "";
for($s=0; $s<count($couponListTmp); $s++) {
	if($couponListTmp[$s]["Merchant_"] == 0) {
	   $couponListTmp[$s]["Name"] = "其他商家";
   }
	if($couponListTmp[$s]["isShow"] == 1 ) {
   		$newCouponFinal[$s]["merchantURL"] = Utilities::getURL("merchant", array("NameURL" => $couponListTmp[$s]["NameURL"]));
	} else {
		$newCouponFinal[$s]["merchantURL"] = "";
	}
    if($lastMerName == $couponListTmp[$s]["Name"]) {
		$newCouponFinal[$s]["merchantName"] = "";
	} else {
		$newCouponFinal[$s]["merchantName"] = $couponListTmp[$s]["Name"];
	}
   $newCouponFinal[$s]["Amount"] = $couponListTmp[$s]["Amount"];
   $newCouponFinal[$s]["couponTitle"] = Utilities::cutString($couponListTmp[$s]["Descript"],40);
   $newCouponFinal[$s]["status"] = Utilities::getCouponStatus($couponListTmp[$s]["ExpireDate"]);
   if($couponListTmp[$s]["isFree"] == 0) {

		$newCouponFinal[$s]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponListTmp[$s]["Merchant_"],
									"Coupon_" => $couponListTmp[$s]["Coupon_"]));
   } else {
		$newCouponFinal[$s]["couponURL"] = Utilities::getURL("couponFree", array("NameURL" => $couponListTmp[$s]["NameURL"],
									"Coupon_" => $couponListTmp[$s]["Coupon_"]));
   }
   $lastMerName = $couponListTmp[$s]["Name"];
   if($couponListTmp[$s]["Merchant_"] == 0) {
	   $newCouponFinal[$s]["merchantURL"] = "";
   }
   $newCouponFinal[$s]["City"] = Utilities::getCity($couponListTmp[$s]["City"]);
}



$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array("免费送货" => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("couponFreeList", $newCouponFinal);
$tpl->displayTemplate();

?>
