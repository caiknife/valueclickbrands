<?php
include("../links.inc.php");
include(__INCLUDE_ROOT."etc/const.inc.php");
include(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

function insert_topic_mer($topic_id,$post_message,$forum_id) {
   if (  0 < $forum_id ) {
      $oTmp = new VItem();
      $res_arr = $oTmp->run_spec('SELECT Merchant_ from `phpbb_forums` WHERE forum_id='.$forum_id);
      $merch = $res_arr['Merchant_'];
      if ( 0 < $merch ) {
         $oTmp->run_spec("REPLACE INTO TopicMer (topic_id,Merchant_) VALUES(".$topic_id.",".$merch.")");
      }
   }
   $oMerchantList = new MerchantList();
   while ( $oMerchant  = $oMerchantList ->nextItem() ) {
      $check_arr = explode("\r\n",strtolower($oMerchant->get("Keywords")));
      array_push($check_arr,strtolower($oMerchant->get("Name")));
      foreach ( $check_arr as $val ) {
         $val1 = str_replace('/','\/',trim($val));
//         $val1 = trim($val);

         if (''!=$val1){
//            if ( strpos(strtolower('.'.$post_message),strtolower($val1)) ) {
            $pattern = "/\b(".$val1.")\b/im";
            if (preg_match($pattern, $post_message)>0) {
               $oMerchant->run_spec("REPLACE INTO TopicMer (topic_id,Merchant_) VALUES(".$topic_id.",".$oMerchant->get('Merchant_').")");
               break;
            }
         }
      }
   }

}




?>
