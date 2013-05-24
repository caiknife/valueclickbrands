<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/dao/class.UserScoreDao.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

if(isset($_GET["cid"]) == false || $_GET["cid"] == '') {
	include_once(__INCLUDE_ROOT."/scripts/notfound.php");
    exit;
}


//$newsbbs = array("72"=>"6","70"=>"66","66"=>"54","68"=>"55","76"=>"62","97"=>"59","63"=>"56","65"=>"60","98"=>"57","94"=>"58","86"=>"70","62"=>"","96"=>"64","77"=>"7","75"=>"67","93"=>"68","99"=>"71");
$newsinfo = array("72"=>"17","70"=>"18","66"=>"19","68"=>"20","76"=>"21","97"=>"22","63"=>"23","65"=>"24","98"=>"25","94"=>"26","86"=>"27","62"=>"28","96"=>"29","77"=>"30","75"=>"31","93"=>"32","99"=>"51");


$couponid = $_GET['cid'];

$tpl = new sTemplate();
$tpl->setTemplate("new/detail.htm");

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);


//coupon info
$oCoupon = new Coupon($couponid);
$couponRow = $oCoupon->getCouponInfo();
if(empty($couponRow)){
	include_once(__INCLUDE_ROOT."/scripts/notfound.php");
    exit;
}

$oUserScoreDao = new UserScoreDao($couponRow['authorid']);
$oUserScoreDao->addScore("REFRESH",$couponid,"coupon","","","","");

if($couponRow['ExpireDate']<getDateTime("Y-m-d") && $couponRow['ExpireDate']!=='0000-00-00'){
	$tpl->assign("isExpire",1);
}else{
	$tpl->assign("isExpire",0);
}


if(!empty($couponRow['Merchant_'])){
	$oMerchant = new Merchant($couponRow['Merchant_']);
	$merchantmore = $oMerchant->getMerchantMore($couponRow['Merchant_']);
}

$oCategory = new Category($couponRow['Category_']);
$categoryList = $oCategory->getCategoryList("SitemapPriority");


$categoryInfo = $oCategory->CategoryInfo;
//print_r($categoryInfo);

for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}





if($couponRow["ImageDownload"] == 1) {
	$couponRow["ImageURL"] = Utilities::getImageURL($couponRow["Coupon_"]);

} else {
	$couponRow["ImageURL"] = "";
}
if($couponRow["Merchant_"] == 0) {
	$couponRow["MerchantName"] = "其他商家";
	$couponRow["MerchantURL"] = "";
} else {
	if($couponRow["Mshow"] == 1) {
		$couponRow["MerchantURL"] = Utilities::getURL("merchant", array("NameURL" => $couponRow["MerchantNameURL"]));
	} else {
		$couponRow["MerchantURL"] = "";
	}
}

$couponRow["tagname"] = Utilities::getTagSrc($couponRow["tagname"]);


//为酷棒增加
if(strpos($couponRow["tagname"], "酷棒")){
	$couponRow["tagname"] .= "&nbsp;&nbsp;&nbsp;<a href=\"http://www.kubang.net/coupon_list.asp?news_type=\" target=_blank rel=nofollow>更多酷棒优惠券</a>";
}

$couponRow["Start"] = ($couponRow["StartDate"] == "0000-00-00"?$couponRow["AddDate"]:$couponRow["StartDate"]);
$couponRow["End"] = ($couponRow["ExpireDate"] == "0000-00-00"?"<span class=\"red\">优惠进行中</span>":$couponRow["ExpireDate"]);
//meta
if($couponRow["City"]==""){
	$metaTitle = "【".$couponRow["Descript"] . "】 - " . $couponRow["MerchantName"] . "优惠券";
}else{
	$metaTitle = "【".$couponRow["Descript"] ."】 -(". $couponRow["City"].")". $couponRow["MerchantName"] . "优惠券";
}
$metaDescription = $couponRow["MerchantName"] . "最新推出" . $couponRow["Descript"] . "优惠券，您也可以查看" .
                   $couponRow["MerchantName"] . "其它更多实用优惠券，打折信息，如果想看频道优惠券，请点击" .$categoryInfo["Name"]."。";
