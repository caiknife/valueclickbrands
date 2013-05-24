<?php
require_once("../etc/const.inc.php");

require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Date.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
require_once(__INCLUDE_ROOT."lib/classes/class.xxObject.php");
require_once(__INCLUDE_ROOT."lib/classes/class.xxMail.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.MailStat.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$oCoupon = new Coupon($p);
$oMailStat = new MailStat();

$sendauthor = $oCoupon->CouponInfo['author'];

if ( $posted == "yes" ){
   $tpl->define(array(
      'main'   => get_browser_template("is_send_friend.tpl")
   ));

   $mail=new xxMail(array("X-Mailer: xxMail Class"));
   $mail->_CRLF="\r\n";

   if($oCoupon->get("isFree") == '0') {
   	   $couponURL = Utilities::getURL("couponUnion", array("Merchant_" => $oCoupon->get("Merchant_"),
				                        "Coupon_" => $oCoupon->get("Coupon_")));
   } else {
   	   $couponURL = Utilities::getURL("couponFree", array("NameURL" => $oCoupon->get("MerchantNameURL"),
				                        "Coupon_" => $oCoupon->get("Coupon_")));
   }
   $tmpStr = "您的朋友".$sendauthor."在大红包网站上找到一份优惠信息给您。大红包网站 - www.dahongbao.com - 帮你轻松购物，省钱多多！".$mail->_CRLF.$mail->_CRLF.
                  "商家名称：　　　".($oCoupon->get("MerchantName")?$oCoupon->get("MerchantName"):"其他商家").$mail->_CRLF.
                  "优惠活动介绍：　".$oCoupon->get("Descript").$mail->_CRLF.
                  "查看：　　　　　http://www.dahongbao.com".$couponURL.$mail->_CRLF.
                  "优惠券编码：　　".$oCoupon->get("Code").$mail->_CRLF.
                  "活动过期时间: 　".getUserDate($oCoupon->get("ExpireDate")).$mail->_CRLF.$mail->_CRLF.$mail->_CRLF.
                  "您的朋友对您说：".$mail->_CRLF.
                  $comments;
   $tmpStr = iconv("gb2312", "utf-8", $tmpStr);
   $sendfriendtitle = iconv("gb2312", "utf-8", "您的朋友给你发送了一条优惠券信息~");
   $mail->addText($tmpStr);
   if(!$mail->buildMessage()) $mail->setError("Email发送错误！！");
   for ( $i=1; $i<=3; $i++ ){
      $friend = "friend".$i;
      if ( strlen($$friend) > 0 ){
         $mail->send(
            '',
            $$friend,
            $sendauthor,
            $email,
            //iconv("gb2312", "utf-8", "your friend".$name."给您一份关于商家 ".$oCoupon->get("MerchantName")." 的优惠活动信息"),
			$sendfriendtitle,
            ""
         );
         $oMailStat->addLetter("MailFriend");
      }
   }
}
else{
   $tpl->define(array(
      'main'      => get_browser_template("send_friend.tpl"),
   ));
   $tpl->assign(array(
      'BASE_HOSTNAME'     => BASE_HOSTNAME,
      'LINK_ROOT'         => __LINK_ROOT,
      'SCRIPT_NAME'       => $PHP_SELF,
      'MERCHANT_NAME'     => $oCoupon->CouponInfo['MerchantName'],
      'CDESC'             => $oCoupon->get("Descript"),
      'CCODE'             => $p,
      'CDATE'             => $oCoupon->CouponInfo['ExpireDate'],
      'CRESTR'            => $oCoupon->CouponInfo['City'],
      'CID'               => $oCoupon->get("Coupon_"),
      'SEND_FRIEND_TEXT'  => __SEND_FRIEND_DEFAULT_TEXT,
   ));
}

$tpl->assign(array(
   MAIN_CONTENT   => $tpl->fetch("INCLUDE_PAGE")
));
$tpl->parse("MAIN","main");
echo str_replace("<?echo date(\"F d, Y\")?>",date("F d, Y"),$tpl->fetch());
?>
