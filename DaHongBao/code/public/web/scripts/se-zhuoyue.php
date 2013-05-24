<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Date.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

$tpl = new sTemplate();
$tpl->setTemplate("se-merchant.tpl");

//Meta
//$oPage = new Page();
//$oPage->find("se-zhuoyue");
//$metaTitle = $oPage->getMeta("MetaTitle");
//$metaDescription = $oPage->getMeta("MetaDescription");
//$metaKeywords = $oPage->getMeta("MetaKeywors");
$metaTitle = '"卓越"电子优惠券，优惠活动，打折信息';

//$zhuoyue = array("838" => "卓越","1039" => "卓越家居化妆","952" => "卓越时尚礼品","1038" => "卓越数码","950" => "卓越图书",
//                                     "949" => "卓越影视","951" => "卓越音乐","954" => "卓越专卖");
$zhuoyue = "(838,1039,952,1038,950,949,951,954)";
$sql = "SELECT COUNT(c.Coupon_) AS CouponNum,c.Merchant_,m.Name FROM Coupon c,Merchant m " .
	   "WHERE c.Merchant_ = m.Merchant_ AND c.Merchant_ IN $zhuoyue AND c.isActive = 1 AND " .
	   "(ExpireDate >= CURDATE() OR ExpireDate = '0000-00-00') " .
	   "GROUP BY c.Merchant_ ORDER BY CouponNum DESC";
$zhuoyuelist = DBQuery::instance()->executeQuery($sql);
for($n=0; $n<count($zhuoyuelist); $n++) {
	$key = $zhuoyuelist[$n]["Merchant_"];
	$MerName = $zhuoyuelist[$n]["Name"];
							 
	$sql = "SELECT c.*,c.AddDate AddDate1,m.Name MerName,m.NameURL FROM Coupon c,Merchant m " .
	       "WHERE c.Merchant_ = m.Merchant_ AND c.Merchant_ = $key AND c.isActive = 1 AND " .
	       "(ExpireDate >= CURDATE() OR ExpireDate = '0000-00-00') " .
		   "ORDER BY c.ImageDownload DESC,c.AddDate DESC,IF(ExpireDate = '0000-00-00','3000-00-00',ExpireDate) ASC";
	$couponListTmp = DBQuery::instance()->executeQuery($sql);
	if(count($couponListTmp) == 0) continue;
	$count=0;
	for($i=0; $i<count($couponListTmp)&& $count<4; $i++) {
		if(!($couponListTmp[$i][Coupon_] > 0)) continue;
		if($couponListTmp[$i]["Merchant_"] == 0) {
			$couponListTmp[$i]["NameURL"] = "merchant";
			$couponListTmp[$i]["MerName"] = "其他商家";
		}
		$couponList[$count]["merchantURL"] = Utilities::getURL("merchant", array("NameURL" => $couponListTmp[$i]["NameURL"]));

		if($couponListTmp[$i]["isFree"] == 0) {
			$couponList[$count]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponListTmp[$i]["Merchant_"],
											"Coupon_" => $couponListTmp[$i]["Coupon_"]));
		} else {
			$couponList[$count]["couponURL"] = Utilities::getURL("couponFree", array("NameURL" => $couponListTmp[$i]["NameURL"],
											"Coupon_" => $couponListTmp[$i]["Coupon_"]));
		} 
		$couponList[$count]["couponTitle"] = Utilities::cutString($couponListTmp[$i]["Descript"],60);
		$couponList[$count]["merchantName"] = $couponListTmp[$i]["MerName"];
		
		if($couponListTmp[$i]["Merchant_"] == 0) {
			$couponList[$count]["merchantURL"] = "";
		}
		
		if($couponListTmp[$i]["ImageDownload"] == 1) {
			$couponList[$count]["image"] = Utilities::getImageURL($couponListTmp[$i]["Coupon_"]);
			@$image = getimagesize("..".$couponList[$count]["image"]);
			$imgx = $image[0];
			$imgy = $image[1];
			if(!($imgx > 0 && $imgy > 0)) {
				$couponListTmp["image"] = "";
			} elseif($imgx>100 || $imgy>100 ) {
				$diveX = $imgx / 100;
				$diveY = $imgy / 100;
				$diveMax = $diveX > $diveY?$diveX:$diveY;
				$imgx = floor($imgx/$diveMax);
				$imgy = floor($imgy/$diveMax);
				$couponList[$count]["imageX"] = $imgx;
				$couponList[$count]["imageY"] = $imgy;
			} else {
				$couponList[$count]["imageX"] = $imgx;
				$couponList[$count]["imageY"] = $imgy;
			}
		} else {
			$couponList[$count]["image"] = "";
		}
		
		$couponList[$count]["start"] = ($couponListTmp[$i]["StartDate"] == "0000-00-00"?$couponListTmp[$i]["AddDate1"]:$couponListTmp[$i]["StartDate"]);
		$couponList[$count]["end"] = (($couponListTmp[$i]["ExpireDate"] == "0000-00-00" or $couponListTmp[$i]["ExpireDate"] >= '2100-1-1')?
											"<span class=\"red\">优惠进行中</span>":$couponListTmp[$i]["ExpireDate"]);
		$couponList[$count]["saveUrl"] = "/account.php?action=save&p=".$couponListTmp[$i]["Coupon_"];
		$couponList[$count]["couponID"] = $couponListTmp[$i]["Coupon_"];
		$count++;
		
	}
	$couponListFinal[] = $couponList;
	$couponList = "";		
}


$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array('"卓越"优惠券搜索结果' => "")));
$tpl->assign("couponList", $couponListFinal);

$tpl->displayTemplate();

?>