<?php

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT . "lib/util/class.FileDistribute.php");


if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
	$ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}

$officeIPs = array("66.161.95.134"=>1, "203.156.246.2"=>1);
if($ip != '127.0.0.1' && $ip != '10.40.4.11' && strpos($ip, '192.168.') !== 0 && !isset($officeIPs[$ip])) {
	//logFatal("not permission visit(IP:$ip).");
	die("Error.");
}

$dirName = __SE_CACHE_DIR.date("ymd", strtotime(date("y-m-d"))-3600*24);
removeDirectory($dirName);

$dirName = __SE_CACHE_DIR.date("ymd");
removeDirectory($dirName);

echo "DONE!";

function removeDirectory($dirName) {
	if(!is_dir($dirName)) {
		die("Error.");
		return false;
	}
	$handle = @opendir($dirName);
	while(($file = @readdir($handle)) !== false){
		if($file != '.' && $file != '..'){
			$dir = $dirName . '/' . $file;
			is_dir($dir) ? removeDirectory($dir) : @unlink($dir);
		}
	}
	closedir($handle);
	return rmdir($dirName) ;
}


?>