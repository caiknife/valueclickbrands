<?php
require_once("../etc/const.inc.php");

require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");


$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$tpl->define(array(
   'main'      => get_browser_template("cache/main.tpl"),
   'include'   => get_browser_template("advertise.tpl"),
));

$tpl->assign(array(
   'TEXT_TOP_DESC'	   => "商家",
	 'TEXT_BOTTOM_DESC'	 => "商品",
   'BASE_HOSTNAME'     => BASE_HOSTNAME,
   'LINK_ROOT'      => __LINK_ROOT,
   'PROMO_HEAD'     => __DEFAULT_PROMO_HEAD,
   'PROMO_TEXT'     => __DEFAULT_PROMO_TEXT,
   'PROMO_FOOTER_1' => __DEFAULT_PROMO_FOOTER_1,
   'PROMO_FOOTER_2' => __DEFAULT_PROMO_FOOTER_2,
   'PROMO_FOOTER_3' => __DEFAULT_PROMO_FOOTER_3,
   'PROMO_URL'      => __DEFAULT_PROMO_URL,
   'SCRIPT_NAME'    => $PHP_SELF,
   'CATEGORY_CUR'   => -1,
   'MERCHANT_CUR'   => -1,
   'COUPON_CUR'     => -1,
   'KEYWORDS'       => "",
   'DESCRIPTION_H'  => "",
   'PAGE_TITLE'     => "Advertise",
   'NAVIGATION_PATH'=> getNavigation(array("Advertise" => "")),
   'INCLUDE1'       => '',
   'INCLUDE2'       => '',
   'FORUMACTIVE'        => COUPONLINKINACTIVE,
   'HOTCACTIVE'         => COUPONLINKINACTIVE,
   'NEWCACTIVE'         => COUPONLINKINACTIVE,
   'EXPCACTIVE'         => COUPONLINKINACTIVE,
   'FRSCACTIVE'         => COUPONLINKINACTIVE,

));


$tpl->parse("INCLUDE_PAGE","include");
$tpl->assign(array(
   MAIN_CONTENT   => $tpl->fetch("INCLUDE_PAGE")
));
$tpl->parse("MAIN","main");
$tpl->xprint("MAIN");
?>