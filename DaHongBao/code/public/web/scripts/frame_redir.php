<?

   require_once("../etc/const.inc.php");

   require_once(__INCLUDE_ROOT."lib/classes/class.Transaction.php");
   require_once(__INCLUDE_ROOT."lib/functions/func.TrackURL.php");
   require_once(__INCLUDE_ROOT."lib/UserTracking.php");


   $mUrl = base64_decode($mUrl);
//echo $mUrl;
   $mUrl = appendUniqueID($mUrl, $ltuid, $sessionID);
//echo $mUrl;
   header("Location: ".$mUrl);

?>