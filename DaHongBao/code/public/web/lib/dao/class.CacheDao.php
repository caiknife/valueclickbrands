<?php
class CacheDao {
	private static $fileDirs = array(
		'cache/discount',
		'images/attachment',
		'js_stats',
		'logs',
		'tplcache/configs',
		'tplcache/smartycache',
		'tplcache/templates_c'
	);

	private static $settingDirs = array(
		'array',
		'config',
		'dba',
		'switch'
	);

	public static function init() {
		foreach (self::$fileDirs as $dir) {
			$dst = __FILE_FULLPATH.$dir;
			if(!file_exists($dst)) {
				mkdir($dst, 0777, true);
			}
		}

		foreach (self::$settingDirs as $dir) {
			$dst = __SETTING_FULLPATH.$dir;
			if(!file_exists($dst)) {
				mkdir($dst, 0777, true);
			}
		}
	}

	public static function initFilesDir() {
		if(file_exists(__FILE_FULLPATH.self::$fileDirs[count(self::$fileDirs)-1])) {
			return;
		}
		foreach (self::$fileDirs as $dir) {
			$dst = __FILE_FULLPATH.$dir;
			if(!file_exists($dst)) {
				mkdir($dst, 0777, true);
			}
		}
	}
}