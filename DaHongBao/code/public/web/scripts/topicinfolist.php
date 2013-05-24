<?php

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
include_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Search.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
include_once(__INCLUDE_ROOT."lib/classes/class.topic.php");
include_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$winduser = P_GetCookie("winduser");


$topicdetailid = $_GET['id'];

$topic = new Topic();
$phpwind = new PHPWIND();
$topiclist = $topic->getTopicContentList($topicdetailid);



$contentid = $topiclist['contentid'];

$contentarray = $phpwind->gettopiclist($contentid);
$topicrow = $topic->getTopicRow($topiclist['topicid']);
//print_r($contentarray);

$oPage = new Page();

$oPage->find("HOTCOUPON_INCLUDE_IN");
$hotcouponin = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

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
$tpl->setTemplate("new/topicinfolist.htm");




$listcountall = count($contentarray);


//page
$pageCount = ceil($listcountall/ 30);
$pageString = $phpwind->getNewslistPageStr("1",$pageCount,$oCategory->get("NameURL"),$cid);
if($pageCount==0){
	$pageString="";
}
$tpl->assign("pageString", $pageString);
$tpl->assign("pageAll", $pageCount);


foreach ($contentarray as $key=>$value){
	$a = explode(" ",get_date($value['postdate']));
	$contentarray[$key]['postdate'] = $a[0];
	//echo $value['postdate'];
}

//print_r($contentarray);



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

$realinfolist = $phpwind->gethotrealinfo();
//print_r($realinfolist);
$tpl->assign("hotinfo", $realinfolist);


$r = $phpwind->gethotrealbbs();
$tpl->assign("hotbbs", $r);


$tpl->assign('hotkeywords',$hotkeywords);
$tpl->assign('indexlist',$indexlist);
$tpl->assign('list',$contentarray);
$tpl->assign("NameURL", $oCategory->get("NameURL"));
$tpl->assign("topicrow", $topicrow);
$tpl->assign("allhotcategorycouponlist", $hotcouponin);
$tpl->assign("category", $categoryForShow);
$tpl->assign("coupcat", $coupcat);
$tpl->assign("hotcoupon_include",$hotcoupon_include);
$tpl->assign("newdetail",$a);
$tpl->assign("forumreview",$b);
$tpl->assign("topiclist",$topiclist);

//$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21
$cityname = $phpwind->getNowCityName($cityid);  //get city name by city id
$tpl->assign('nowcityname',$cityname);
$citylist = $phpwind->getCityList();   //city list for city select
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();

?>