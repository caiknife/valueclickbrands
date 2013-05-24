<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$tpl = new sTemplate();
$tpl->setTemplate("new/exchange.htm");

//设置显示的重要线下商家
$famousMerArr = array(898,899,1015);

//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("YQLJ_INDEX1");
$yqlj1 = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$oPage->find("YQLJ_INDEX2");
$yqlj2 = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$oPage->find("YQLJ_INDEX3");
$yqlj3 = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

//Meta
$oPage->find("all_merchant");
$metaTitle = $oPage->getMeta("MetaTitle");
$metaDescription = $oPage->getMeta("MetaDescription");
$metaKeywords = $oPage->getMeta("MetaKeywors");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
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

//$tpl->assign("hotbbs", $r);
$indexlist = $phpwind->getforumlist("50");
$tpl->assign("indexlist", $indexlist);

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign('yqlj1',$yqlj1);
$tpl->assign('yqlj2',$yqlj2);
$tpl->assign('yqlj3',$yqlj3);
$tpl->assign("navigation_path", getNavigation(array("所有的认证商家" => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("MerchantList", $MerchantArr);
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);
$tpl->displayTemplate();

?>