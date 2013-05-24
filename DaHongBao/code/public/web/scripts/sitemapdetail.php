<?PHP
session_start();
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

if(isset($_GET["cid"]) == false || $_GET["cid"] == '') {
	//redirect301(__LINK_ROOT."notfound.php");
	//exit;
	include_once(__INCLUDE_ROOT."/scripts/notfound.php");
    exit;
}
//共通

$newsbbs = array("72"=>"6","70"=>"66","66"=>"54","68"=>"55","76"=>"62","97"=>"59","63"=>"56","65"=>"60","98"=>"57","94"=>"58","86"=>"70","62"=>"","96"=>"64","77"=>"7","75"=>"67","93"=>"68","99"=>"71");
$newsinfo = array("72"=>"17","70"=>"18","66"=>"19","68"=>"20","76"=>"21","97"=>"22","63"=>"23","65"=>"24","98"=>"25","94"=>"26","86"=>"27","62"=>"28","96"=>"29","77"=>"30","75"=>"31","93"=>"32","99"=>"51");

if(count($_SESSION['digestarray'])){
	$digestarray = $_SESSION['digestarray'];
}else{
	$digestarray = array();
}

require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$oPage = new Page();
$oPage->find("HOTMERCHANT_INCLUDE_IN");
$hotmerchantin = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE_IN");
$hotcouponin = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";


$oCoupon = new Coupon;

$i=$_GET['pageid'];
if(empty($i)){
	$i=1;
}


$oCategory = new Category($_GET["cid"]);
$categoryList = $oCategory->getCategoryList("SitemapPriority");

$t_array = explode(' ',microtime());
$totaltime = number_format(($t_array[0]+$t_array[1]-$P_S_T),5);
//echo $totaltime."<BR>";

$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($_GET["cid"]);


//print_r($categoryList);
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$allCategoryCoupon = $oCategory->getEricCategorylist($_GET['cid'],$i,$cityarray['cityid']);






$allCategoryCouponCount = $oCategory->getEricCategorylistCount($_GET['cid'],$cityid);
$pageCount = ceil($allCategoryCouponCount/ 10);

