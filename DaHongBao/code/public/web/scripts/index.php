<?php
$t_array=explode(' ',microtime());
$P_S_T=$t_array[0]+$t_array[1];
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__SETTING_FULLPATH."array/array_day_coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Customer.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

//search hot tag
require_once(__INCLUDE_ROOT. '/etc/tag_format_define.php'); // 百分比字符串格式定义数组
require_once(__INCLUDE_ROOT. '/lib/classes/class.hottag.php'); // 按照百分比格式化文字的类
require_once(__INCLUDE_ROOT. '/lib/array/array_tag.php'); // 数据库取出的HOT TAG数组

//获取主题图片列表
require_once(__INCLUDE_ROOT."lib/dao/class.HomepageDao.php");

//cache
require_once "Cache/Lite.php";
$options = array(
    'cacheDir' => __FILE_FULLPATH.'cache/',
    'lifeTime' => 1*60*60,
    'pearErrorMode' => CACHE_LITE_ERROR_DIE
);
$cache = new Cache_Lite($options);


$hottag = new HotTag();
$s = $hottag->show($define_format_array, $array_tag);
//echo $s;

$coupon = new Coupon();




$winduser = P_GetCookie("winduser");

//////////////////////////////

$tpl = new sTemplate();
$tpl->setTemplate("new/index.htm");

//register forward
$url = empty($_GET['url'])?base64_encode($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);


/////////////

if($_POST['addemail']=="true"){
	$oCustomer = new Customer();
	$a = $oCustomer->addemail($_POST['email']);
	redirect301(__LINK_ROOT."index.php");
	exit();
}


//共通
$oPage = new Page();
$oPage->find("RESOURCE_INCLUDE");
$resource_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("NEWCOUPON_INCLUDE");
$newcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE_INDEX");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTMERCHANT_INCLUDE_INDEX");
$hotmerchant_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$oPage->find("TJSJ_INDEX");
$tjsj = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("YQLJ_INDEX1");
$yqlj1 = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$oPage->find("YQLJ_INDEX2");
$yqlj2 = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$oPage->find("YQLJ_INDEX3");
$yqlj3 = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

//Meta
$oPage->find("homepage");
$metaTitle = $oPage->getMeta("MetaTitle");
$metaDescription = $oPage->getMeta("MetaDescription");
$metaKeywords = $oPage->getMeta("MetaKeywords");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$phpwind = new PHPWIND();



$indexpage = $oPage->getIndexPage();
$tpl->assign('indexpage',$indexpage);


if ($a = $cache->get('index_new72')) {
	$newcouponlist1 = unserialize($a);//use cache
} else {
	$newcouponlist1 = $coupon->getCouponNew(72);
	$re = $cache->save(serialize($newcouponlist1),'index_new72');
}
$tpl->assign('newcouponlist1',$newcouponlist1);

if ($a = $cache->get('index_new70')) {
	$newcouponlist2 = unserialize($a);//use cache
} else {
	$newcouponlist2 = $coupon->getCouponNew(70);
	$re = $cache->save(serialize($newcouponlist2),'index_new70');
}
$tpl->assign('newcouponlist2',$newcouponlist2);


if ($a = $cache->get('index_new68')) {
	$newcouponlist3 = unserialize($a);//use cache
} else {
	$newcouponlist3 = $coupon->getCouponNew(68);
	$re = $cache->save(serialize($newcouponlist3),'index_new68');
}
$tpl->assign('newcouponlist3',$newcouponlist3);

if ($a = $cache->get('index_new66')) {
	$newcouponlist4 = unserialize($a);//use cache
} else {
	$newcouponlist4 = $coupon->getCouponNew(66);
	$re = $cache->save(serialize($newcouponlist4),'index_new66');
}
$tpl->assign('newcouponlist4',$newcouponlist4);

//slow query
//$nowaddcoupon = $phpwind->gettodayaddcoupon();
$nowaddcoupon = getDateTime("d")-getDateTime("m")+30;
//echo $nowaddcoupon;
//$nowaddcoupon = substr($nowaddcoupon,0,2);




if ($hotallbbs = $cache->get('index_hotrealbbs')) {
	$hotallbbs = unserialize($hotallbbs);//use cache
} else {
	$hotallbbs = $phpwind->gethotrealbbs();
	$re = $cache->save(serialize($hotallbbs),'index_hotrealbbs');
}


foreach ($hotallbbs as $key=>$value){
	$hotallbbs[$key]['subject'] = Utilities::cutString($value['subject'],25);

}

if ($a = $cache->get('index_forumlist')) {
	$a = unserialize($a);//use cache
} else {
	$a = $phpwind->getindexforumlist("50,69");
	$re = $cache->save(serialize($a),'index_forumlist');
}

foreach ($a as $key=>$value){
	$a[$key][0]['content'] = Utilities::cutString($value[0]['content'],48);
	foreach ($value as $key1=>$value1){
		if($key=="50"){
			$a[$key][$key1]['subject'] = Utilities::cutString($value1['subject'],40);
		}else{
			$a[$key][$key1]['subject'] = Utilities::cutString($value1['subject'],30);
		}
	}
}
//print_r($b);


$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);

if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	//echo $winduser;
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}


$hotuser = $phpwind->gethotuser();
$tpl->assign("hotuser", $hotuser);
//print_r($hotuser);
$tpl->assign("hotallbbs", $hotallbbs);
//print_r($hotallbbs);
$tpl->assign("LINK_ROOT", __LINK_ROOT);
$tpl->assign("title", $metaTitle);

$tpl->assign("nowaddcoupon", $nowaddcoupon);
$tpl->assign("day", $day);
$tpl->assign("day2", $day2);
$tpl->assign("week", $week);
$tpl->assign("week2", $week2);
$tpl->assign("month", $month);
$tpl->assign("month2", $month2);
$tpl->assign("cnum", $cnum);
$tpl->assign("cadd", $cadd);
$tpl->assign("description", $metaDescription);
$tpl->assign("keywords", $metaKeywords);
$tpl->assign("category_cur","-1");
$tpl->assign("merchant_cur", "-1");
$tpl->assign("coupon_cur", "-1");
$tpl->assign("RESOURCE_INCLUDE", $resource_include);
$tpl->assign("newCoupon", $newcoupon_include);
$tpl->assign("hotCoupon", $hotcoupon_include);
$tpl->assign("category", $categoryForShow);
$tpl->assign("totalCoupon", $total);
$tpl->assign("todayCoupon", $totalAvailable);
$tpl->assign("cityid", $cityid);
$tpl->assign("cityname", $cityarray[$cityid]);
$tpl->assign('hottag',$s);
$tpl->assign('indexlist',$a);
$tpl->assign('tjsj',$tjsj);
$tpl->assign('yqlj1',$yqlj1);
$tpl->assign('yqlj2',$yqlj2);
$tpl->assign('yqlj3',$yqlj3);


$tpl->assign('__IMAGE_SRC',__IMAGE_SRC);


//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);





$tpl->assign('hotmerchant',$hotmerchant_include);
$tpl->assign('TopicConfig',HomepageDao::getTopicConfig('INDEX'));

$tpl->displayTemplate();
?>
