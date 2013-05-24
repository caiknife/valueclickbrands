<?php
/*
 * Created on 2009-08-03
 * class.CacheFile.php
 * �ļ�����
 * -------------------------
 * 
 * 
 * 
 * @author thomas_fu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: 
 * @link       http://www.smarter.com/
 */
class FileCache {
	
	function __construct($cache_dir = '') {
	}
	
	/**
	 * ��ȡ�ļ�Ŀ¼
	 */
	public static function getFileDir($subDir, $file, $rootDir, $suffixName = '') {
		$subDir = trim($subDir, "/");
		if (empty($suffixName)) { 
			$tmpDir = $rootDir.date("ymd")."/".$subDir."/";
		}
		else {
			$tmpDir = $rootDir.$suffixName."/".$subDir."/";
		}
		return $tmpDir.$file;
	}
	
	/**
	 * ��ȡ�ļ�����
	 * @param  string $subDir ��Ŀ¼����
	 * @param  $string $file 	�ļ�����
	 * @param  $expire ����ʱ�� 0 ��������
	 * @param  $rootDir ��Ŀ¼����
	 * @return $file 
	 */
	public static function getDateCache($subDir, $file, $expire = 0,  $rootDir = __SE_CACHE_DIR) {
		$fileName = self::getFileDir($subDir, $file, $rootDir);
		if (!file_exists($fileName)) {
			if(($expire > 0 && (time()  < strtotime(date("Y-m-d")) + $expire)) || $expire == 0) {
				//��ȡ��һ��cache
				$fileName = self::getFileDir($subDir, $file, $rootDir, date("ymd", time()-3600*24));
				if (!file_exists($fileName)) {
					return false;
				}
			}
			else {
				return false;
			}
		}
		if ($expire > 0) {
			$mtime = filemtime($fileName);
			if(time() > $expire + $mtime) {
				return false;
			}	
		}
		return file_get_contents($fileName);
	}
	
	/**
	 * ���û���
	 *
	 * @param string $subDir 
	 * @param string $file
	 * @param string $rootDir
	 * @param  string $data
	 */
	public static function setDateCache($subDir, $file, $data, $rootDir = __SE_CACHE_DIR) {
		$fileName = self::getFileDir($subDir, $file, $rootDir);
		if (!is_dir($dirName = dirname($fileName))) {
			@mkdir($dirName, 0777, true);
		}
		$fp = fopen($fileName, "wb+");
		if (flock($fp, LOCK_EX)) {
			fwrite($fp, $data);
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}
	
}