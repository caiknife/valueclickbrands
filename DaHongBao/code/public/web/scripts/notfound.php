<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");

header("HTTP/1.0 404 Not Found");


$tpl = new sTemplate();
$tpl->setTemplate("new/notfound.tpl");


$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");

//print_r($categoryList);
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$phpwind = new PHPWIND();
$a = $phpwind->getforumlist("50");

$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$tpl->assign("category", $categoryForShow);

$tpl->assign('indexlist',$a);
//$oMeta = new Meta();
//$oMeta->find('ItemType','NotFound');
//$metaTitle = $oMeta->get("MetaTitle");
//$metaDescription = $oMeta->get("MetaDescription");
//$metaKeywords = $oMeta->get("MetaKeywords");
$metaTitle = "页面或优惠信息暂时不存在 – 大红包";
$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
//$tpl->assign("description", $metaDescription);
//$tpl->assign("keywords", $metaKeywords);
$tpl->displayTemplate();
?>