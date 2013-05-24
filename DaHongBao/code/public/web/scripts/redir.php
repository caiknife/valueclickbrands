<?php
require_once("../etc/const.inc.php");

require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__INCLUDE_ROOT."lib/classes/class.TrackingURL.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Transaction.php");
require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

if ( $p > 0 ){

  // if ( 0 < $_COOKIE['AFID'] && 0 < $_COOKIE['CRID'] ){
//      @file('http://affiliates.dahongbao.com/afp_outclick.php?afid='.$_COOKIE['AFID'].'&crid='.$_COOKIE['CRID']);
//   }

   $oCoupon = new Coupon($p);
   $oMerchant = new Merchant($oCoupon->get("Merchant_"));

   $c = 0;
   $p = (integer)$p;
   $m = $oMerchant->get("Merchant_");

   if ( $oCoupon->canShow() == 0 || $oMerchant->get("isActive") == 0 ){
      if ( $_GET['test'] == 'yes' ){
         //echo("Location: ".__LINK_ROOT);
		 redirect301(__LINK_ROOT."notfound.php");
         exit;
      }
      else{
         //header("Location: ".__LINK_ROOT);
		 redirect301(__LINK_ROOT."notfound.php");
         exit;
      }
   }
   if ( $f == "yes" ) {
   	  if(strlen($oCoupon->get("URL")) > 10) {
	  	  $url = $oCoupon->get("URL");
	  } else {
	  	  $url = $oMerchant->get("URL");
	  }
	  redirect301($url);
      exit;
   } else {
   	  if(strlen($oCoupon->get("URL") > 10)) {
	  	  $url = $oCoupon->get("URL");
	  } else {
	  	  $url = $oMerchant->get("URL");
	  }
   	  redirect301($url);
      exit;
   }
   if ( $f == "yes" ) {

        $source  = isset($HTTP_COOKIE_VARS["SOURCE"])  ? rawurldecode($HTTP_COOKIE_VARS["SOURCE"])  : "Unknown";
//      $referer = isset($HTTP_COOKIE_VARS["REFERER"]) ? rawurldecode($HTTP_COOKIE_VARS["REFERER"]) : "Unknown";
		  if($source=="Unknown") {
			$source = getSource();
		  }


 //     require_once(__INCLUDE_ROOT."lib/session.php");

      // Retrieve LTUID and CMSESSION cookies info.
      // The UserTracking.php will set variables $ltuid and $sessionID
      require_once(__INCLUDE_ROOT."lib/UserTracking.php");
      $tran = new Transaction($ltuid, $sessionID, $m, $p);

      if ( $_COOKIE['SOURCE_BANNER'] != '' ){
          $oSource = new Source();
          $oSource->find($_COOKIE['SOURCE']);

          $url = $tran->appendTransactionInfo( $oSource->dynamicURL($oCoupon, $oMerchant) );

          if ( $_GET['test'] == 'yes' ){
              //echo('Location: '.$url);
			  redirect301($url);
              exit;
          }
          else{
              //header('Location: '.$url);
			  redirect301($url);
              exit;
          }
      }
      else{
         $url = $tran->appendTransactionInfo( dynamicURL($oCoupon, $oMerchant, $source) );

         if ( $_GET['test'] == 'yes' ){
            //echo("Location: ".$url);
			redirect301($url);
            exit;
         }
         else{
            //header("Location: ".$url);
			redirect301($url);
            exit;
         }
      }
   }
   else{
      if ( $_GET['test'] == 'yes' ){
         //echo("(prevlast)Location: ".__LINK_ROOT);
		 redirect301(__LINK_ROOT."notfound.php");
         exit;
      }
      else{
         //header("Location: ".__LINK_ROOT);
		 redirect301(__LINK_ROOT."notfound.php");
         exit;
      }
   }
}
else{
   // User clicks merchant's logo instead of coupon icon or coupon description.
   // Transaction does not start yet!
   $isClick = $_GET["fff"] == 'yes' ? 0 : 1;

   if ( $fff != 'yes' ){
     if( isset($_COOKIE['AFID']) && isset($_COOKIE['CRID']) ) {
         @file('http://affiliates.dahongbao.com/afp_outclick.php?afid='.$_COOKIE['AFID'].'&crid='.$_COOKIE['CRID']);
     }
   }
   if($source=="Unknown") {
     $source = getSource();
   }

      require_once(__INCLUDE_ROOT."lib/UserTracking.php");
   $tran = new Transaction($ltuid, $sessionID, $m, 0, $isClick);

   $oMerchant = new Merchant($m);
   $url = dynamicURL($oMerchant, $oMerchant, $source);
   $url = $tran->appendTransactionInfo($url);

   if ( $_GET['test'] == 'yes' ){
      //echo("Location: ".$url);
	  redirect301($url);
      exit;
   }
   else{
      //header("Location: ".$url);
	  redirect301($url);
      exit;
   }
}

?>
