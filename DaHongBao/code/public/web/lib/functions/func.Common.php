<?php
/**
 * func.url.php
 *-------------------------
 *
 * the string translation functions related to url.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine 
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare 
 * the attributes of over one million products in the computer and consumer electronics categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then 
 * research the latest promotional and pricing information on products listed at a wide selection of 
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.  
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @author     Webber <webber_yao@mezimedia.com>
 * @oriauthor  Kevin <kevin@mezimedia.com>
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: func.Common.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       / Location: /
 * @deprecated File deprecated in Release 2.0.0
 */
 
 if(!defined("INC_FUNC_COMMON")) {
 	define("INC_FUNC_COMMON", "1");

define("__MODEL_FUNC_COMMON", "");
define("__MODEL_EMPTY", "");
define("__MODEL_EXCEPTION", "Exception");

if(defined("__PHP_CLI") == false) {
	define("__PHP_CLI", "php ");
}

/**
 * 获得当前时间
 * @return datetime Format:20060306_181010_023435
 */
function getDateTimeById() {
	//GMT
	$time = date("Ymd_His");
	//GMT+8
	//$time = date("Ymd_His", strtotime('+8 HOUR'));
	$time .= "_".substr(microtime(), 2, 6);
	return $time;
}

/**
 * 作日志并终止程序: 系统错误,用于输入的关键参数错误
 * @param message
 */
function logFatal($message, $model="", $level="FATAL") {
	writeLog("fatal.log", $message);
	echo "fatal.";
	exit;
}

/**
 * 错误日志: 系统错误,用在处理模块中被检测到
 * @param message
 */
function logError($message, $model="", $level="ERROR") {
	writeLog("error.log", $message);
}

/**
 * 警告日志: 数据错误
 * @param message
 */
function logWarn($message, $model="", $level="WARN") {
	if(defined("__LOG_LEVEL") && __LOG_LEVEL <= 3) { // 3: WARN
		writeLog("warn.log", $message);
	}
}

/**
 * 消息日志: 重要操作的日志
 * @param message
 */
function logInfo($message, $model="", $level="INFO") {
	if(defined("__LOG_LEVEL") && __LOG_LEVEL <= 2) { // 2: INFO
		writeLog("info.log", $message);
	}
}

/**
 * 调试(详细)日志
 * @param message
 */
function logDebug($message, $model="", $level="DEBUG") {
	if(defined("__LOG_LEVEL") && __LOG_LEVEL <= 1) { // 1: DEBUG
		writeLog("debug.log", $message);
	}
}

function writeLog($filename, $message) {
	$fp = fopen(__ROOT_LOGS_PATH.$filename, "a+");
	$pos = strpos($_SERVER['SCRIPT_URL'], "?");
	if($pos === false) {
		$pos = strpos($_SERVER['SCRIPT_URL'], "&");
	}
	if($pos === false) {
		$msg = $_SERVER['SCRIPT_URL']." - ".$message;
	} else {
		$prefix = substr($_SERVER['SCRIPT_URL'], 0, $pos);
		$postfix = substr($_SERVER['SCRIPT_URL'], $pos);
		$msg = $prefix." - $message; ".$postfix;
	}
	$msg = getDateTime() . " >>> " . $msg;
	fwrite($fp, "$msg\r\n");
	fclose($fp);
}

function formatMessage($message, $model, $level) {
	$msg = "";
	if($model != __MODEL_EMPTY) {
		switch($level) {
		case "FATAL":
			$color = "#FF0000";
		break;
		case "ERROR":
			$color = "#DD0000";
		break;
		case "WARN":
			$color = "#FF8000";
			$level .= " ";
		break;
		case "INFO":
			$color = "#007100";
			$level .= " ";
		break;
		case "DEBUG":
			$color = "#42F246";
		break;
		}
		$msg = "<font color='$color'><b>$level</b> ";
		$msg .= getDateTime() . " ". $model . ": </font>";
	}
	$msg .= print_r($message, true);
	$msg .= "<BR>\r\n";
	//$msg = "$msg";
	return $msg;
}

function phpCall($phpfile, $backgroupFlag=false, $outFile="") {
	$param = "";
	$pos = strpos($phpfile, "?");
	if($pos !== false) {
		$param = " \"".substr($phpfile, $pos+1)."\"";
		$phpfile = substr($phpfile, 0, $pos);
	}
	//取得路径
	$pos = strrpos($phpfile, "/");
	if($pos !== false) {
		$path = substr($phpfile, 0, $pos);
		//设置当前目录
		$ret = chdir($path);
		if($ret == false) {
			throw new Exception("can not change work dir [".$path."].");
		}
	}
	//"(".
	$cmd =  __PHP_CLI . $phpfile . $param;
	if($backgroupFlag) { //加载到后台执行
		if(empty($outFile)) {
			$outFile = "stdout.log";
		}
		$cmd .= " 1> $outFile 2>&1 &";
		//log..
		logDebug($cmd, __MODEL_FUNC_COMMON);
		$processId = sysCallByPipe($cmd, $path);
		return $processId;
	} else { //直接执行
		//log..
		logDebug($cmd, __MODEL_FUNC_COMMON);
		return system($cmd);
	}
}

function sysCall($cmd, $backgroupFlag=false) {
	if($backgroupFlag == true) {
//		$cmd .= " 1>stdout.txt 2>stderr.txt &";
	}
	//log..
	logInfo($cmd, __MODEL_FUNC_COMMON);
	$ret = system($cmd);
	return $ret;
}

function sysCallByPipe($cmd, $workDir="") {
	$ret = false;

	$descriptorspec = array(
	   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
	   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
	   2 => array("pipe", "w") // stderr is a file to write to
	);

	$cwd = './';
//	$env = array(
//		'SystemRoot' => $_ENV["SystemRoot"], 	//数据库连接需要此参数
//		'PATH' => $_ENV["PATH"], 
//		);
	$env = $_ENV;
	$pipes = array();

	$process = proc_open(__PHP_CLI, $descriptorspec, $pipes, $cwd, $env);

	if (is_resource($process)) {
		// $pipes now looks like this:
		// 0 => writeable handle connected to child stdin
		// 1 => readable handle connected to child stdout
		// Any error output will be appended to /tmp/error-output.txt
		$chdir = "";
		if(!empty($workDir)) {
			$chdir = "chdir('$workDir');";
		}
		fwrite($pipes[0], '<?php '.$chdir.' system(\''.$cmd.'\'); ?>');
		fclose($pipes[0]);

		$ret = stream_get_contents($pipes[1]);
		$err = stream_get_contents($pipes[2]);
		if(!empty($err)) {
			$ret .= "|" . $err;
		}
		fclose($pipes[1]);
		fclose($pipes[2]);

		// It is important that you close any pipes before calling
		// proc_close in order to avoid a deadlock
		$return_value = proc_close($process);

		//	   echo "command returned $return_value\n";
	}

	return $ret;

}

function parseRequestString($arg) {
	$ret = array();
	$param = split("&", $arg);
	foreach($param as $str) {
		//to name => value
		$tmp = split("=", $str, 2);
		$ret[$tmp[0]] = $tmp[1];
	}
	return $ret;
}

/**
 * 删除文件
 * @param pathfile
 * @return boolean
 */
function deleteFile($pathfile) {
	return @unlink($pathfile);
//	return true;	
}

/**
 * 移动文件
 * @param $srcFile
 * @param $dstFile
 * @return boolean
 */
function moveFile($srcFile, $dstFile, $mode = 0777, $destExistIngore=false) {
	if(file_exists($dstFile)) {
		if($destExistIngore == false) {
			return false;
		}
		$ret = deleteFile($dstFile);
		if($ret == false) {
			return false;
		}
	}
	$ret = @copy($srcFile, $dstFile);
	if($ret == false) {
		return false;
	}
	chmod($dstFile, $mode);
	return deleteFile($srcFile);
}

/**
 * 
 */
function sendCharset($charset, $contentType="text/html") {
	header("Content-type: $contentType; charset=$charset");
}

/**
 * 创建目录
 */
if (!function_exists(createDir)) {
  function createDir($dir, $mode = 0777) {
  	$ret = @mkdir($dir);
  	if($ret == false) {
  		return false;
  	}
  	//umask 
  	return @chmod($dir, $mode);
  }
}
/**
 * 刷新客户端
 */
function flushClient() {
	//del: $str = generateLargeString(" ", 10);
	//del: echo $str;
	flush();
}
/**
 * 生成放大2的N次方的字符串
 */
function generateLargeString($src, $N) {
	for(; $N>0; $N--) {
		$src .= $src;
	}
	return $src;
}

if (!function_exists("redirect301")) {
	function redirect301($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		exit();
	}
}

if (!function_exists(redirect302)) {
	function redirect302($url) {
		header("HTTP/1.1 302 Moved Temporarily");
		header("Location: $url");
		exit();
		//TrackingAPI::execStatHeader($url, '302');
	}
}

function isGetMethod() {
	if(isset($_ENV['REQUEST_METHOD']) && $_ENV['REQUEST_METHOD'] == "GET") {
		return true;
	}
	if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
		return true;
	}
	return false;
}

