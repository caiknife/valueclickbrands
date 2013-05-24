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
   $tmpStr = "��������".$sendauthor."�ڴ�����վ���ҵ�һ���Ż���Ϣ������������վ - www.dahongbao.com - �������ɹ��ʡǮ��࣡".$mail->_CRLF.$mail->_CRLF.
                  "�̼����ƣ�������".($oCoupon->get("MerchantName")?$oCoupon->get("MerchantName"):"�����̼�").$mail->_CRLF.
                  "�Żݻ���ܣ���".$oCoupon->get("Descript").$mail->_CRLF.
                  "�鿴������������http://www.dahongbao.com".$couponURL.$mail->_CRLF.
                  "�Ż�ȯ���룺����".$oCoupon->get("Code").$mail->_CRLF.
                  "�����ʱ��: ��".getUserDate($oCoupon->get("ExpireDate")).$mail->_CRLF.$mail->_CRLF.$mail->_CRLF.
                  "�������Ѷ���˵��".$mail->_CRLF.
                  $comments;
   $tmpStr = iconv("gb2312", "utf-8", $tmpStr);
   $sendfriendtitle = iconv("gb2312", "utf-8", "�������Ѹ��㷢����һ���Ż�ȯ��Ϣ~");
   $mail->addText($tmpStr);
   if(!$mail->buildMessage()) $mail->setError("Email���ʹ��󣡣�");
   for ( $i=1; $i<=3; $i++ ){
      $friend = "friend".$i;
      if ( strlen($$friend) > 0 ){
         $mail->send(
            '',
            $$friend,
            $sendauthor,
            $email,
            //iconv("gb2312", "utf-8", "your friend".$name."����һ�ݹ����̼� ".$oCoupon->get("MerchantName")." ���Żݻ��Ϣ"),
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
