<?php
require_once("../etc/const.inc.php");
require_once(FRONT_END_ROOT . "lib/functions/func.Common.php");

//WARN: check remote IP
$ip = Utilities::onlineIP();
$officeIPs = array("66.161.95.134"=>1, "203.156.246.2"=>1);
if($ip != '127.0.0.1' && $ip != '10.40.4.11' && strpos($ip, '192.168.') !== 0 && !isset($officeIPs[$ip])) {
	logFatal("not permission visit(IP:$ip).");
	die("Error.");
}

set_time_limit(10 * 60);

$fileVersion = strtotime("2011-01-07"); //从生产环境下载“bbsdata”的日期

$srcDir = __ROOT_PATH."etc/config/bbsdata/data/";
$dstDir = __SETTING_FULLPATH."bbsdata/data/";

function copyOneDir($srcDir, $dstDir) {
	global $fileVersion;
	if( is_dir($srcDir) && $handle = opendir($srcDir) ) {
		while( false != ($file = readdir($handle)) ) {
			if(substr($file, 0, 1) == '.') {
				continue;
			}
			$srcFile = $srcDir.$file;
			$dstFile = $dstDir.$file;
			if(is_dir($srcFile)) {
				copyOneDir($srcFile."/", $dstFile."/");
			} else if(file_exists($dstFile) == false
				|| filemtime($dstFile) < $fileVersion) {
				if(file_exists($dstDir) == false) {
					mkdir($dstDir, 0777, true);
				}
				copy($srcFile, $dstFile);
				print("copy {$srcFile} to {$dstDir}.\n<BR/>");
			}
		}
		closedir($handle);
	}
}

copyOneDir($srcDir, $dstDir);
print("DONE\n<BR/>");