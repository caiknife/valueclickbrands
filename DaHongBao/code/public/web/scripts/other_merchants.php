<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$tpl = new sTemplate();
$tpl->setTemplate("new/merchant.htm");

//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

//Meta
$oPage->find("other_merchants");
$metaTitle = $oPage->getMeta("MetaTitle");
$metaDescription = $oPage->getMeta("MetaDescription");
$metaKeywords = $oPage->getMeta("MetaKeywors");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category", 
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$oMerchant = new Merchant();
$merchantList = $oMerchant->getAllMerchantList("NameURL");
$tcount = count($merchantList);
for($i=0;$i<count($merchantList); $i++) {
	if(trim($merchantList[$i]["Name"]) == "") continue;
	if($merchantList[$i]["isShow"] != 1) continue;
	$firstStr = strtoupper(substr($merchantList[$i]["NameURL"],0,1));
	switch($firstStr) {	
		case "0":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "1":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "2":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "3":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "4":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "5":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "6":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "7":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "8":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "9":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Num"][] = $InfoTmp;
			break;
		case "A":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["A"][] = $InfoTmp;
			break;
		case "B":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["B"][] = $InfoTmp;
			break;
		case "C":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["C"][] = $InfoTmp;
			break;
		case "D":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["D"][] = $InfoTmp;
			break;
		case "E":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["E"][] = $InfoTmp;
			break;
		case "F":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["F"][] = $InfoTmp;
			break;
		case "G":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["G"][] = $InfoTmp;
			break;
		case "H":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["H"][] = $InfoTmp;
			break;
		case "I":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["I"][] = $InfoTmp;
			break;
		case "J":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["J"][] = $InfoTmp;
			break;
		case "K":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["K"][] = $InfoTmp;
			break;
		case "L":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["L"][] = $InfoTmp;
			break;
		case "M":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["M"][] = $InfoTmp;
			break;
		case "N":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["N"][] = $InfoTmp;
			break;
		case "O":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["O"][] = $InfoTmp;
			break;
		case "P":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["P"][] = $InfoTmp;
			break;
		case "Q":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Q"][] = $InfoTmp;
			break;
		case "R":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["R"][] = $InfoTmp;
			break;
		case "S":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["S"][] = $InfoTmp;
			break;
		case "T":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["T"][] = $InfoTmp;
			break;
		case "U":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["U"][] = $InfoTmp;
			break;
		case "V":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["V"][] = $InfoTmp;
			break;
		case "W":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["W"][] = $InfoTmp;
			break;
		case "X":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["X"][] = $InfoTmp;
			break;
		case "Y":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Y"][] = $InfoTmp;
			break;
		case "Z":
			$InfoTmp["MerInfo"] = $merchantList[$i]["Name"] . "（" . $merchantList[$i]["CouponCount"]. "）";
			if($merchantList[$i]["isShow"] == 1) {
				$InfoTmp["MerURL"] = "/Me-".$merchantList[$i]["NameURL"]."--Mi-".$merchantList[$i]["Merchant_"].".html";
			} else {
				$InfoTmp["MerURL"] = "";
			}
			$InfoTmp["isFree"] = $merchantList[$i]["isFree"];
			if($InfoTmp["isFree"] == 0) {
				//$InfoTmp["MerInfo"] = "<font color='#aa0000'>" . $InfoTmp["MerInfo"] . "</font>";
			}
			$MerchantArr["Z"][] = $InfoTmp;
			break;
		}
}

$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}
$indexlist = $phpwind->getforumlist("50");
$tpl->assign("indexlist", $indexlist);

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array("所有的商家" => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("MerchantListOfNum", $MerchantArr["Num"]);
$tpl->assign("MerchantListOfA", $MerchantArr["A"]);
$tpl->assign("MerchantListOfB", $MerchantArr["B"]);
$tpl->assign("MerchantListOfC", $MerchantArr["C"]);
$tpl->assign("MerchantListOfD", $MerchantArr["D"]);
$tpl->assign("MerchantListOfE", $MerchantArr["E"]);
$tpl->assign("MerchantListOfF", $MerchantArr["F"]);
$tpl->assign("MerchantListOfG", $MerchantArr["G"]);
$tpl->assign("MerchantListOfH", $MerchantArr["H"]);
$tpl->assign("MerchantListOfI", $MerchantArr["I"]);
$tpl->assign("MerchantListOfJ", $MerchantArr["J"]);
$tpl->assign("MerchantListOfK", $MerchantArr["K"]);
$tpl->assign("MerchantListOfL", $MerchantArr["L"]);
$tpl->assign("MerchantListOfM", $MerchantArr["M"]);
$tpl->assign("MerchantListOfN", $MerchantArr["N"]);
$tpl->assign("MerchantListOfO", $MerchantArr["O"]);
$tpl->assign("MerchantListOfP", $MerchantArr["P"]);
$tpl->assign("MerchantListOfQ", $MerchantArr["Q"]);
$tpl->assign("MerchantListOfR", $MerchantArr["R"]);
$tpl->assign("MerchantListOfS", $MerchantArr["S"]);
$tpl->assign("MerchantListOfT", $MerchantArr["T"]);
$tpl->assign("MerchantListOfU", $MerchantArr["U"]);
$tpl->assign("MerchantListOfV", $MerchantArr["V"]);
$tpl->assign("MerchantListOfW", $MerchantArr["W"]);
$tpl->assign("MerchantListOfX", $MerchantArr["X"]);
$tpl->assign("MerchantListOfY", $MerchantArr["Y"]);
$tpl->assign("MerchantListOfZ", $MerchantArr["Z"]);

require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();

?>