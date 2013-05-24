<?php

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
include_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Search.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
include_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$winduser = P_GetCookie("winduser");



$oPage = new Page();

$oPage->find("HOTCOUPON_INCLUDE_IN");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$newcategorycouponlist = $oCategory->getnewcategorycouponlist();



$tpl = new sTemplate();
$tpl->setTemplate("new/refer.htm");




$phpwind = new PHPWIND();
$a = $phpwind->getforumlist("17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,50,51");

$r = $phpwind->gethotrealbbs();
$tpl->assign("hotbbs", $r);

$r = $phpwind->gethotallbbs("17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,51");
$tpl->assign("hotinfo", $r);
//print_r($a);

$adlist = $phpwind->getad("news.html");

$tpl->assign('hotkeywords',$hotkeywords);



$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);

if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}
$tpl->assign('adlist',$adlist);
//print_r($adlist);

$tpl->assign('indexlist',$a);
$tpl->assign("category", $categoryForShow);
$tpl->assign("newcategorycouponlist", $newcategorycouponlist);

//print_r($newcategorycouponlist);
$tpl->assign("hotcoupon_include",$hotcoupon_include);

$tpl->assign("forumreview",$b);

$tpl->assign('__IMAGE_SRC',__IMAGE_SRC);

//$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21
$cityname = $phpwind->getNowCityName($cityid);  //get city name by city id
$tpl->assign('nowcityname',$cityname);
$citylist = $phpwind->getCityList();   //city list for city select
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();

?>