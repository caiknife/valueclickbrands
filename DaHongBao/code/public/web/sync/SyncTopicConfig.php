<?php

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT . "lib/dao/class.HomepageDao.php");
require_once(__INCLUDE_ROOT . "lib/util/class.FileDistribute.php");

//WARN: check remote IP
//$ip = $_SERVER["REMOTE_ADDR"];
//if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
//	$ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
//} else {
//	$ip = $_SERVER['REMOTE_ADDR'];
//}
$ip = Utilities::onlineIP();
$officeIPs = array("66.161.95.134"=>1, "203.156.246.2"=>1);
if($ip != '127.0.0.1' && $ip != '10.40.4.11' && strpos($ip, '192.168.') !== 0 && !isset($officeIPs[$ip])) {
	//logFatal("not permission visit(IP:$ip).");
	die("Error.");
}

if(isset($_REQUEST['switch'])  && $_REQUEST['switch'] == "execute") 
{
	if(HomepageDao::syncTopicConfig())
	{
		FileDistribute::syncFile();
		echo "ok";
	}
	else
	{
		echo "fail";
	}
}
else
{
	echo "Forbid";
}
?>