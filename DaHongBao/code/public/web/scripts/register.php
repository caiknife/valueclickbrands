<?php
set_time_limit(3600);
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Customer.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

if($p) {
	$addC = "&p=$p";
}
$tpl = new sTemplate();
if($_POST['action']=='register'){
	 $oCustomer = new Customer();
      //$oCustomer->find($txEmail,0);
      $oCustomer->set("Forum","No Forum");
      $oCustomer->set("Email",$txEmail);
      $oCustomer->set("FName",$txFirst);
      $oCustomer->set("LName",$txLast);
      $oCustomer->set("Password",strlen($txPass) > 0 ? $txPass : $oCustomer->get("Password"));
      $oCustomer->set("Email",strlen($oCustomer->get("Email")) > 0 ? $oCustomer->get("Email") : $txEmail);
      $oCustomer->set("FreqLetter",(integer)$FreqMail);
      $oCustomer->set("LastLetter","0000-00-00");

      $res = $oCustomer->insert();
	  if(!$res) {
	  	  echo("<"."script language=javascript>");
		  echo("alert('您填写的邮箱已被注册，请使用其他邮箱，点击确定后返回!');");
		  echo("history.back(-1);");
		  echo("<"."/script>");
		  exit;
	  }
      setcookie("DIU",base64_encode($txEmail),time()+999999999,"/",".".COOKIE_HOSTNAME);
	  if($p) {
	  	redirect301(__LINK_ROOT."account.php?action=save&p=$p");
      } else {
		redirect301(__LINK_ROOT."account.php?empty=yes");
	  }
	  exit;

}
if($_POST['action']=='login'){
	$oCustomer = new Customer();
	$a = $oCustomer->checklogin($_POST['txEmail'],$_POST['txPass']);
	if($a){
		setcookie("DIU",base64_encode($_POST['txEmail']),time()+999999999,"/",".".COOKIE_HOSTNAME);
		if($_GET['p']) {
			redirect301(__LINK_ROOT."account.php?action=save&p=$p");
		} else {
			redirect301(__LINK_ROOT."account.php?empty=yes");
		}
        exit;
	}else{
		redirect301(__LINK_ROOT."register.php?action=relogin".$addC);
		exit();
	}
}
if($_GET['action']=='log'){
	setcookie("DIU","",0,"/",".".COOKIE_HOSTNAME);
}

if($_GET['action']=='new'){
	$tpl->setTemplate("register_new.tpl");
	$metaTitle = "注册新帐号";
}else{
	setcookie("DIU","0",0,"/",".".COOKIE_HOSTNAME);
	$tpl->setTemplate("register.tpl");
	$metaTitle = "登录－大红包购物商城";
}
if($_GET['action']=='relogin'){
	$tpl->assign("relogin", 1);
}

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
if($p) {
	$tpl->assign("addCouponID", $p);
}

$tpl->displayTemplate();
?>