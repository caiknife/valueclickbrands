<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
include_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Search.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
include_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$winduser = P_GetCookie("winduser");

$newsbbs = array("72"=>"6","70"=>"66","66"=>"54","68"=>"55","76"=>"62","97"=>"59","63"=>"56","65"=>"60","98"=>"57","94"=>"58","86"=>"70","62"=>"","96"=>"64","77"=>"7","75"=>"67","93"=>"68","99"=>"71");
$newsinfo = array("72"=>"17","70"=>"18","66"=>"19","68"=>"20","76"=>"21","97"=>"22","63"=>"23","65"=>"24","98"=>"25","94"=>"26","86"=>"27","62"=>"28","96"=>"29","77"=>"30","75"=>"31","93"=>"32","99"=>"51");


$cid = $_GET['cid'];


$coupcat = array_search($cid , $newsinfo);

if(empty($coupcat) && !empty($cid)){
	redirect301("/bbs/thread.php?fid=".$cid);
   	exit;
}

$oPage = new Page();

$oPage->find("HOTCOUPON_INCLUDE_IN");
$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oCategory = new Category($coupcat);
$categoryList = $oCategory->getCategoryList("SitemapPriority");

$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($coupcat);
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}



$tpl = new sTemplate();
$tpl->setTemplate("new/list.htm");




$phpwind = new PHPWIND();
$listcountall = $phpwind->getlistCountAll($_GET['cid']);

$list = $phpwind->getlist($_GET['cid'],$_GET['pg']);
//print_r($list);

if($_GET['pg']==""){
	$_GET['pg']=1;
}
//page
$pageCount = ceil($listcountall/ 30);
$pageString = $phpwind->getNewslistPageStr($_GET['pg'],$pageCount,$oCategory->get("NameURL"),$cid);
if($pageCount==0){
	$pageString="";
}
$tpl->assign("pageString", $pageString);
$tpl->assign("pageAll", $pageCount);


foreach ($list as $key=>$value){
	$a = explode(" ",get_date($value['postdate']));
	$list[$key]['postdate'] = $a[0];
	//echo $value['postdate'];
}



function cvpic($url){
	$code="<img src=\"$url\" border=\"0\">";
	return $code;

}
function cvurl($http,$url='',$name=''){
	global $code_num,$code_htm;
	$code_num++;
	if(!$url){
		$url="<a href=\"http://www.$http\" target=\"_blank\">www.$http</a>";
	} elseif(!$name){
		$url="<a href=\"$http$url\" target=\"_blank\">$http$url</a>";
	} else{
		$url="<a href=\"$http$url\" target=\"_blank\">".str_replace('\\"','"',$name)."</a>";
	}
	return $url;
	$code_htm[0][$code_num]=$url;
	return "<\twind_code_$code_num\t>";
}

$a[0]['content'] = preg_replace("/\[img\](.+?)\[\/img\]/eis","cvpic('\\1')",$a[0]['content']);

$searcharray = array(
			"/\[url=(https?|ftp|gopher|news|telnet|mms|rtsp)([^\[\s]+?)\](.+?)\[\/url\]/eis",
			"/\[url\]www\.([^\[]+?)\[\/url\]/eis",
			"/\[url\](https?|ftp|gopher|news|telnet|mms|rtsp)([^\[]+?)\[\/url\]/eis"
);
$replacearray = array(
			"cvurl('\\1','\\2','\\3')",
			"cvurl('\\1')",
			"cvurl('\\1','\\2')",
);
$a[0]['content'] = preg_replace($searcharray,$replacearray,$a[0]['content']);
$a[0]['postdate'] = get_date($a[0]['postdate']);

/*
$read = $a[0];
	$attachper=1;
	if($read['ifshield']){ //µ¥ÌûÆÁ±Î
		$read['icon']    = '';
		$read['subject'] = $groupid=='3' ? shield('shield_title') : '';
		$groupid!='3' && $read['content'] = shield('shield_article');
	} elseif($read['groupid'] == 6 && $groupid != 3 && $db_shield){
		$read['subject'] = $read['icon'] = '';
		$read['content'] = shield('ban_article');
	} else{
		$read['ifwordsfb']!=$db_wordsfb && $read['content'] = wordsfb($read['content'],$read['ifwordsfb']);
		$read['ifconvert']==2 && $read['content'] = convert($read['content'],$db_windpost);
		if(strpos($read['content'],'[p:')!==false || strpos($read['content'],'[s:')!==false){
			$read['content'] = showface($read['content']);
		}
	}
$a[0] = $read;
*/

$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
$tpl->assign('url',$url);

if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign('userid',$user[0]);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}

$phpwind = new PHPWIND();


$indexlist = $phpwind->getforumlist("50,17,18,19,20");

$r = $phpwind->gethotrealbbs();
$tpl->assign("hotbbs", $r);

$r = $phpwind->gethotbbs($newsinfo[$coupcat]);
$tpl->assign("hotinfo", $r);

$tpl->assign('indexlist',$indexlist);
$tpl->assign('list',$list);
$tpl->assign("NameURL", $oCategory->get("NameURL"));
$tpl->assign("categoryName", $oCategory->get("Name"));
$tpl->assign("allhotcategorycouponlist", $hotcategorycouponlist);
$tpl->assign("category", $categoryForShow);
$tpl->assign("coupcat", $coupcat);
$tpl->assign("hotcoupon_include",$hotcoupon_include);
$tpl->assign("newdetail",$a);
$tpl->assign("forumreview",$b);
$tpl->assign('hotkeywords',$hotkeywords);


//$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21
$cityname = $phpwind->getNowCityName($cityid);  //get city name by city id
$tpl->assign('nowcityname',$cityname);
$citylist = $phpwind->getCityList();   //city list for city select
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();

?>