$metaKeywords = $couponRow["MerchantName"] . "优惠券," . $couponRow["MerchantName"] .
                "电子优惠券,".$categoryInfo["Name"].",".$categoryInfo["Name"]."优惠券";


$categoryid =  $categoryInfo["Category_"];
$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($categoryid);

$couponRow["outcouponUrl"] = Utilities::getURL("couponUnion", array("Category" => $categoryid,
				                        "Coupon_" => $couponRow["Coupon_"]));

if($couponRow["ImageDownload"]=="1"){
	$couponRow["HasImage"]=1;
	$couponRow["ImageURL"] = Utilities::getMiddleImageURL($couponRow["Coupon_"]);
	//echo $couponRow["ImageURL"];
}else{
	$couponRow["HasImage"]=0;
}


$phpwind = new PHPWIND();

$phpwind->addcouponclick($couponid);

$r = $phpwind->gethotrealbbs();
$tpl->assign("hotbbs", $r);
//remove by thomas(2009/08/19)
//$r = $phpwind->gethotbbs($newsinfo[$categoryid]);
//$tpl->assign("hotinfo", $r);
//print_r($r);

$indexlist = $phpwind->getBBSNotify(50);
$tpl->assign('indexlist',$indexlist);

if($couponRow['replies']!=0){
	$re = $phpwind->getreplies($_GET['cid']);


	foreach ($re as $key=>$value){
		$re[$key]['postdate'] = get_date($value['postdate']);
	//echo $value['postdate'];
	}

	$pageCount = ceil(count($re)/ 10);
	$pageString = $phpwind->getNewPageStr("1",$pageCount);
	if($pageCount==0){
		$pageString="";
	}
	$tpl->assign("pageString", $pageString);
	$tpl->assign("pageAll", $pageCount);
	//echo $pageString;
}

//user login return url
$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);

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




$infoarray = array("1"=>"电子优惠券(卷)下载打印","2"=>"打折优惠,节假日降价优惠","3"=>"打折优惠,购物券","4"=>"优惠促销装,试用装","5"=>"优惠套票,家庭票","6"=>"购物券,打折降价","7"=>"降价促销,打折优惠","8"=>"购物券,打折优惠","9"=>"购物券,打折降价活动","10"=>"打折优惠","11"=>"购物券,促销打折","12"=>"打折优惠,降价促销活动");
$titleinfomore = array("72"=>"1","70"=>"2","66"=>"3","68"=>"4","76"=>"5","97"=>"6","63"=>"7","65"=>"9","98"=>"9","94"=>"10","86"=>"8","62"=>"6","96"=>"11","77"=>"7","75"=>"7","93"=>"6","99"=>"8","95"=>"12");

$tpl->assign("titleinfomore", $infoarray[$titleinfomore[$categoryid]]);


$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);
$tpl->assign("re", $re);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("navigation_path", getNavigation(array($categoryName => "")));
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
//$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("category", $categoryForShow);
//print_r($couponRow);
$tpl->assign("couponRow", $couponRow);
//$tpl->assign("newCouponlist", $newCouponFinal);
$tpl->assign("categoryName", $categoryInfo["Name"]);
$tpl->assign("cid", $categoryInfo['Category_']);
$tpl->assign("NameURL",$categoryInfo["NameURL"]);

$tpl->assign("merchantmore", $merchantmore);

$tpl->assign("merCouponCount", $merchantCouponCount);
$tpl->assign("allhotcategorycouponlist", $hotcategorycouponlist);
//print_r($hotcategorycouponlist);


//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

//add google ads by thomas 07/15/09
//$params = array();
//$adsWords = new AdWordsDao($couponRow['Descript'], 8);
//$adsResult = $adsWords -> dispatch($params);

$splitCountArr = array(3, 5);
$baiduParams = array('splitCountArr' => array(-3, -5));
$adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $couponRow['Descript'], "IsHighlight" => true);
$adsResult = AdWordsDao::getAdsScript($adsParams, $baiduParams);

$tpl->assign("adsResult", $adsResult);

$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);

if(substr($_SERVER['REQUEST_URI'],-3)=="tui"){
	$tpl->assign("tui",1);
}

$tpl->displayTemplate();
?>
