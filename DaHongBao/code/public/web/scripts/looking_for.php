<?php
	   require_once("../etc/const.inc.php");

	   require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
	   require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
	   require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
	   require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
	   require_once(__INCLUDE_ROOT."lib/classes/class.NewsLetter.php");
	   require_once(__INCLUDE_ROOT."lib/classes/class.xxObject.php");
	   require_once(__INCLUDE_ROOT."lib/classes/class.xxMail.php");

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$tpl->VerifyUnmatched = "no";
$tpl->define(array(
   'main'      => get_browser_template("cache/main_account.tpl"),
));

if ( $posted == "yes" ){
   $tpl->define(array(
      'include'   => get_browser_template("info.tpl")
   ));

   $mail=new xxMail(array("X-Mailer: xxMail Class"));
   $mail->_CRLF="\r\n";
   $mail->addText("First name: ".$firstname.$mail->_CRLF.
                  "Last name: ".$lastname.$mail->_CRLF.
                  "Email: ".$email.$mail->_CRLF.
                  "Product or merchant you want coupons for: ".$productmerchant.$mail->_CRLF.
                  "Comment: ".$comments.$mail->_CRLF);
   if(!$mail->buildMessage()) $mail->setError("Ошибка создания письма");
   $mail->send(
      __REQUEST_EMAIL,
      __REQUEST_EMAIL,
      $firstname." ".$lastname,
      $email,
      "Response To Your Coupon Request",
      "Response To Your Coupon Request"
   );
}
else{
   $tpl->define(array(
      'include'   => get_browser_template("looking_for.tpl")
   ));
}

$tpl->assign(array(
   'BASE_HOSTNAME'     => BASE_HOSTNAME,
   'LINK_ROOT'      => __LINK_ROOT,
   'PAGE_TITLE'     => "Let us find coupons for you",
   'NAVIGATION_PATH'=> getNavigation(array("Let us find coupons for you" => "")),
   'LEFT_CONTENT'   => "left content",         // change
   'PROMO_HEAD'     => __DEFAULT_PROMO_HEAD,
   'PROMO_TEXT'     => __DEFAULT_PROMO_TEXT,
   'PROMO_FOOTER_1' => __DEFAULT_PROMO_FOOTER_1,
   'PROMO_FOOTER_2' => __DEFAULT_PROMO_FOOTER_2,
   'PROMO_FOOTER_3' => __DEFAULT_PROMO_FOOTER_3,
   'PROMO_URL'      => __DEFAULT_PROMO_URL,
   'SCRIPT_NAME'    => $SCRIPT_URL,
//   TEXT           => "Thank you for your submission.<br>A dahongbao Shopping Expert will respond to your request within 24 hours.<br><br><br><br><br>",
   'TEXT'           => "Thank you for your submission.<br>A dahongbao Shopping Expert will respond to your request within 48 hours.<br><br>Please note that some coupons may be published before a Shopping Expert responds to your email.<br>In order to be notified as soon as coupons become available, we invite you to create a FREE personalized account so that you can always get alerts for coupons to your favorite merchants.<br><br>Click <a href=\"http://www.dahongbao.com/notify_me.php\">here</a> to create your free account!",
   'CATEGORY_CUR'   => -1,
   'MERCHANT_CUR'   => -1,
   'COUPON_CUR'     => -1,
   'KEYWORDS'       => "",
   'DESCRIPTION_H'  => "",
   'LOGGEDIN'      => "",
   'INCLUDE1'       => '',
   'INCLUDE2'       => '',
   'FORUMACTIVE'        => COUPONLINKINACTIVE,
   'HOTCACTIVE'         => COUPONLINKINACTIVE,
   'NEWCACTIVE'         => COUPONLINKINACTIVE,
   'EXPCACTIVE'         => COUPONLINKINACTIVE,
   'FRSCACTIVE'         => COUPONLINKINACTIVE,
));

$tpl->define_dynamic("graph_header","include");
$tpl->assign(array(
   GRAPH_HEADER   => __LINK_ROOT."images/Looking_For.gif"
));
$tpl->parse("HEADER_GRAPH","graph_header");

$tpl->parse("INCLUDE_PAGE","include");
$tpl->assign(array(
   MAIN_CONTENT   => $tpl->fetch("INCLUDE_PAGE")
));
$tpl->parse("MAIN","main");
echo str_replace("<?echo getDateTime(\"F d, Y\")?>",getDateTime("F d, Y"),$tpl->fetch());
?>