/**
 * @package     BugFree  
 * 
 * Sort an two-dimension array by some level two items use array_multisort() function.
 * sysSortArray($Array,"Key1","SORT_ASC","SORT_RETULAR","Key2"……)  
 * @author Chunsheng Wang <wwccss@263.net>
 * @param  array   $ArrayData   the array to sort.
 * @param  string  $KeyName1    the first item to sort by. 
 * @param  string  $SortOrder1  the order to sort by("SORT_ASC"|"SORT_DESC")
 * @param  string  $SortType1   the sort type("SORT_REGULAR"|"SORT_NUMERIC"|"SORT_STRING")
 * @return array                sorted array.
 */
function &sysSortArray(&$ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR") {
	if(!is_array($ArrayData)) {
		return $ArrayData;
	}
	// Get args number.
	$ArgCount = func_num_args();
	// Get keys to sort by and put them to SortRule array.
	for($I = 1;$I < $ArgCount;$I ++) {
		$Arg = func_get_arg($I);
		if(!eregi("SORT",$Arg)) {
			$KeyNameList[] = $Arg;
			$SortRule[] = '$'.$Arg;
		} else {
			$SortRule[] = $Arg;
		}
	}
	// Get the values according to the keys and put them to array.
	foreach($ArrayData AS $Key => $Info) {
		foreach($KeyNameList AS $KeyName) {
			${$KeyName}[$Key] = $Info[$KeyName];
		}
	}
	// Create the eval string and eval it.
	$EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
	eval ($EvalString);
	return $ArrayData;
}

