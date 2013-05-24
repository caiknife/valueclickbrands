<?php
require_once("../../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once (__INCLUDE_ROOT."lib/classes/class.City.php");

require_once (__INCLUDE_ROOT."lib/dao/class.BaixingDao.php");
require_once (__INCLUDE_ROOT."lib/util/class.CURL.php");

$tpl = new sTemplate();
$tpl->setTemplate("new/kjjshow.htm");


$id = $_GET['id'];
if (empty($id)){
	die('error id.');
}

$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tpl->assign('nowcityname', $cityarray['cityname']);
$tpl->assign('citylist', $citylist);

$baixingdao = new BaixingDao();
$baixingdetail = $baixingdao->getBaixingDetail($_COOKIE['cityid'],$id);

$tpl->assign('baixingdetail', $baixingdetail);
//print_r($baixingdetail);

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

$tpl->displayTemplate();

?>