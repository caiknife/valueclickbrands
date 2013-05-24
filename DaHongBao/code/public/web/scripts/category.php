<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

//cache
require_once "Cache/Lite.php";
$options = array('cacheDir' => __FILE_FULLPATH.'cache/', 'lifeTime' => 2 * 60 * 60, 'pearErrorMode' => CACHE_LITE_ERROR_DIE);
$cache = new Cache_Lite($options);

//if category id is empty
if (empty($_GET["cid"])) {
	include_once(__INCLUDE_ROOT."/scripts/notfound.php");
	exit;
}
$oCategory = new Category($_GET["cid"]);
if (empty($oCategory->CategoryInfo)) {
	include_once(__INCLUDE_ROOT."/scripts/notfound.php");
	exit;
}

if (!empty($_GET['__xparam'])) {
	$xarray = array ();
	$__xParam = explode("--", $_GET['__xparam']);
	foreach ($__xParam as $key => $value) {
		$te = explode("-", $value);
		$k = $te[0];
		$v = $te[1];
		$xarray[$k] = $v;
	}
}

$pageid = $xarray['Pg']; //声明pageid变量，初始化
if (empty($pageid)) {
	$pageid = 1;
}

if ($pageid == 1) {
	$timeurl = "Ca-".$oCategory->CategoryInfo['NameURL']."--Ci-".$oCategory->CategoryInfo['Category_']."--sortby-time.html";
	$defaulturl = "Ca-".$oCategory->CategoryInfo['NameURL']."--Ci-".$oCategory->CategoryInfo['Category_'].".html";
} else {
	$timeurl = "Ca-".$oCategory->CategoryInfo['NameURL']."--Ci-".$oCategory->CategoryInfo['Category_']."--sortby-time--Pg-".$xarray['Pg'].".html";
	$defaulturl = "Ca-".$oCategory->CategoryInfo['NameURL']."--Ci-".$oCategory->CategoryInfo['Category_']."--Pg-".$xarray['Pg'].".html";
}

if ($xarray['sortby'] == 'time') { //声明sort变量。初始化
	$sort = 'time';
	$sortstring = "( <B>按发布时间排列</B> | <a href=".$defaulturl.">按投票数排列</a> )";
} else {
	$sort = NULL;
	$sortstring = "( <a href=".$timeurl.">按发布时间排列</a> | <b>按投票数排列</b> )";
}

//$newsbbs = array("72"=>"6","70"=>"66","66"=>"54","68"=>"55","76"=>"62","97"=>"59","63"=>"56","65"=>"60","98"=>"57","94"=>"58","86"=>"70","62"=>"","96"=>"64","77"=>"7","75"=>"67","93"=>"68","99"=>"71");
$newsinfo = array("72" => "17", "70" => "18", "66" => "19", "68" => "20", "76" => "21", "97" => "22", "63" => "23", "65" => "24", "98" => "25", "94" => "26", "86" => "27", "62" => "28", "96" => "29", "77" => "30", "75" => "31", "93" => "32", "99" => "51");

//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$oPage = new Page();
$yqlj = "YQLJ_".$_GET['cid'];
$oPage->find($yqlj);
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";


$categoryList = $oCategory->getCategoryList("SitemapPriority");
for ($j = 0; $j < count($categoryList); $j ++) {
	$categoryForShow[$j]["category_url"] = Utilities :: getURL("category", array ("NameURL" => $categoryList[$j]["NameURL"], "Cid" => $categoryList[$j]["Category_"], "Page" => 1));
	$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}


//get hotcategorycouponlist
$hotcouponcacheid = "category_hotcategorycouponlist".$_GET["cid"];
if ($hotcategorycouponlist = $cache->get($hotcouponcacheid)) {
	$hotcategorycouponlist = unserialize($hotcategorycouponlist); //use cache
} else {
	$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($_GET["cid"]);
	$re = $cache->save(serialize($hotcategorycouponlist), $hotcouponcacheid);
}

//get coupon count by category
$allCategoryCouponCount = $oCategory->getEricCategorylistCount($_GET['cid'], $cityarray['cityid']);
$pageCount = ceil($allCategoryCouponCount / 10);

