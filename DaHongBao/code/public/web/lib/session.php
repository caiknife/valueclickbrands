<?php
    require_once(__INCLUDE_ROOT."etc/const.inc.php");

$category = (integer)$c;
$merchant = (integer)$m;
$coupon   = (integer)$p;

$new_visitor = 0;
if ( $newvisit == "1" ){
    require_once(__INCLUDE_ROOT."lib/classes/class.Session.php");
   $oSession = new Session($REMOTE_ADDR);
   $new_visitor = $oSession->update();
}



if ( $_COOKIE['SOURCE_BANNER'] != '' ){
    $source = $_COOKIE['SOURCE'];
}

    require_once(__INCLUDE_ROOT."lib/classes/class.Source.php");
$oSource = new Source();
if ( strlen($source) > 0 ){
   $oSource->find($source);
   if ( $oSource->get("Source_") > 0 ){
      $source = $oSource->get("Source_");
   }
   else{
      $source = 1;
   }
}
else{
   $source = 1;
}

    require_once(__INCLUDE_ROOT."lib/classes/class.Referrer.php");
$oReferrer = new Referrer();
if ( (strlen($referer) > 0) && ($referer != 'Unknown') ){
   $oReferrer->find(($referer));
   if ( !($oReferrer->get("Referrer_") > 1) ){
      $oReferrer->insert(($referer));
   }
   $referer = $oReferrer->get("Referrer_");
}
else{
   $referer  = 1;
}

$keyword  = 1;

    require_once(__INCLUDE_ROOT."lib/classes/class.Statistic.php");
$oStatistic = new Statistic();
$oStatistic->update($source,$referer,$keyword,$new_visitor,$category,$merchant,$coupon);
/*
if ( $_COOKIE['AFID'] > 0 && $_COOKIE['CRID'] > 0 && $PHP_SELF == '/redir.php' ){
   $oStatistic->run_spec('UPDATE afp_out_clicks SET outcount=(outcount+1) WHERE clickdate=CURDATE() AND afid='.$_COOKIE['AFID'].' AND crid='.$_COOKIE['CRID']);
   if ( $oStatistic->RowAffect == 0 ){
      $oStatistic->run_spec('INSERT INTO afp_out_clicks (clickdate,afid,crid,outcount) VALUES(CURDATE(),'.$_COOKIE['AFID'].','.$_COOKIE['CRID'].',1)');
   }
}
*/
?>
