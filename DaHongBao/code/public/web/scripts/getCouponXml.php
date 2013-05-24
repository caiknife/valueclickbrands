<?php
/**
 * seach.php
 *-------------------------
 *
 * seach
 *
 * PHP versions 4
 *
 * LICENSE: This source file is from CouponMountain.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 4.0
 * @version    CVS: $Id: getCouponXml.php,v 1.1 2013/04/15 10:58:06 rock Exp $
 * @link       http://www.couponmountain.co.uk/
 * @deprecated File deprecated in Release 2.0.0
 */


require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
include_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Search.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

if ( !trim($q)) {
   $total = 0;
   $tpl->assign("total", $total);
} else {
	$tpl = new sTemplate();
	$tpl->setTemplate("suotianxia.tpl");
	
	if(($p>1)==false) $p = 1;
	$q = trim($q);
	$perPage = 10;
	$oSearch = new Search($q);
	$couponList = $oSearch->searchForSTX($p, $perPage);
	$total = $oSearch->doSearchCountForSTX($p, $perPage);
	$n = 0;
	for($i=0; $i<$total && $n<10; $i++) {
		if($couponList[$i]["Merchant_"] == 0) {
			$couponList[$i]["NameURL"] = "merchant";
			$couponList[$i]["MerName"] = "其他商家";
		}
		if($couponList[$i]["MerIsActive"] == 1) {
			$couponListTmp["MerURL"] =  "/Me-".$couponList[$i]["NameURL"]."--Mi-".$couponList[$i]["Merchant_"].".html";
		} else {
			$couponListTmp["MerURL"] = "";
		}

		if($couponList[$i]["isFree"] == 0) {
			$couponListTmp["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponList[$i]["Merchant_"],
											"Coupon_" => $couponList[$i]["Coupon_"]));
		} else {
			$couponListTmp["couponURL"] = Utilities::getURL("couponFree", array("NameURL" => $couponList[$i]["NameURL"],
											"Coupon_" => $couponList[$i]["Coupon_"]));
		} 
		$couponListTmp["Descript"] = $couponList[$i]["DescriptOri"];
		$couponListTmp["Detail"] = $couponList[$i]["DetailOri"];
		$couponListTmp["MerName"] = $couponList[$i]["MerName"];
		
		if($couponList[$i]["Merchant_"] == 0) {
			$couponListTmp["MerURL"] = "";
		}
		
		if($couponList[$i]["ImageDownload"] == 1) {
			$couponListTmp["ImageURL"] = Utilities::getImageURL($couponList[$i]["Coupon_"]);
			$image = @getimagesize("..".$couponListTmp["ImageURL"]);
			$imgx = $image[0];
			$imgy = $image[1];
			if(!($imgx > 0 && $imgy > 0)) {
				$couponListTmp["ImageURL"] = "";
			} elseif($imgx>100 || $imgy>100 ) {
				$diveX = $imgx / 100;
				$diveY = $imgy / 100;
				$diveMax = $diveX > $diveY?$diveX:$diveY;
				$imgx = floor($imgx/$diveMax);
				$imgy = floor($imgy/$diveMax);
				$couponListTmp["ImageX"] = $imgx;
				$couponListTmp["imageY"] = $imgy;
			} else {
				$couponListTmp["imageX"] = $imgx;
				$couponListTmp["imageY"] = $imgy;
			}
		} else {
			$couponListTmp["ImageURL"] = "";
		}
		
		$couponListTmp["START"] = ($couponList[$i]["StartDate"] == "0000-00-00"?$couponList[$i]["AddDate"]:$couponList[$i]["StartDate"]);
		$couponListTmp["END"] = (($couponList[$i]["ExpireDate"] == "0000-00-00" or $couponList[$i]["ExpireDate"] >= '2100-1-1')?
												"":$couponList[$i]["ExpireDate"]);
		$couponListTmp["couponID"] = $couponList[$i]["Coupon_"];
		
		$couponListTmp["City"] = $couponList[$i]["City"];
		$couponListTmp["MerID"] = $couponList[$i]["Merchant_"];
		$couponListTmp["ID"] = $couponList[$i]["Coupon_"];
		$couponListTmp["NO"] = ($p-1)*$perPage + $i + 1;
		
		if(strlen($couponListTmp["City"]) < 2) {
			if($couponList[$i]["MerIsActive"] == 1) {
				$couponListTmp["City"] = "全国";
			}
		}
		$couponListFinal[$n] = $couponListTmp;			
		$n++;
	}
	$tpl->assign("couponList", $couponListFinal);
	$tpl->assign("total", $total);
}
header('Content-Type: text/xml');
$tpl->displayTemplate();
?>