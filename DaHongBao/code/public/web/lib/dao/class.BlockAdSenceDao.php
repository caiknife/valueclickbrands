<?php
/*
 * Created on 2010-08-05
 * class.BlockAdSenceDao.php
 * -------------------------
 * 
 * 屏敝请求关键词或URL
 * 
 * @author Thomas fu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id
 * @link       http://www.smarter.com/
 */

class BlockAdSenceDao {
	public static function updateBlockAdSence() {
		$md5Arr = file('http://2007.smarter.com.cn/adSenceManage.php?switch=DHBList');
		if (is_array($md5Arr)) {
			foreach ($md5Arr as $md5Code) {
				$result[trim($md5Code)] = 1;
			}
		}
		$fileName = __SETTING_FULLPATH . "blockAdSence/blockAdSence.php";
		Utilities::setArrayCache($fileName, $result);
		FileDistribute::syncDirectory(__SETTING_PATH . "blockAdSence");
		print("Create--BlockAdSence File END <br>\r\n");
	}
	/**
	 * 获取文件数组
	 */
	public static function getBlockList() {
		static $allBlockAdSence = array();
		if ($allBlockAdSence) {
			return $allBlockAdSence;
		}
		$fileName = __SETTING_FULLPATH . "blockAdSence/blockAdSence.php";
		$allBlockAdSence = Utilities::getArrayCache($fileName);
		return $allBlockAdSence;
	}
	/**
	 * 根据请求的URL 来确定是否是不显示广告
	 * @param string $requestUrl
	 * @return boolean if match true or false
	 */
	public static function isBlockAdSence($requestUrl = NULL) {
		$allBlockAdSence = self::getBlockList();
		if (empty($requestUrl)) { //request url
			$requestUrl = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		$requestUrl = urldecode(strtolower(urlencode($requestUrl)));
		if ($allBlockAdSence[md5($requestUrl."URL")]) {
			return true;
		}
		return false;
	}
	
	/**
	 * 根据Keywords 来确定是否要显示产品，用在search页面
	 * @param string $keywords
	 * @return boolean if match true or false
	 */
	public static function isBlockKeywords($keywords) {
		$allBlockAdSence = self::getBlockList();
		$keywords = urldecode(strtolower(urlencode($keywords)));
		if ($allBlockAdSence[md5($keywords."Keywords")]) {
			return true;
		}
		return false;
	}
}
?>