$metaTitle = $oCategory->getMeta('MetaTitle');
$metaDescription = $oCategory->getMeta('MetaDescription');
$metaKeywords = $oCategory->getMeta('MetaKeywords');
$pageString = $oCategory->getNewSitemapPageStr($i,$pageCount);
if($pageCount==0){
	$pageString="";
}
 $merchant_name = "";
			      $allMerchantInfo = null;
	  	          for($k=0;$k<10; $k++) {
				  	  $current = $k;
				  	  if(isset($allCategoryCoupon[$current])) {
                          if((strlen($allCategoryCoupon[$current]["Name"]) > 0) == false) {
                              $allCategoryCoupon[$current]["Name"] = "其他商家";
							  $allCategoryCoupon[$current]["NameURL"] = "merchant";
                          }
					      if($allCategoryCoupon[$current]["Name"] == $merchant_name) {
						  	  $infoTmp["merName"] = "" ;
						  } else {
						  	  $infoTmp["merName"] = $allCategoryCoupon[$current]["Name"];
						  }
						  $merchant_name = $allCategoryCoupon[$current]["Name"];
						  if(($allCategoryCoupon[$current]["isFree"] == 0)) {
							  $infoTmp["isFree"] = 0;
						      $infoTmp["couponUrl"] = $allCategoryCoupon[$current]["couponUrl"];
						  } else {
							  $infoTmp["isFree"] = 1;
						      $infoTmp["couponUrl"] = Utilities::getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"],
												"Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
						  }
						  $infoTmp["couponTitle"] = Utilities::cutString($allCategoryCoupon[$current]["Descript"],40);

						  //if($allCategoryCoupon[$current]["isShow"] == 1) {
						  //	  $infoTmp["merUrl"] = Utilities::getURL("merchant", array("NameURL" => //$allCategoryCoupon[$current]["NameURL"]));
						  //} else {
						  //	  $infoTmp["merUrl"] = "";
                          //}

						  $infoTmp["NameURL"] = $allCategoryCoupon[$current]["NameURL"];
						  $infoTmp["Merchant_"] = $allCategoryCoupon[$current]["Merchant_"];

						  if($allCategoryCoupon[$current]["ExpireDate"]=="0000-00-00"){
							$infoTmp["couponStatus"] = "永久有效";
						  }else{
							$infoTmp["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
						  }

						  if (in_array($allCategoryCoupon[$current]["Coupon_"], $digestarray)){
							$infoTmp["IsDigest"]=1;
						  }else{
							$infoTmp["IsDigest"]=0;
						  }
						  $infoTmp["Coupon_"] = $allCategoryCoupon[$current]["Coupon_"];
						  $infoTmp["name1"] = $allCategoryCoupon[$current]["name1"];
						  $infoTmp["replies"] = $allCategoryCoupon[$current]["replies"];
						  $infoTmp["City"] = Utilities::getCity($allCategoryCoupon[$current]["City"]);
						  $infoTmp["Hasmap"] = $allCategoryCoupon[$current]["Hasmap"];
						  $infoTmp["digest"] = $allCategoryCoupon[$current]["digest"];
						  $infoTmp["CouponType"] = $allCategoryCoupon[$current]["CouponType"];
						  //echo $allCategoryCoupon[$current]["author"];
						  $infoTmp["author"] = $allCategoryCoupon[$current]["author"];

							$infoTmp["isExpire"] = $allCategoryCoupon[$current]["isExpire"];

						  $infoTmp["authorid"] = $allCategoryCoupon[$current]["authorid"];
						  $infoTmp["StartDate"] = $allCategoryCoupon[$current]["StartDate"];
						  //echo $infoTmp["author"];
						  $infoTmp["Detail"] = Utilities::cutString($allCategoryCoupon[$current]["Detail"],148);
						  $infoTmp["ImageURL"] = Utilities::getImageURL($allCategoryCoupon[$current]["Coupon_"]);

						  $filename = __INCLUDE_ROOT.$infoTmp["ImageURL"];
						  //echo $filename;
						 if (file_exists($filename)) {
								$infoTmp["HasImage"]=1;
							} else {
								$infoTmp["HasImage"]=0;
							}
						  //echo $infoTmp["ImageURL"]."<BR>";
						  $allMerchantInfo[] = $infoTmp;
					  } else {
					  	  break;
					  }
				  }
				  $merName = "";
				  for($n=0; $n<count($allMerchantInfo); $n++) {
				  	  if($allMerchantInfo[$n]["merName"] != $merName && $allMerchantInfo[$n]["merName"] != "") {
					  	  $merName = $allMerchantInfo[$n]["merName"];
					  }
					  if($allMerchantInfo[$n]["couponUrl"] != "" || $allMerchantInfo[$n]["merUrl"] != "") {
					      $merNameArr[$allMerchantInfo[$n]["merName"]] = 1;
					  }
				  }
				  for($n=0; $n<count($allMerchantInfo); $n++) {
				  	  if($allMerchantInfo[$n]["merName"] != $merName && $allMerchantInfo[$n]["merName"] != "") {
					  	  $merName = $allMerchantInfo[$n]["merName"];
					  }
					  if($merNameArr[$merName] == 1) {
					  	  $allMerchantInfo[$n]["isAbled"] = 1;
					  } else {
					  	  $allMerchantInfo[$n]["isAbled"] = 0;
					  }
				  }



$tpl = new sTemplate();
$tpl->setTemplate("new/map2.htm");


$tpl->assign('hotkeywords',$hotkeywords);

$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("category", $categoryForShow);
//print_r($allMerchantInfo);
$tpl->assign("couponList",$allMerchantInfo);

$tpl->assign("pageString",$pageString);
$tpl->assign("cityarray",$cityarray);
$tpl->assign("nowcityid",$cityid);
$tpl->assign("title", $metaTitle);

$tpl->assign("allhotcategorycouponlist", $hotcategorycouponlist);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("navigation_path", getNavigation(array($oCategory->get("Name")."电子优惠券、购物折扣券" => "")));
$tpl->assign("categoryName", $oCategory->get("Name"));
$tpl->assign("NameURL", $oCategory->get("NameURL"));

$tpl->assign("cityname", $cityarray[$_COOKIE['cityid']]);

//$oPage->find("HOTCOUPON_INCLUDE_OTHER");
//$hotcouponother = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
//$tpl->assign("hotcouponother",$hotcouponother);


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

$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);

$cid = $_GET['cid'];
$r = $phpwind->gethotbbs($newsbbs[$cid]);
$tpl->assign("hotbbs", $r);

$r = $phpwind->gethotbbs($newsinfo[$cid]);
//print_r($r);
$tpl->assign("hotinfo", $r);

$indexlist = $phpwind->getforumlist("50");
$tpl->assign("indexlist", $indexlist);

$bbslist = $phpwind->gethotrealbbs();

$tpl->assign("bbslist", $bbslist);

$tpl->assign("hotcouponin", $hotcouponin);
$tpl->assign("hotmerchantin", $hotmerchantin);




$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();


?>