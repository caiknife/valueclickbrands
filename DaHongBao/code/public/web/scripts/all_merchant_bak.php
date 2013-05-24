<?php
      require_once("../etc/const.inc.php");

      require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
      require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
      require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
      require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$tpl->define(array(
   'main'      => get_browser_template("cache/main.tpl"),
   'include'   => get_browser_template("all_merchant.tpl"),
));

$tpl->assign(array(
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
   'PAGE_TITLE'     => "所有的商家",
   'NAVIGATION_PATH'=> getNavigation(array("All Merchants" => "")),
   'INCLUDE1'       => '',
   'INCLUDE2'       => '',
   'FORUMACTIVE'        => COUPONLINKINACTIVE,
   'HOTCACTIVE'         => COUPONLINKINACTIVE,
   'NEWCACTIVE'         => COUPONLINKINACTIVE,
   'EXPCACTIVE'         => COUPONLINKINACTIVE,
   'FRSCACTIVE'         => COUPONLINKINACTIVE,
));

$oMerchantList = new AllMerchantList();
$tcount = $oMerchantList->getItemCount();

$tpl->define_dynamic("column_1","include");
$tpl->define_dynamic("head_1","include");
$i = 0;
$pref = '';
$tmpHead ='';
$tmpHeadOld ='';
$blank = 0;
while ( $oMerchant = $oMerchantList->nextItem() ){
   $tmpHead = $oMerchant->get("Name");
     if(strtoupper($tmpHead[0])!==strtoupper($tmpHeadOld[0])){
        if (((!is_numeric($tmpHead[0]))||((!is_numeric($tmpHeadOld[0]))&&($tmpHeadOld!=='')))||($i==$tcount)) {
         $tpl->assign(array(
            'MERCHANT_HEAD'   => (($blank==0)?'':'<br>').'<SPAN class=infoLink><b>'.((is_numeric($tmpHeadOld[0]))?'0-9':strtoupper($tmpHeadOld[0])).'</b></SPAN><br>',
         ));
         $tpl->parse("HEAD_1",".head_1");
         $blank++;
         $pref       = '';
        }
     }
      $tpl->assign(array(
         'MERCHANT_URL'   => __LINK_ROOT.str_replace(" ","_",$oMerchant->get("NameURL"))."/index.html",
         'MERCHANT_NAME'  => $oMerchant->get("isPremium")==1?'<b>'.$oMerchant->get("Name").'</b>':$oMerchant->get("Name"),
         'MERCHANT_COUPON'=> (integer)$oMerchant->get("CouponCount"),
      ));
      $tmpHeadOld = $tmpHead;
      $tpl->parse("COLUMN_1",$pref."column_1");
      $pref       = '.';
      $i++;
   if ( $i >= ($tcount/3)+1 ){
      $tpl->assign(array(
         'MERCHANT_HEAD'   => (($blank==0)?'':'<br>').'<SPAN class=infoLink><b>'.((is_numeric($tmpHeadOld[0]))?'0-9':strtoupper($tmpHeadOld[0])).'</b></SPAN><br>',
      ));
      $tpl->parse("HEAD_1",".head_1");
      break;
   }
}

$tpl->define_dynamic("column_2","include");
$tpl->define_dynamic("head_2","include");
$j = 0;
$pref = '';
$blank = 0;
while ( $oMerchant = $oMerchantList->nextItem() ){
   $tmpHead = $oMerchant->get("Name");
     if(strtoupper($tmpHead[0])!==strtoupper($tmpHeadOld[0])){

        if (((!is_numeric($tmpHead[0]))||(!is_numeric($tmpHeadOld[0])))||($j==($tcount-$i))) {
         $tpl->assign(array(
            'MERCHANT_HEAD'   => (($tcount-$i)==1)?'':(($blank==0)?'':'<br>').'<SPAN class=infoLink><b>'.((is_numeric($tmpHeadOld[0]))?'0-9':strtoupper($tmpHeadOld[0])).'</b></SPAN><br>',
         ));
         $tpl->parse("HEAD_2",".head_2");
         $blank++;
         $pref       = '';
        }
     }
      $tpl->assign(array(
         'MERCHANT_URL'   => __LINK_ROOT.str_replace(" ","_",$oMerchant->get("NameURL"))."/index.html",
         'MERCHANT_NAME'  => $oMerchant->get("isPremium")==1?'<b>'.$oMerchant->get("Name").'</b>':$oMerchant->get("Name"),
         'MERCHANT_COUPON'=> (integer)$oMerchant->get("CouponCount"),
      ));
      $tmpHeadOld = $tmpHead;
      $tpl->parse("COLUMN_2",$pref."column_2");
      $pref       = '.';
      $j++;
   if ( $j >= $i-1 ){
      $tpl->assign(array(
         'MERCHANT_HEAD'   => (($tcount-$i)==1)?'':(($blank==0)?'':'<br>').'<SPAN class=infoLink><b>'.((is_numeric($tmpHeadOld[0]))?'0-9':strtoupper($tmpHeadOld[0])).'</b></SPAN><br>',
      ));
      $tpl->parse("HEAD_2",".head_2");
      break;
   }
}

$tpl->define_dynamic("column_3","include");
$tpl->define_dynamic("head_3","include");
$k = 0;
$blank = 0;
$pref = '';
while ( $oMerchant = $oMerchantList->nextItem() ){
   $tmpHead = $oMerchant->get("Name");
     if(strtoupper($tmpHead[0])!==strtoupper($tmpHeadOld[0])){

        if (((!is_numeric($tmpHead[0]))||(!is_numeric($tmpHeadOld[0])))||($k==$tcount-$i-$j)) {
         $tpl->assign(array(
            'MERCHANT_HEAD'   => (($tcount-$i-$j)==1)?'':(($blank==0)?'':'<br>').'<SPAN class=infoLink><b>'.((is_numeric($tmpHeadOld[0]))?'0-9':strtoupper($tmpHeadOld[0])).'</b></SPAN><br>',
         ));
         $tpl->parse("HEAD_3",".head_3");
         $blank++;
         $pref       = '';
        }
     }

      $tpl->assign(array(
         'MERCHANT_URL'   => __LINK_ROOT.str_replace(" ","_",$oMerchant->get("NameURL"))."/index.html",
         'MERCHANT_NAME'  => $oMerchant->get("isPremium")==1?'<b>'.$oMerchant->get("Name").'</b>':$oMerchant->get("Name"),
         'MERCHANT_COUPON'=> (integer)$oMerchant->get("CouponCount"),
      ));
      $tpl->parse("COLUMN_3",$pref."column_3");
      $pref       = '.';
      $k++;
      if(($k!==$tcount-$i-$j)) {
         $tmpHeadOld = $tmpHead;
      }


}

$tpl->assign(array(
   'MERCHANT_HEAD'   => (($tcount-$i-$j)==1)?'':(($blank==0)?'':'<br>').'<SPAN class=infoLink><b>'.((is_numeric($tmpHead[0]))?'0-9':strtoupper($tmpHead[0])).'</b></SPAN><br>',
));
$tpl->parse("HEAD_3",".head_3");


$tpl->parse("INCLUDE_PAGE","include");
$tpl->assign(array(
   MAIN_CONTENT   => $tpl->fetch("INCLUDE_PAGE")
));
$tpl->parse("MAIN","main");
$tpl->xprint("MAIN");
?>