/**
 * 字符串转换为十六进制编码(For GBK)
 */
function str2hex($str) {
	$str = (string)$str;
	$result = "";
	$len = strlen($str);
	for($i = 0; $i < $len; $i++) {
		if(ord($str[$i]) < 128 || $i + 1 == $len) {
			$result .= bin2hex($str[$i]) . "S";
		} else {
			$result .= bin2hex($str[$i] . $str[$i+1]) . "S";
			$i++;
		}
	}
	return trim($result, "S");
}

function my_strtolower($str) {
	$len = strlen($str);
	$A = ord('A');
	$Z = ord('Z');
	for($i = 0; $i < $len; $i++) {
		if(ord($str[$i]) >= $A && ord($str[$i]) <= $Z) {
			$str[$i] = chr(ord($str[$i]) + 32);
		}
	}
	return $str;
}







$db_hash="Ww~E33i7k&";
if (!function_exists("P_GetCookie")) {
	function P_GetCookie($Var){
		//echo P_CookiePre();
		return StrCode($_COOKIE[P_CookiePre().'_'.$Var],"DECODE");
	}
}
if (!function_exists("P_CookiePre")) {
	function P_CookiePre(){
		global $db_hash;
		return substr(md5($db_hash),0,5);
	}
}

if (!function_exists("PwdCode")) {
	function PwdCode($pwd){
		global $db_hash;
		return md5($_SERVER["HTTP_USER_AGENT"].$pwd.$db_hash);
	}
}
   
if (!function_exists("get_date")) {
	function get_date($timestamp,$timeformat=''){
		$db_datefm="Y-m-d H:i:s";
		$db_timedf="8";
		$date_show=$timeformat ? $timeformat : ($_datefm ? $_datefm : $db_datefm);
		if($_timedf){
			$offset = $_timedf=='111' ? 0 : $_timedf;
		}else{
			$offset = $db_timedf=='111' ? 0 : $db_timedf;
		}
		return gmdate($date_show,$timestamp+$offset*3600);
	}
}
if (!function_exists("onlineUserAgent")) {
	function onlineUserAgent() {
	
		if(isset($_SERVER["HTTP_USER_AGENT"])) {
		
			return $_SERVER["HTTP_USER_AGENT"];
		
		} else {
		
			return $_ENV["HTTP_USER_AGENT"];
		
		}
	}
}

if (!function_exists("onlineIP")) {
	function onlineIP() {
	
		//check the forward address X-Forwarded-For
		
		if(isset($_REQUEST['X-Forwarded-For']) && $_REQUEST['X-Forwarded-For'] !=""){
		
			$ip = $_REQUEST['X-Forwarded-For'];
		
		} else {
		
			//check the remote x client ip address
			
			if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
			
				$ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
			
			} else {
			
			//set the default client ip
			
				$ip = $_SERVER['REMOTE_ADDR'];
			
			}
		
		}
		
		return $ip;
		
	}
}
         
if (!function_exists("StrCode")) {
	function StrCode($string,$action='ENCODE'){
		global $db_hash;
		$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$db_hash),8,18);
		$string	= $action == 'ENCODE' ? $string : base64_decode($string);
		$len	= strlen($key);
		$code	= '';
		for($i=0; $i<strlen($string); $i++){
			$k		= $i % $len;
			$code  .= $string[$i] ^ $key[$k];
		}
		$code = $action == 'DECODE' ? $code : base64_encode($code);
		$user = explode("\t",$code);
		if($user[1]==""){
			return;
		}
		return $code;
	}
}

/** This function is used to get page urls by page type and necessary params */

}
?>
