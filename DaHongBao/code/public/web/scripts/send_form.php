<?php
set_time_limit(999999999);
      require_once("../etc/const.inc.php");
      require_once(__INCLUDE_ROOT."lib/classes/class.xxObject.php");
      require_once(__INCLUDE_ROOT."lib/classes/class.xxMail.php");

$period = "";
$freck  = -1;
               $mail=new xxMail(array("X-Mailer: xxMail Class"));
               $mail->_CRLF="\r\n";
               $coupon_list = "";
               $coupon_list1 = "";
               $mMerchant='';
               $mOldMerchant='';
               $mCountRow=0;
               $mCountGr = 0;
               $mFirst = 0;

if(strpos($for_brows,'confirm.html')) {
               $mail->addHtml('<html><body>'.
                              '商家名字 '. $requiredcompanyname.'<br>'.
                              '商家主页: '. $website.'<br>'.
                              '货品类别: '. $shop_categ.'<br><br><br>'.
                              '优惠券编号： '. $agreebox_code.'<br>'.
                              'Affiliate Program? '. $agreebox_affil.'<br>'.
                              'Advertise Online? '. $agreebox_adver.'<br><br><br>'.
                              'We Advertise Online At: '. $chars_where.'<br><br><br>'.
                              '联系人信息<br>'.
                              '姓名: '. $name.'<br>'.
                              'Title: '. $title.'<br>'.
                              '电话: '. $phone.'<br>'.
                              'Email: '. $email.'<br>'.
                              '其他: '. $chars_other.'<br>'.
                              '</body>'.
                              '</html>');



               if(!$mail->buildMessage()) $mail->setError("Email发送错误！");
               $mail->send(
                  ' business development',
                  'bizdev@mezimedia.com',
                  $name,
                  $email,
                  'Advertise',
                  ""
               );
}
elseif (strpos($for_brows,'custservice.html')){
               $mail->addHtml('<html><body>'.
                              '名字: '. $requiredname.'<br>'.
                              '说明: '. $requiredcomments.'<br>'.
                              '</body>'.
                              '</html>');



               if(!$mail->buildMessage()) $mail->setError("Email发送错误！");
               $mail->send(
                  ' 客户服务',
                  'userfeedback@mezimedia.com',
                  $requiredname,
                  $requiredemail,
                  '说明',
                  ""
               );
}
/*               $mail->send(
                  $oCustomer->get("FName")." ".$oCustomer->get("LName"),
                  $oCustomer->get("Email"),
                  dahongbao,
                  "notify@dahongbao.com",
                  $oCustomer->get("FName").", 这里有新的优惠信息！(".$oCustomer->get("Email").")",
                  ""
               );
*/
header("Location: ".$for_brows);
?>