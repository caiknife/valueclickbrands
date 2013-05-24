<?php
   require_once("links.inc.php");
   require_once(__INCLUDE_ROOT."etc/const.inc.php");
   require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

$oMerchant = new Merchant($actMer);
echo "<html>\n";
echo "<head>\n";
echo "<script language=\"JavaScript\">\n";
echo "<!--\n";
if ( $oMerchant->get("Merchant_") > 0 ){
   echo "window.location.replace('".__LINK_ROOT.(str_replace(' ','_',$oMerchant->get("Name")))."/".(str_replace(' ','_',$oMerchant->get("Name")." coupon".($actCat > 0 ? "_".$actCat : ""))).".html".($source!="" ? "?source=".$source : "")."');\n";
}
else{
   echo "window.location.replace('".__LINK_ROOT.($source!="" ? "?source=".$source : "")."');\n";
}
echo "//-->\n";
echo "</script>\n";
echo "</head>\n";
echo "</html>\n";

?>
