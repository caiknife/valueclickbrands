<?php

      require_once("../etc/const.inc.php");

      require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

echo "<html>";
echo "  <head>";
//echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
echo "  <title>Which merchant has no logo?</title>";
echo "  </head>";
echo "  <body>";


$oMerchantList = new AllMerchantList();
$AllHaveLogo = "true";
while ( $oMerchant = $oMerchantList->nextItem() ){
   $MerchantID = $oMerchant->get("Merchant_");
   $MerchantName = $oMerchant->get("Name");
   
   if( !($f = @fopen(__INCLUDE_ROOT."images/merchants/".$MerchantID."/Logo.gif" , "r") ) ){
      $AllHaveLogo = "false";
      echo $MerchantName." - has no Logo.gif ";
      if( !($f = @fopen(__INCLUDE_ROOT."images/merchants/".$MerchantID."/OldLogo.gif" , "r") ) ){
         echo "<b>even OldLogo.gif</b> ";
      }
      else{
         echo "<i>but has OldLogo.gif</i> ";
      }
      echo "in directory: /images/merchants/".$MerchantID."/";
      
      if( $MerNameURL = $oMerchant->get("NameURL")){
         echo "<a href=\"/".$MerNameURL."/".$MerNameURL."_coupon.html\" target=\"_blank\"> Check: ".$MerchantName."</a><br>";
      }
   }
}
if($AllHaveLogo == "true") echo "----- All Merchants have its logo ! -----<br>";
echo "  <body>";
echo "</html>";
?>