<?php
$t_array=explode(' ',microtime());
$P_S_T=$t_array[0]+$t_array[1];
session_start();
require_once("../etc/const.inc.php");

//require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
//require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
//require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.topic.php");
//require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
//require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
//require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

//$t_array = explode(' ',microtime());
//$totaltime = number_format(($t_array[0]+$t_array[1]-$P_S_T),5);
//echo $totaltime."<BR>";

require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");

$topicid = $_GET['id'];



$oTopic = new Topic();
$topicrow = $oTopic->getTopic($topicid);


$topicidarray = $topicrow['bbsid'];
if(trim($topicidarray)!=""){
	$topicbbsarray = $oTopic->getTopicContentDetail($topicidarray);
}

$searchTextOri = $topicrow['adwords'];


$tpl = new sTemplate();

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$tpl->assign("topicbbsarray",$topicbbsarray);
$tpl->assign("adseKey",$searchTextOri);

if($topicid==6){
	$tpl->setTemplate("new/topic2.htm");

}else{

	$topiccontent = $oTopic->getTopicContent($topicid);

	if($topicrow['isactive']==0){
		exit();
	}


	foreach($topiccontent as $key=>$value){
		//echo $value['contentid'];
		$cidarray = "";
		$contentidarray = explode(",",$value['contentid']);
		for($jj=0;$jj<count($contentidarray) && $jj<3;$jj++){
			if($jj==2){
				$cidarray .= $contentidarray[$jj];
			}else{
				$cidarray .= $contentidarray[$jj].",";
			}
		}

		$array = $oTopic->getTopicContentDetail($value['contentid']);

		$carray = $oTopic->getTopicCouponDetail($value['couponid']);

		foreach($array as $key1=>$value1){
			$array[$key1]['content'] = Utilities::cutString($value1['content'],148);
		}

		foreach($carray as $key1=>$value1){
			$carray[$key1]['Descript'] = Utilities::cutString($value1['Descript'],32);
			if($carray[$key1]['isFree']==0){
				$carray[$key1]["couponUrl"] = Utilities::getURL("couponUnion", array("Category" => $carray[$key1]["Category_"],
											"Coupon_" => $carray[$key1]["Coupon_"]));

			}else{
				$carray[$key1]["couponUrl"] = Utilities::getURL("couponFree", array("NameURL" => $carray[$key1]["NameURL"],
													"Coupon_" => $carray[$key1]["Coupon_"]));
			}
		}

		//print_r($carray);
		$topiccontent[$key]['contentarray'] = $array;
		$topiccontent[$key]['couponarray'] = $carray;
	}


	$tpl->setTemplate($topicrow['templates']);

	$msg = $topicrow['topicdetail'];
	$msg = str_replace('&amp;','&',$msg);
	$msg = str_replace('&nbsp;',' ',$msg);
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("'",'&#39;',$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace("\t"," &nbsp; &nbsp;",$msg);
	$msg = str_replace("\r","<BR>",$msg);
	$msg = str_replace("   "," &nbsp; ",$msg);
	$topicrow['topicdetail'] = $msg;
	$tpl->assign("topicrow",$topicrow);
	$tpl->assign("topiccontent",$topiccontent);
}

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");
for($j=0; $j<count($categoryList); $j++) {
$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
	array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}


$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}

$indexlist = $phpwind->getforumlist("50");
$tpl->assign("indexlist", $indexlist);

$tpl->assign("category", $categoryForShow);

$tpl->assign("__IMAGE_SRC", __IMAGE_SRC);
//print_r($adsArr);

//$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21
$cityname = $phpwind->getNowCityName($cityid);  //get city name by city id
$tpl->assign('nowcityname',$cityname);
$citylist = $phpwind->getCityList();   //city list for city select
$tpl->assign('citylist',$citylist);

$tpl->displayTemplate();
?>