//get coupon by category , use cache if avaliable
$cachecategoryid = "category_array".$_GET['cid']."page".$pageid."city".$cityarray['cityid'].$sort;
if ($allCategoryCoupon = $cache->get($cachecategoryid)) {
	$allCategoryCoupon = unserialize($allCategoryCoupon); //use cache
} else {
	$allCategoryCoupon = $oCategory->getEricCategorylist($_GET['cid'], $pageid, $cityarray['cityid'], $sort);
	$cache->save(serialize($allCategoryCoupon), $cachecategoryid);
}

$pageString = $oCategory->getNewPageStr($pageid, $pageCount, $sort);
if ($pageCount == 0) {
	$pageString = "";
}

$merchant_name = "";
$allMerchantInfo = null;
//print_r($allCategoryCoupon);
for ($k = 0; $k < 10; $k ++) {
	$current = $k;
	if (isset ($allCategoryCoupon[$current])) {

		$infoTmp["merName"] = $allCategoryCoupon[$current]["Name"];
		if ($allCategoryCoupon[$current]["isFree"] == 0) {
			$infoTmp["couponUrl"] = Utilities :: getURL("couponUnion", array("Category" => $_GET['cid'], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
			$infoTmp["printUrl"] = Utilities :: getURL("couponUnion", array("Category" => $_GET['cid'], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
		} else {
			$infoTmp["couponUrl"] = Utilities :: getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
			if ($allCategoryCoupon[$current]["ImageDownload"] == 1) {
				$printdetail = Utilities :: getURL("couponPrint", array("Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
				$infoTmp["printUrl"] = "/print.php?url=".$printdetail;
			} else {
				$infoTmp["printUrl"] = Utilities :: getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
			}
		}

		$infoTmp["detailUrl"] = Utilities :: getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
		$infoTmp["couponTitle"] = Utilities :: cutString($allCategoryCoupon[$current]["Descript"], 40);
		$infoTmp["NameURL"] = $allCategoryCoupon[$current]["NameURL"];
		$infoTmp["Merchant_"] = $allCategoryCoupon[$current]["Merchant_"];
		if ($allCategoryCoupon[$current]["ExpireDate"] == "0000-00-00") {
			$infoTmp["couponStatus"] = "永久有效";
		} else {
			$infoTmp["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
		}

		$infoTmp["Coupon_"] = $allCategoryCoupon[$current]["Coupon_"];
		$infoTmp["name1"] = $allCategoryCoupon[$current]["name1"];
		$infoTmp["replies"] = $allCategoryCoupon[$current]["replies"];
		$infoTmp["City"] = Utilities :: getCity($allCategoryCoupon[$current]["City"]);
		$infoTmp["Hasmap"] = $allCategoryCoupon[$current]["Hasmap"];
		$infoTmp["digest"] = $allCategoryCoupon[$current]["digest"];
		$infoTmp["tagname"] = Utilities :: getTagSrc($allCategoryCoupon[$current]["tagname"]);
		$infoTmp["CouponType"] = $allCategoryCoupon[$current]["CouponType"];
		$infoTmp["author"] = trim($allCategoryCoupon[$current]["author"]);
		$infoTmp["isExpire"] = $allCategoryCoupon[$current]["isExpire"];
		$infoTmp["authorid"] = $allCategoryCoupon[$current]["authorid"];
		$infoTmp["StartDate"] = $allCategoryCoupon[$current]["StartDate"];

		$t = explode("-",$infoTmp["StartDate"]);
		$tomorrow = mktime(0,0,0,$t[1],$t[2],$t[0]);
		$infoTmp["StartDate"] = date("n月j日",$tomorrow);

		if ($allCategoryCoupon[$current]["ImageDownload"] == "1") {
			$infoTmp["HasImage"] = 1;
			$infoTmp["ImageURL"] = __IMAGE_SRC.Utilities :: getSmallImageURL($allCategoryCoupon[$current]["Coupon_"]);
		} else {
			$infoTmp["HasImage"] = 0;
		}

		$infoTmp["Detail"] = strip_tags($allCategoryCoupon[$current]["Detail"]);
		$infoTmp["Detail"] = Utilities :: cutString($infoTmp["Detail"], 148);

		$allMerchantInfo[] = $infoTmp;
	} else {
		break;
	}
}

//add by menny June 10,2008 func-> get category top merchants
$merchantlistcacheid = "category_topcategorymerchantlist".$_GET["cid"];
if ($categoryMerchantList = $cache->get($merchantlistcacheid)) {
	$categoryMerchantList = unserialize($categoryMerchantList); //use cache
} else {
	$oMerchant = new Merchant();
    $categoryMerchantList = $oMerchant->getCategoryMerchantList($_GET["cid"]);
	$re = $cache->save(serialize($categoryMerchantList), $merchantlistcacheid);
}
//print_r($categoryMerchantList);

$tpl = new sTemplate();
$tpl->setTemplate("new/category.htm");
$tpl->assign("categoryMerchantList", $categoryMerchantList);  //栏目上商家列表
$tpl->assign("timeurl", $timeurl);
$tpl->assign("defaulturl", $defaulturl);
$tpl->assign("sortstring", $sortstring);


$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("category", $categoryForShow);
//print_r($allMerchantInfo);
$tpl->assign("couponList", $allMerchantInfo);

$tpl->assign("pageString", $pageString);

$tpl->assign("title", $oCategory->getMeta('MetaTitle'));
$tpl->assign("description", $oCategory->getMeta('MetaDescription'));
$tpl->assign("keywords", $oCategory->getMeta('MetaKeywords'));

$tpl->assign("allhotcategorycouponlist", $hotcategorycouponlist);
$tpl->assign('hotkeywords',$hotkeywords);
$tpl->assign("categoryName", $oCategory->get("Name"));
$tpl->assign("NameURL", $oCategory->get("NameURL"));

$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if (empty($winduser)) {
	$tpl->assign('islogon', 0);
} else {
	$user = explode("\t", $winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$tpl->assign("userinfo", $userinfo[0]);
	$tpl->assign('islogon', 1);
}

$url = empty($_GET['url']) ? ($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']) : $_GET['url'];
$tpl->assign('url', $url);

$cid = $_GET['cid'];
if ($cid == 95) {
	$tpl->assign("hashotbbs", '1');
	$tpl->assign("hashotinfo", '1');
} else {
	if ($r = $cache->get('category_hotbbs')) {
		$r = unserialize($r); //use cache
	} else {
		$hotallbbs = $phpwind->gethotrealbbs();
		$re = $cache->save(serialize($hotallbbs), 'category_hotbbs');
	}
	$tpl->assign("hotbbs", $r);

	/*move by menny temporary June 10,2008
	$infoid = "category_hotinfo".$cid;
	if ($r = $cache->get($infoid)) {
		$r = unserialize($r); //use cache
	} else {
		$r = $phpwind->gethotbbs($newsinfo[$cid]);
		$re = $cache->save(serialize($r), $infoid);
	}
	$tpl->assign("hotinfo", $r);
	*/
	$tpl->assign("hashotinfo",1);
}
//add google ads by thomas 07/15/09
//$params = array();
//$adsWords = new AdWordsDao($oCategory->get("Name"), 8);
//$adsResult = $adsWords -> dispatch($params);
//$tpl->assign("adsResult", $adsResult);
$splitCountArr = array(3, 5);
$baiduParams = array('splitCountArr' => array(-3, -5));
$adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $oCategory->get("Name"), "IsHighlight" => true);
$adsResult = AdWordsDao::getAdsScript($adsParams, $baiduParams);

$tpl->assign("adsResult", $adsResult);
$indexlist = $phpwind->getBBSNotify(50);
$tpl->assign("indexlist", $indexlist);

//$adlist = $phpwind->getad($_GET['cid']);
//$tpl->assign("adlist", $adlist);

$tpl->assign('__IMAGE_SRC', __IMAGE_SRC);
$tpl->assign('__ADHOST', __ADHOST);

$tpl->assign('nowcityname', $cityarray['cityname']);
$tpl->assign('citylist', $citylist);

$tpl->displayTemplate();
?>