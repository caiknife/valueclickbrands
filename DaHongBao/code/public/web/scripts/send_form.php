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
                              '�̼����� '. $requiredcompanyname.'<br>'.
                              '�̼���ҳ: '. $website.'<br>'.
                              '��Ʒ���: '. $shop_categ.'<br><br><br>'.
                              '�Ż�ȯ��ţ� '. $agreebox_code.'<br>'.
                              'Affiliate Program? '. $agreebox_affil.'<br>'.
                              'Advertise Online? '. $agreebox_adver.'<br><br><br>'.
                              'We Advertise Online At: '. $chars_where.'<br><br><br>'.
                              '��ϵ����Ϣ<br>'.
                              '����: '. $name.'<br>'.
                              'Title: '. $title.'<br>'.
                              '�绰: '. $phone.'<br>'.
                              'Email: '. $email.'<br>'.
                              '����: '. $chars_other.'<br>'.
                              '</body>'.
                              '</html>');



               if(!$mail->buildMessage()) $mail->setError("Email���ʹ���");
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
                              '����: '. $requiredname.'<br>'.
                              '˵��: '. $requiredcomments.'<br>'.
                              '</body>'.
                              '</html>');



               if(!$mail->buildMessage()) $mail->setError("Email���ʹ���");
               $mail->send(
                  ' �ͻ�����',
                  'userfeedback@mezimedia.com',
                  $requiredname,
                  $requiredemail,
                  '˵��',
                  ""
               );
}
/*               $mail->send(
                  $oCustomer->get("FName")." ".$oCustomer->get("LName"),
                  $oCustomer->get("Email"),
                  dahongbao,
                  "notify@dahongbao.com",
                  $oCustomer->get("FName").", �������µ��Ż���Ϣ��(".$oCustomer->get("Email").")",
                  ""
               );
*/
header("Location: ".$for_brows);
?>