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
require_once(__INCLUDE_ROOT."lib/classes/class.Customer.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');


$winduser = P_GetCookie("winduser");
$phpwind = new PHPWIND();
if(empty($winduser)){
	Header("Location:/bbs/login.php");
	exit;
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	//$tpl->assign("userinfo",$userinfo[0]);
	//$tpl->assign('islogon',1);
}


$oCustomer = new Customer();
//if($p) {
//	$addC = "?p=$p";
//}

//$ui = $oCustomer->getid($uid);
//if ( $action == "remove" ){
//   $oCustomer->delCoupon($p,$ui);
//}
if ( $_GET['action'] == "save" ){
	$oCustomer->addCoupon($_GET['p'],$user[0]);
	$oCoupon = new Coupon($_GET['p']);
	$couponRow = $oCoupon->getCouponInfo();

	if($couponRow['CouponType']==9){
		Header("Location:/profile/mydiscount.php?switch=myfavordiscount");
		exit;
	}else{
		Header("Location:/profile/mycoupon.php?switch=myfavorcoupon");
		exit;
	}
}

$tpl = new sTemplate();

$saved_cnt = $oCustomer->hasSaved($ui);
if ( $saved_cnt[1] == 0 ){
   $tpl->assign("COUPON_COUNT", 0);
   $tpl->assign("nothing_yet1", 1);
} else {
   $couponlist = $oCustomer->loadCoupons($ui);
   $merchant_name = "";
   for($i=0; $i<count($couponlist); $i++) {
   	    if(trim($couponlist[$i]["Name"]) == "") {
			$couponFinal[$i]["merchantName"] = "其他商家";
		} else {
			$couponFinal[$i]["merchantName"] = $couponlist[$i]["Name"];
		}
		if($couponlist[$i]["MerIsShow"] == "1") {
			$couponFinal[$i]["merchantURL"] = Utilities::getURL("merchant", array("NameURL" => $couponlist[$i]["NameURL"]));
		} else {
			$couponFinal[$i]["merchantURL"] = "";
		}
		$couponFinal[$i]["Amount"] = $couponlist[$i]["Amount"];
		if($couponlist[$i]["isFree"] == 0) {
			$couponFinal[$i]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponlist[$i]["Merchant_"],
											"Coupon_" => $couponlist[$i]["Coupon_"]));
		} else {
			$couponFinal[$i]["couponURL"] = Utilities::getURL("couponFree", array("NameURL" => $couponlist[$i]["NameURL"],
											"Coupon_" => $couponlist[$i]["Coupon_"]));
		}

		$couponFinal[$i]["couponTitle"] = Utilities::cutString($couponlist[$i]["Descript"],40);
		$couponFinal[$i]["status"] = Utilities::getCouponStatus($couponlist[$i]["ExpireDate"]);
   }
   $tpl->assign("COUPON_COUNT", count($couponlist));
   $tpl->assign("couponList", $couponFinal);
}

//$em = base64_decode($_COOKIE['DIU']);
$tpl->setTemplate("my_account_content.tpl");

if ( $action == "save" ){
   $oCoupon = new Coupon($p);
   $tpl->assign("INTRODUCTION", " <b>".$oCoupon->get("MerchantName")." </b> 已经保存到您的收藏夹。");
}else if ( $saved_cnt[0] == 0 && $saved_cnt[1] == 0 ){
   $tpl->assign("INTRODUCTION", "感谢您在大红包注册。");

}
else{
   $tpl->assign("INTRODUCTION", "");
}
//$LOGGEDIN = (strlen(base64_decode($HTTP_COOKIE_VARS["DIU"])) > 0 ) ? "已登录用户：".
//            (base64_decode($HTTP_COOKIE_VARS["DIU"])).".&nbsp;<a href=\"".__LINK_ROOT.
//			"register.php?action=log\" class=\"loggedIn\">更改用户</a>" : "&nbsp;"



//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

//Meta
//$oPage->find("register");
//$metaTitle = $oPage->getMeta("MetaTitle");
//$metaDescription = $oPage->getMeta("MetaDescription");
//$metaKeywords = $oPage->getMeta("MetaKeywors");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Name" => $categoryList[$j]["NameURL"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", '大红包优惠折扣券－我的帐户');
$tpl->assign("description", '');
$tpl->assign("keywords", '');
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array("我的账户" => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);

$tpl->displayTemplate();
?>