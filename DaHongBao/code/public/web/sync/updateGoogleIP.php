<?php
/*
 * Created on 2009-3-18
 * updateGoogleIP.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: updateGoogleIP.php,v 1.1 2013/04/15 10:58:20 rock Exp $
 * @link       http://www.smarter.com/
 */

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT . "lib/functions/func.Common.php");

//WARN: check remote IP
$ip = Utilities::onlineIP();
if($ip != '127.0.0.1' && $ip != '10.40.4.11') {
	logFatal("not permission visit(IP:$ip).");
	die("Error.");
} else if(!empty($_REQUEST['X-Forwarded-For'])) {
	logFatal("fraud ip visit(IP:$ip).");
	die("Error.");
}

if($_GET['switch'] == 'setstatus') {
	if(!empty($_GET['google_ip'])) {
		$ips = explode(',', trim($_GET['google_ip']));
		GoogleDNSDao::storeGoogleIP($ips);
	}
	 
	if(!empty($_GET['google_dnslookup'])) {
		$dnsloopup = trim($_GET['google_dnslookup']);
		if($dnsloopup != "IP") $dnsloopup = "DOMAIN";
		SystemConfigDao::setGoogleDNSLookup($dnsloopup);
	}
	FileDistribute::commit();
	echo "\nDONE!\n".date('Y-m-d H:i:s');
} else {
	echo SystemConfigDao::getGoogleDNSLookup();
}

 
?>