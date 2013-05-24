<?php
    require_once("../etc/const.inc.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Customer.php");

if ( strlen($HTTP_COOKIE_VARS["DIU"]) == 0 ){
   header("Location: ".__LINK_ROOT."register.php");
}
$oCustomer = new Customer();
$oCustomer->find(base64_decode($HTTP_COOKIE_VARS["DIU"]));
if ( !($oCustomer->ID > 0) ){
   header("Location: ".__LINK_ROOT."register.php");
}
if ( $action == "save" ){
   $oCustomer->find(base64_decode($HTTP_COOKIE_VARS["DIU"]),1);
   $Merchants = split(';',$mymerchantlist);
   if ( is_array($Merchants) ){
      $oMerchantListTop = new TopMerchantList();
      $Merchants = array_diff($Merchants,$oMerchantListTop->getArray());
      if ( is_array($TopMerchnat) ){
         $Merchants = array_unique(array_merge($Merchants,$TopMerchnat));
      }
   }
   else{
      $Merchants = $TopMerchnat;
   }
   $oCustomer->saveMerchants($Merchants);
   $oCustomer->set("FreqLetter",(integer)$howOften);
   $oCustomer->set("AlertEmail",(integer)$alertExpire);
   $oCustomer->rec2param();
   $oCustomer->update();
   if ( $weeklyNews == 1 ){
      $oCustomer->addnewsletter();
   }
   else{
      $oCustomer->delnewsletter();
   }
   header("Location: ".__LINK_ROOT."account.php");
   exit;
}
$oCustomer->loadMerchants();

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$tpl->define(array(
   'main'      => get_browser_template("cache/main_account.tpl"),
   'include'   => get_browser_template("notify_me.tpl")
));

$tpl->assign(array(
	'TEXT_TOP_DESC'		=> '商家',
	'TEXT_BOTTOM_DESC'	=> "商品",
   'BASE_HOSTNAME'   => BASE_HOSTNAME,
   'LINK_ROOT'       => __LINK_ROOT,
   'PAGE_TITLE'      => "通知我",
   'NAVIGATION_PATH' => getNavigation(array("Notify me" => "")),
   'PROMO_HEAD'      => __DEFAULT_PROMO_HEAD,
   'PROMO_TEXT'      => __DEFAULT_PROMO_TEXT,
   'PROMO_FOOTER_1'  => __DEFAULT_PROMO_FOOTER_1,
   'PROMO_FOOTER_2'  => __DEFAULT_PROMO_FOOTER_2,
   'PROMO_FOOTER_3'  => __DEFAULT_PROMO_FOOTER_3,
   'PROMO_URL'       => __DEFAULT_PROMO_URL,
   'EMAIL'           => $oCustomer->get("Email"),
   'SCRIPT_NAME'     => $PHP_SELF,
   'CATEGORY_CUR'    => -1,
   'MERCHANT_CUR'    => -1,
   'COUPON_CUR'      => -1,
   'INTRODUCTION'    => "在这个页面的设置，会让您通过设置，自动获得最新的优惠信息。<br>以下是具体设置：",
   'KEYWORDS'        => "",
   'DESCRIPTION_H'   => "",
   'SE_WORDS'        => "",
   'INCLUDE1'        => '',
   'INCLUDE2'        => '',
   'FORUMACTIVE'     => COUPONLINKINACTIVE,
   'HOTCACTIVE'      => COUPONLINKINACTIVE,
   'NEWCACTIVE'      => COUPONLINKINACTIVE,
   'EXPCACTIVE'      => COUPONLINKINACTIVE,
   'FRSCACTIVE'      => COUPONLINKINACTIVE,
));

$oMerchantListTop = new TopMerchantList();
//$oMerchantListTop->setOrder("Name");
$tpl->define_dynamic("top_merchant_list","include");
$TopMerchantCount = ((integer)($oMerchantListTop->getItemCount() / 2)) * 2;
$tpl->assign(array(
   TOP_MERCHANT_COUNT => $TopMerchantCount,
));
for ( $i=1; $i<=($TopMerchantCount/2); $i++){
   $m1 = $oMerchantListTop->getItemByNum($i-1);
   $m2 = $oMerchantListTop->getItemByNum($i+($TopMerchantCount/2)-1);
   $cm1 = $oCustomer->checkMerchant($m1->get("Merchant_"));
   $cm2 = $oCustomer->checkMerchant($m2->get("Merchant_"));
   $tpl->assign(array(
      'SELCOLOR1'      => $cm1 == "checked" ? "#FCE482" : "#FFFFFF",
      'TOPMERCH1VAL'   => $m1->get("Merchant_"),
      'TOPMERCH1NAME'  => $m1->get("Name"),
      'TOPMERCH1'      => $cm1,
      'SELCOLOR2'      => $cm2 == "checked" ? "#FCE482" : "#FFFFFF",
      'TOPMERCH2VAL'   => $m2->get("Merchant_"),
      'TOPMERCH2NAME'  => $m2->get("Name"),
      'TOPMERCH2'      => $cm2,
   ));
   $tpl->parse("TOPMERCHANTLIST",".top_merchant_list");
}

$weekly_yes = $oCustomer->checked("WeeklyNews",1);
$tpl->assign(array(
  'WEEKLY_YES'      => $weekly_yes,
  'WEEKLY_NO'       => $weekly_yes == "checked" ? "" : "checked",
  'OFTEN_ASAP'      => $oCustomer->checked("FreqLetter",1),
  'OFTEN_DAILY'     => $oCustomer->checked("FreqLetter",2),
  'OFTEN_WEEKLY'    => $oCustomer->checked("FreqLetter",3),
  'OFTEN_NEVER'     => $oCustomer->checked("FreqLetter",0),
  'ALERT_EXPIRE'    => $oCustomer->checked("AlertEmail",1),
));

$oMerchantList = new NonTopMerchantList();
$tpl->assign(array(
   ALL_MERCHANT   => $oMerchantList->getselect("Merchant_","Name"),
   MY_MERCHANT    => $oCustomer->Merchants->getselect("Merchant_","Name"),
));

$tpl->assign(array(
   LOGGEDIN => (strlen(base64_decode($HTTP_COOKIE_VARS["DIU"])) > 0 ) ? "已登录的Email: ".(base64_decode($HTTP_COOKIE_VARS["DIU"])).".&nbsp;<a href=\"".__LINK_ROOT."register.php?action=log\" class=\"loggedIn\">更改用户</a>" : "&nbsp;",
));

$tpl->parse("INCLUDE_PAGE","include");
$tpl->assign(array(
   MAIN_CONTENT   => $tpl->fetch("INCLUDE_PAGE")
));
$tpl->parse("MAIN","main");
$tpl->xprint();
?>