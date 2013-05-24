<?php
/**
 * func.AutoLoad.php
 *-------------------------
 *
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
 * @author     Menny Zhang <menny_zhang@mezimedia.com>
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: 
 * @link       / Location: /
 * @deprecated File deprecated in Release 2.0.0
 */
 
if(!defined("INC_FUNC_AUTOLOAD")) {
 	define("INC_FUNC_AUTOLOAD", "1");

	/**
	 * @自动装载类
	 * @说明: 系统全局函数
	 */
	function __autoload($class) {
		// find suffix
		for($loop=strlen($class)-1; $loop>=0; $loop--) {
			if($class[$loop] >= 'A' && $class[$loop] <= 'Z') {
				break;
			}
		}
		$classpath = FRONT_END_ROOT . "lib/"; 
		switch(substr($class, $loop)) {
		case "Object":
			include_once($classpath . "object/class." . $class . ".php");
			break;
		case "Action":
			include_once($classpath . "action/class." . $class . ".php");
			break;
		case "Process":
			include_once($classpath . "process/class." . $class . ".php");
			break;
		case "Dao":
			include_once($classpath . "dao/class." . $class . ".php");
			break;
		default:
			if(!@include_once($classpath . "util/class." . $class . ".php")){
				 //$lib = get_include_path();
				 $lib = get_include_path();
				 if(!strpos($lib, __TRACK_ROOT_PATH."library/")) {
				 	$lib = $lib . PATH_SEPARATOR . __TRACK_ROOT_PATH."library/";
					 set_include_path($lib);
				 }
				 if(!strpos($lib, FRONT_END_ROOT . "lib")) {
				 	 $lib = $lib . PATH_SEPARATOR . FRONT_END_ROOT . "lib";
					 set_include_path($lib);
				 }
				 if($class == "PEAR_Error") {
				   include_once("PEAR.php");
				 } else {
				   include_once(str_replace('_', "/", $class) . ".php");
				 }
			}
		}
	}
	
	/**
	 * 获得当前时间
	 * @return datetime Format:2006-03-06 18:10:10
	 */
	function getDateTime($format="Y-m-d H:i:s", $timeStamp = NULL, $timezone = __ADJUST_TIMEZONE) {
		$offset = $timezone * 60 * 60;
		if ($timeStamp == NULL) {
			$timeStamp = time();
		}
		$timeStamp += $offset;
		return date($format, $timeStamp);
	}
	
	//invoke tracking incoming
	if(!defined("__DISABLE_TRACKING_LOG") || __DISABLE_TRACKING_LOG != true) {
		require_once(__TRACK_ROOT_PATH."scripts/incoming.php");
	}
	//CREATE DIRS
	if(defined("__INIT_FILES_DIR") && __INIT_FILES_DIR) {
		CacheDao::initFilesDir();
	}
}