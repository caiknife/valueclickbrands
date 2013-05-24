<?php
require_once("../../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once('lib/oem.php');
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once (__INCLUDE_ROOT."lib/classes/class.City.php");

global $oem;


$id = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21

$nowcityid = $id;

//echo ('<h2>当前城市：'.$city->data->name.'</h2>');
//echo ('<span><a href="index.php">查看所有城市</a></span><span><a href="http://www.kijiji.cn/fabu" target="_blank">发布信息</a></span>');

//echo ('<h2>所有类目</h2>');
$categorys = $oem->getService('categoryChildren', array('id' => $id));

$tpl = new sTemplate();
$tpl->setTemplate("new/cityhome.htm");


$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$tpl->assign("cityId",$nowcityid);

$newarray = array();
$i=0;
foreach ($categorys as $key=>$o){
	//echo ('<h2><a href="listing.php?cityId='.$city->data->id.'&categoryId='.$o->id.'">'.$o->name.'</a></h2>');
	$o->name = iconv( "UTF-8", "gb2312" , $o->name);
	$newarray[$i]['name'] = $o->name;
	$newarray[$i]['count'] = $o->adsCount;
	$newarray[$i]['id'] = $o->id;
	$newarray[$i]['iconid'] = substr($o->id,-1);

	if($i>0){
		if($newarray[$i]['count']>$maxcount){
			$maxid = $o->id;
			$maxcount = $o->adsCount;
		}
	}else{
		$maxcount = $o->adsCount;
		$maxid = $o->id;
	}

	$categorysChildren = $oem->getService('categoryChildren', array('id' => $o->id));

	$n = array();
	$j=0;
	foreach ($categorysChildren as $b){
		$b->name = iconv( "UTF-8", "gb2312" , $b->name);
		$b->name = str_replace("@","",$b->name);
		$n[$j]['name'] = $b->name;
		$n[$j]['id'] = $b->id;
		$j++;
	}

	$newarray[$i]['child'] = $n;

	//print_r($categorysChildren);
	//$o->child = $categorysChildren;
	//foreach ($categorysChildren as $b){
		//echo (' <span> <a href="listing.php?cityId='.$city->data->id.'&categoryId='.$b->id.'">'.$b->name.'</a> </span> ');
	//}
	//echo $key;
	$i++;
}
//print_R($categorys);
//print_r($newarray);

//echo "<BR><BR>";
$popvalue = array_pop($newarray);

//print_r($popvalue);

array_unshift($newarray,$popvalue);

//$categorys = serialize($categorys);
//$categorys = iconv( "UTF-8", "gb2312" , $categorys);
//$categorys = unserialize($categorys);
//print_r($categorys);
//print_r($newarray);
$tpl->assign("fcategorys",$categorys);

$tpl->assign("categorys",$newarray);
$tpl->assign("nowcityid",$nowcityid);

$tpl->assign("maxid",$maxid);
//echo $maxid;


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


$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tpl->assign('nowcityname', $cityarray['cityname']);
$tpl->assign('citylist', $citylist);

$citys = $oem->getService('areaChildren',array('id' => 1));
foreach ($citys as $b){
	$b->name = iconv( "UTF-8", "gb2312" , $b->name);
}
$tpl->assign("city",$citys);

$tpl->displayTemplate();

?>