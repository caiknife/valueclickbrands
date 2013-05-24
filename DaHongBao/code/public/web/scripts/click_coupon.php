<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
//$isMerchant = isset( $_GET["m"] ) && isset( $_GET["fff"] ) && ($_GET["fff"] == "yes");
//$s = $_REQUEST["source"];
//$r = getenv("HTTP_REFERER");

//include_once(__INCLUDE_ROOT."/lib/session.php");
//include_once(__INCLUDE_ROOT."/lib/UserTracking.php");
if($_REQUEST['p'] > 0) {
	$sql = "SELECT c.Coupon_,c.isFree,c.Merchant_,m.NameURL FROM Coupon c LEFT JOIN Merchant m ON m.Merchant_ = c.Merchant_ " .
	       "WHERE m.isActive = 1 AND c.isActive = 1 AND c.Coupon_ = ".$_REQUEST['p'];
	$rs = DBQuery::instance()->getRow($sql);
	if($rs["Coupon_"] > 0) {
		if($rs["isFree"] == 1) {
			$couponURL = Utilities::getURL("couponFree", array("NameURL" => $rs["NameURL"],
				                        "Coupon_" => $rs["Coupon_"]));
		    redirect301($couponURL);
			exit;
		} else {
			$url = "/redir.php?p=".$rs["Coupon_"]."&f=yes&m=".$rs["Merchant_"];
			redirect301($url);
			exit;
		}
	} else {
		redirect301(__LINK_ROOT."notfound.php");
		exit;
	}
} else {
	redirect301(__LINK_ROOT."notfound.php");
    exit;
}

?>

