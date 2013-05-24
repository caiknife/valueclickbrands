<?php
    require_once("../etc/const.inc.php");

    require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.NewsLetter.php");


$oNewsLetter = new NewsLetter();
if ( $unsubscribe == "yes" ){
   $text = "您没有订阅大红包新闻。";
   $oNewsLetter->find($email);
   $oNewsLetter->delete();
}
else{
   $text = "感谢订阅大红包新闻。我们将定时给你发送最新的优惠新闻及其他各类信息。";
   $oNewsLetter->set("Email",$email);
   $oNewsLetter->rec2param();
   $oNewsLetter->insert();
}

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$tpl->define(array(
   'main'      => get_browser_template("cache/main.tpl"),
   'include'   => get_browser_template("info.tpl")
));

$tpl->assign(array(
   'BASE_HOSTNAME'     => BASE_HOSTNAME,
   'LINK_ROOT'      => __LINK_ROOT,
   'PAGE_TITLE'     => "每周更新",
   'NAVIGATION_PATH'=> getNavigation(array("Receive weekly updates" => "")),
   'LEFT_CONTENT'   => "left content",         // change
   'PROMO_HEAD'     => __DEFAULT_PROMO_HEAD,
   'PROMO_TEXT'     => __DEFAULT_PROMO_TEXT,
   'PROMO_FOOTER_1' => __DEFAULT_PROMO_FOOTER_1,
   'PROMO_FOOTER_2' => __DEFAULT_PROMO_FOOTER_2,
   'PROMO_FOOTER_3' => __DEFAULT_PROMO_FOOTER_3,
   'PROMO_URL'      => __DEFAULT_PROMO_URL,
   'TEXT'           => $text,
   'CATEGORY_CUR'   => -1,
   'MERCHANT_CUR'   => -1,
   'COUPON_CUR'     => -1,
   'KEYWORDS'       => "",
   'DESCRIPTION_H'  => "",
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
echo str_replace("<?echo getDateTime(\"F d, Y\")?>",getDateTime("F d, Y"),$tpl->fetch());
?>
