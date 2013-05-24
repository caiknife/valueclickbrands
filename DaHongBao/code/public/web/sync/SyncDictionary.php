<?php
/*
 * Created on 2006-7-20
 * SyncFrontEnd.php
 * -------------------------
 *
 *
 *
 * @author Fan Xu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: SyncDictionary.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 * @link       http://www.smarter.com/
 */
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

$syncDictionary = false;
if(isset($_REQUEST['syncId']) && is_array($_REQUEST['syncId'])) {
	foreach($_REQUEST['syncId'] as $id) {
		switch($id) {
		case "syncDictionary":
			$phpfile = __ROOT_PATH . "dba/creator.php";
			phpCall($phpfile);
			break;
		}
	}
	FileDistribute::commit();
}
?>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=GBK" />
<title>SyncFrontEnd</title>
</head>
<body>
<form name="form1" action="" method="POST">
<div>
	<b>更新数据</b>
	<br><br>
</div>
<div>
	<input type="checkbox" name="syncId[]" value="syncDictionary" />同步分词字典
	<br />
	<br />
	<input type="submit" name="submit1" value=" 更 新  ">
</div>
</form>
</body>
</html>