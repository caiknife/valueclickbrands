<?php
/*
 * Created on 2009-3-18
 * SystemConfigDao.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.SystemConfigDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class SystemConfigDao {
	private static $systemConfig = null;
	
	public static function init() {
		self::load();
		if(self::$systemConfig != null) {
			return;
		}
		self::store();
	} 
	
	public static function getGoogleDNSLookup() {
		self::load();
		return isset(self::$systemConfig['ggdns']) ? self::$systemConfig['ggdns'] : null;
	}
	
	public static function setGoogleDNSLookup($dnslookup) {
		$dnslookup = strtoupper($dnslookup);
		if($dnslookup != "IP" && $dnslookup != "DOMAIN") {
			$dnslookup = null;
		}
		self::$systemConfig['ggdns'] = $dnslookup;
		self::store();
	}
	
	protected static function load() {
		if(self::$systemConfig !== null) {
			return;
		}
		self::$systemConfig = Utilities::getArrayCache(__SETTING_FULLPATH.'config_main/system_config.php');
		if(self::$systemConfig == null) {
			self::$systemConfig = array();
		}
	}
	
	protected static function store() {
		Utilities::setArrayCache(__SETTING_FULLPATH.'config_main/system_config.php', self::$systemConfig);
	}
	
	
}
?>