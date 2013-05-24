<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

$id=$_GET['id'];

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Name" => $categoryList[$j]["NameURL"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oCoupon = new Coupon;
			$couponList = $oCoupon->getMerchantCouponList($id);
			for($i=0; $i<count($couponList); $i++) {
				if($couponList[$i]["isFree"] == '0') {
					$couponInfo[$i]["url"] = Utilities::getURL("couponUnion", array("Merchant_" => $id,
				                        "Coupon_" => $couponList[$i]["Coupon_"]));
				} else {
					if(($couponList[$i]["isMore"] == 0 || $couponList[$i]["hasDetail"] == 0) && $couponList[$i]["ImageDownload"] != 1) {
						$couponInfo[$i]["url"] = "";
					} else {
						$couponInfo[$i]["url"] = Utilities::getURL("couponFree", array("NameURL" => $id,
				                        "Coupon_" => $couponList[$i]["Coupon_"]));
					}

				}
				if($couponList[$i]["ImageDownload"] == 1) {
					$couponInfo[$i]["image"] = Utilities::getImageURL($couponList[$i]["Coupon_"]);
					$image = getimagesize("../".$couponInfo[$i]["image"]);
					$imgx = $image[0];
					$imgy = $image[1];
					if(!($imgx > 0 && $imgy > 0)) {
						$couponInfo[$i]["image"] = "";
					} elseif($imgx>100 || $imgy>100 ) {
						$diveX = $imgx / 100;
						$diveY = $imgy / 100;
						$diveMax = $diveX > $diveY?$diveX:$diveY;
						$imgx = floor($imgx/$diveMax);
						$imgy = floor($imgy/$diveMax);
						$couponInfo[$i]["imageX"] = $imgx;
						$couponInfo[$i]["imageY"] = $imgy;
					} else {
						$couponInfo[$i]["imageX"] = $imgx;
						$couponInfo[$i]["imageY"] = $imgy;
					}
				} else {
					$couponInfo[$i]["image"] = "";
				}
				$couponInfo[$i]["title"] = Utilities::cutString($couponList[$i]["Descript"],80);
				$couponInfo[$i]["detail"] = Utilities::cutString($couponList[$i]["Detail"],380);
				$couponInfo[$i]["start"] = ($couponList[$i]["StartDate"] == "0000-00-00"?$couponList[$i]["AddDate1"]:$couponList[$i]["StartDate"]);
				$couponInfo[$i]["end"] = (($couponList[$i]["ExpireDate"] == "0000-00-00" or $couponList[$i]["ExpireDate"] >= '2100-1-1')?"<span class=\"red\">优惠进行中</span>":$couponList[$i]["ExpireDate"]);
				$couponInfo[$i]["saveUrl"] = "/account.php?action=save&p=".$couponList[$i]["Coupon_"];
				$couponInfo[$i]["couponID"] = $couponList[$i]["Coupon_"];

			}

//print_r($couponInfo);
if(file_exists(__INCLUDE_ROOT.__MERCHANT_IMAGES.$id."/Logo.gif")) {
			//if(strlen($this->get("Logo")) > 0) {
				$merLogPath = "/images/merchants/".$id."/Logo.gif";
			} else {
				$merLogPath = "";
			}

$sql = "SELECT * FROM Merchant WHERE Merchant_= ".$id;
$merchantinfo = DBQuery::instance()->getRow($sql);

//print_r($merchantinfo);
$tpl = new sTemplate();
$tpl->setTemplate("merchant_2.tpl");


$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
			  $tpl->assign("description", $metaDescription);
			  $tpl->assign("keywords", $metaKeywords);
			  $tpl->assign("category_cur", "-1");
			  $tpl->assign("merchant_cur", $id);
			  $tpl->assign("coupon_cur", "-1");
			  //$tpl->assign("navigation_path", getNavigation(array($this->get("Name")."优惠券、折扣券、购物券" => $merchantCouponURL)));
			 // $tpl->assign("navigation_path", getNavigation(array($this->get("Headline") => $merchantCouponURL)));

			  //$tpl->assign("RESOURCE_INCLUDE", $this->StoreForShare["resource_include"]);
			  //$tpl->assign("newCoupon", $this->StoreForShare["newcoupon_include"]);
			  //$tpl->assign("hotCoupon", $this->StoreForShare["hotcoupon_include"]);
			  //$tpl->assign("category", $this->StoreForShare["categoryForShow"]);

			  $tpl->assign("merchantLogo", $merLogPath);
			  //$tpl->assign("merchantName", $this->get("Name"));
			  //if($this->get("Descript")==""){
				//$tpl->assign("merchantDescript", $this->get("DescriptMore"));
			  //}else{
				//$tpl->assign("merchantDescript", $this->get("Descript")."<br /><br />".$this->get("DescriptMore"));
			  //}
			  $tpl->assign("merchantURL", $merchantURL);
			  $tpl->assign("couponlist", $couponInfo);
			  $tpl->assign("specWord", $specWord);

$merchantCouponURL = Utilities::getURL("merchant", array("NameURL" => $merchantinfo['NameURL']));
$merchant = new Merchant($id);
$spinfo = $merchant->getSpInfo($id);

$addressarray = $merchant->getMerchantAddress($id,"21");
//print_r($addressarray);

$tpl->assign("navigation_path", getNavigation(array($merchant->get("Headline") => $merchantCouponURL)));
$tpl->assign("merchantLogo", $merLogPath);
$tpl->assign("merchantName", $merchantinfo['Name']);
if($merchantinfo['Descript']==""){
	$tpl->assign("merchantDescript", $merchantinfo['DescriptMore']);
}else{
	$tpl->assign("merchantDescript", $merchantinfo['Descript']."<br /><br />".$merchantinfo['DescriptMore']);
}
if(strlen($merchantinfo['URL']) > 0) {
    $merchantURL = Tracking_Uri::build(array(
        Tracking_Uri::BUILD_TYPE    => 'coupon',
        Tracking_Uri::MERCHANT_ID   => $id,
    ));
} else {
	$merchantURL = "";
}


$tpl->assign("addressarray", $addressarray);
$tpl->assign("spinfo", $spinfo);
$tpl->assign("merchantURL", $merchantURL);
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("category", $categoryForShow);
$tpl->displayTemplate();
?>
