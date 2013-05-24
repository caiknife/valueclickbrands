<?php
/*
 * Created on 2010-08-085
 * SyncBlockAdSence.php
 * -------------------------
 * @author thomas_fu
 */
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT . "lib/functions/func.Common.php");
set_time_limit(0);

//WARN: check remote IP
$ip = Utilities::onlineIP();
$officeIPs = array("66.161.95.134"=>1, "203.156.246.2"=>1);
if($ip != '127.0.0.1' && $ip != '10.40.4.11' && strpos($ip, '192.168.') !== 0 && !isset($officeIPs[$ip])) {
	logFatal("not permission visit(IP:$ip).");
	die("Error.");
}

BlockAdSenceDao::updateBlockAdSence();
FileDistribute::commit();
echo "DONE!";
?>