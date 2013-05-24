<?php
    require_once("../etc/const.inc.php");

    require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.TrackingURL.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");

$oCoupon = new Coupon($p);
$oOldCoupon = new Coupon($op);
$oMerchant = new Merchant($oCoupon->get("Merchant_"));
$oMC  = new MerchantCouponList($oMerchant->get("Merchant_"));
$oMC->setOrder("Descript");

if ( $verifCoup == "" ){
   $verCY = split(";",$HTTP_COOKIE_VARS["VerifyCouponYes"]);
   if ( in_array($p,$verCY) ){
      $verifCoup = "yes";
   }
   $verCN = split(";",$HTTP_COOKIE_VARS["VerifyCouponNo"]);
   if ( in_array($p,$verCN) ){
      $verifCoup = "no";
   }
}
else{
   if ( $verifCoup == "yes" ){
      setcookie("VerifyCouponYes",$HTTP_COOKIE_VARS["VerifyCouponYes"].";".$p,0,"/",".".COOKIE_HOSTNAME);
      $oCoupon->verified("yes");
   }
   else{
      setcookie("VerifyCouponNo",$HTTP_COOKIE_VARS["VerifyCouponNo"].";".$p,0,"/",".".COOKIE_HOSTNAME);
      $oCoupon->verified("no");
   }
}

if ( $verifCoup == "" ){
   $verify = "是，有效<input type=\"radio\" style=\"background-color:#FFCE00\" name=\"verifCoup\" value=\"yes\" onClick=\"submit();\">&nbsp;&nbsp;&nbsp;不，无效<input type=\"radio\" style=\"background-color:#FFCE00\" name=\"verifCoup\" value=\"no\" onClick=\"submit();\">";
}
else{
   $verify = $verifCoup == "yes" ? "感谢您的支持！" : "抱歉，我们会尽快做出处理。";
//   $verify = "Alredy verified (status: ".$verifCoup.")";
}

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");

if ( $oCoupon->emptyURL() && $verifCoup == "" && $f == "yes"){
   $c = 0;
   $p = (integer)$p;
   $m = $oMerchant->get("Merchant_");
   $source = rawurldecode($HTTP_COOKIE_VARS["SOURCE"]);
   $referer= rawurldecode($HTTP_COOKIE_VARS["REFERER"]);
    require_once(__INCLUDE_ROOT."lib/session.php");
}

$tpl->define(array(
   'main'      => get_browser_template("popup.tpl"),
));

if ( strncmp($verify,"Yes&",4) == 0 ){
   $tpl->define_dynamic("did_coupon_work",'main');
   $tpl->parse("DID_COUPON_WORK","did_coupon_work");
}

$tpl->assign(array(
   'BASE_HOSTNAME'     => BASE_HOSTNAME,
   'LINK_ROOT'      => __LINK_ROOT,
   'COUP_ID'        => $p,
   'COUPON_CODE'    => $oCoupon->get("Code") != "" ? $oCoupon->get("Code") : "优惠券没有编号",
//   COUPON_URL'     => ( ( $oCoupon->emptyURL()==0 && $mv!="yes" ) || $f != "yes" ) ? "opener.location.replace('".__LINK_ROOT."redir.php?p=".$p."&f=yes');" : "",
   'COUPON_URL'     => ( $oCoupon->getURL() != $oOldCoupon->getURL() && $FIRST != 1) ? "opener.location.href = '".__LINK_ROOT."redir.php?p=".$p."&f=yes".($_GET['test']=='yes'?'&test=yes':'')."';" : "",
   'COUPONLIST'     => $oMC->getselect("Coupon_","Descript",$oCoupon->get("Coupon_"),array(),50),
   'RAWMERCHANT'    => (str_replace(' ','_',$oMerchant->get("Name"))),
   'RESTR'          => strlen($oCoupon->get("LongRestr")) > 0 ? $oCoupon->get("LongRestr") : $oCoupon->get("Restr"),
   'READ_RESTRICTION' => "查看此优惠券的适用范围",
   'VERIFY_STATUS'  => $verify,
));
if ( $oCoupon->get("Code") != "" ){
   $tpl->define_dynamic("this_is_coupon_2","main");
   $tpl->parse("this_is_coupon_parse_2","this_is_coupon_2");
//   $tpl->define_dynamic("this_is_coupon_3","main");
//   $tpl->parse("this_is_coupon_parse_3","this_is_coupon_3");
}

$tpl->parse("MAIN","main");
$tpl->xprint("MAIN");
?>