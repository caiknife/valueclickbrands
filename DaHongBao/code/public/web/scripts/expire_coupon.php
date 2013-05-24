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
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$tpl = new sTemplate();
$tpl->setTemplate("new/expire_coupon.tpl");

//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

//Meta
$oPage->find("expire_coupon");
$metaTitle = $oPage->getMeta("MetaTitle");
$metaDescription = $oPage->getMeta("MetaDescription");
$metaKeywords = $oPage->getMeta("MetaKeywors");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");

$newsestcoupon = $oCategory->getnewcategorycouponlist();


for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}
$oCoupon = new Coupon();
for ( $i=0; $i < 3; $i++ ){
   $couponListTmp = $oCoupon->getExpireCouponList($i);
   $lastMerName = "";
   for($s=0; $s<count($couponListTmp) && $s < 20; $s++) {
   	   if($couponListTmp[$s]["Merchant_"] == 0) {
	   	   $couponListTmp[$s]["Name"] = "其他商家";
	   }
   	   if($couponListTmp[$s]["isShow"] == 1 ) {
   	   	   $newCouponFinal[$i][$s]["merchantURL"] = Utilities::getURL("merchant",
		   				array("NameURL" => $couponListTmp[$s]["NameURL"]));
	   } else {
	   	   $newCouponFinal[$i][$s]["merchantURL"] = "";
	   }
	   if($lastMerName == $couponListTmp[$s]["Name"]) {
	   	   $newCouponFinal[$i][$s]["merchantName"] = "";
		   $newCouponFinal[$i][$s]["merchantURL"] = "";
	   } else {
	   	   $newCouponFinal[$i][$s]["merchantName"] = $couponListTmp[$s]["Name"];
	   }
	   $newCouponFinal[$i][$s]["Amount"] = $couponListTmp[$s]["Amount"];
	   $newCouponFinal[$i][$s]["couponTitle"] = Utilities::cutString($couponListTmp[$s]["Descript"],40);
	   $newCouponFinal[$i][$s]["status"] = Utilities::getCouponStatus($couponListTmp[$s]["ExpireDate"]);
	   if($couponListTmp[$s]["isFree"] == 0) {

	   		$newCouponFinal[$i][$s]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponListTmp[$s]["Merchant_"],
				                        "Coupon_" => $couponListTmp[$s]["Coupon_"]));
	   } else {
	   		$newCouponFinal[$i][$s]["couponURL"] = Utilities::getURL("couponFree", array("NameURL" => $couponListTmp[$s]["NameURL"],
				                        "Coupon_" => $couponListTmp[$s]["Coupon_"]));
	   }
	   $lastMerName = $couponListTmp[$s]["Name"];
	   if($couponListTmp[$s]["Merchant_"] == 0) {
	   	   $newCouponFinal[$i][$s]["merchantURL"] = "";
	   }
	   $newCouponFinal[$i][$s]["City"] = Utilities::getCity($couponListTmp[$s]["City"]);
   }

}

$winduser = P_GetCookie("winduser");
$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);

$phpwind = new PHPWIND();
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	//echo $winduser;
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}
$r = $phpwind->gethotallbbs("17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,51");
$tpl->assign("hotinfo", $r);


$r = $phpwind->gethotrealbbs();
$tpl->assign("hotbbs", $r);


//$tpl->assign("hotbbs", $r);
$indexlist = $phpwind->getforumlist("50");
$tpl->assign("indexlist", $indexlist);

$tpl->assign("newsestcoupon", $newsestcoupon);
$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array("快过期的优惠购物券" => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("couponTodayList", $newCouponFinal[0]);
$tpl->assign("couponTomorrowList", $newCouponFinal[1]);
$tpl->assign("couponAfterTomorrowList", $newCouponFinal[2]);
//$tpl->assign("startDay1", $adate[1]);
//$tpl->assign("startDay2", $adate[2]);
//$tpl->assign("startDay3", $adate[3]);

$tpl->displayTemplate();

?>