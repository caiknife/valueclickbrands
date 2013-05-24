<?
if ( !defined("FUNCTION_TRACK_URL") ){
   define("FUNCTION_TRACK_URL","YES");

         require_once("links.inc.php");
         require_once(__INCLUDE_ROOT."etc/const.inc.php");

         require_once(__INCLUDE_ROOT."lib/classes/class.Transaction.php");
         require_once(__INCLUDE_ROOT."lib/UserTracking.php");


   function appendUniqueID($url, $ltuid, $sessionID) {
       $tran = new Transaction($ltuid, $sessionID, 0, 0, 2);
       $url = $tran->appendTransactionInfo($url);
       return $url;
   }
}
?>