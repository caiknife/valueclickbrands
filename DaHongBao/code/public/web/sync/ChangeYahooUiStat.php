<?php

require_once("../etc/const.inc.php");
//require_once(FRONT_END_ROOT . "lib/functions/func.Common.php");
require_once(__INCLUDE_ROOT . "lib/util/class.FileDistribute.php");
//WARN: check remote IP
//$ip = $_SERVER["REMOTE_ADDR"];
if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
	$ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$officeIPs = array("66.161.95.134"=>1, "203.156.246.2"=>1);
if($ip != '127.0.0.1' && $ip != '10.40.4.11' && strpos($ip, '192.168.') !== 0 && !isset($officeIPs[$ip])) {
	logFatal("not permission visit(IP:$ip).");
	die("Error.");
}

$file = __SETTING_FULLPATH.'switch/yahooUiSwitch.txt';

$switch = strtolower($_GET['switch']);
if($switch == 'on') {
	file_put_contents($file, '1');
	echo "Plugin Keyword Turn ON";

}
elseif($switch == 'off') {
	file_put_contents($file, '0');
	echo "Plugin Keyword Turn OFF";

}
else {
	echo file_get_contents($file);
}

FileDistribute::syncFile();

?>