<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

$tpl = new sTemplate();
$tpl->setTemplate("exchange.tpl");

$oPage = new Page();

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

//Meta
//$oMeta = new Meta();
//$oMeta->find('ItemType',"Coupon");
$metaTitle = "合作伙伴 - 大红包";
//$metaDescription = $oMeta->get("MetaDescription");
//$metaKeywords = $oMeta->get("MetaKeywords");


$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

//coupon info
$oCoupon = new Coupon();
//newest coupon online
$couponList = $oCoupon->getNewCouponListForCoupon();
for ( $i=0; $i < count($couponList) && $i < 10; $i++ ){
	$newCouponFinal[$i]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponList[$i]["Merchant_"],
				                        "Coupon_" => $couponList[$i]["Coupon_"]));
	$newCouponFinal[$i]["couponTitle"] = $couponList[$i]["Descript"];
}

$oPage = new Page();
$oPage->find("EXCHANGE_INCLUDE");
$exchange_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("EXCHANGE_INCLUDE", $exchange_include);
$tpl->assign("newCouponlist", $newCouponFinal);
$tpl->displayTemplate();
?>
