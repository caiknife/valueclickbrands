<?php
/*
 * Created on 2007-4-23
 * c2c.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: stp.php,v 1.1 2013/04/15 10:58:07 rock Exp $
 * @link       http://www.smarter.com/
 */

require_once("../etc/const.inc.php");

$loadPos    = $_GET['loadPos'];
$movePos    = $_GET['movePos'];
$actionTime = $_GET['actionTime'];
$screenSize = $_GET['screenSize'];
$url        = $_GET['url'];
$userID     = 0;
$sessionID  = $_COOKIE['TRACKING_USER_SESSION'];
$pageOrder  = $_COOKIE['TRACKING_PAGE_VISIT_ORDER'];
$visitTime  = getDateTime("Y-m-d H:i:s");

$path = __FILE_FULLPATH."js_stats/";
if(!is_dir($path)) {
	exit;
}

$filename = "s_".$_ENV['HOSTNAME']."_".getDateTime("Ymd").".csv";
$record = '';
$split = "\t";
if(!file_exists($path.$filename)) {
	$record = "UserID\tSessionID\tRequestURI\tPageOrder\tLoadingPosition\t".
		      "MovedPosition\tScreenSize\tActionTime\tVisitTime\r\n";
}
$record .=$userID.$split.$sessionID.$split.$url.$split.$pageOrder.
          $split.$loadPos.$split.$movePos.$split.$screenSize.
          $split.$actionTime.$split.$visitTime."\r\n";
$handle = fopen($path.$filename,"a+");
if($handle) {
	if(flock($handle, LOCK_EX)) {
	    fwrite($handle,$record);
		flock($handle, LOCK_UN);
	}
	fclose($handle);
}

?>