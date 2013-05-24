<?php
/*
 * Created on Mar 3, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class BaiduTagDao {
	private static $defaultTag = "23092097_5_pg|1";
	private static $defaultSiteID = "38";
	private static $defaultCountry = "";
	private static $defaultMatch = "1";
	private static $defaultMatchType = "Default";
	private static $kwTagParams = array();
	private static $defaultExpireTime = 0;
	
	public static function findtag($keyword, $source, $referer) {
		if(!defined("__BAIDU_TAG_SERVICE")) {
			logError("undefine __BAIDU_TAG_SERVICE");
			return self::$defaultTag;
		}
        $requestKeyword = urlencode(iconv("GBK", "UTF-8//IGNORE", $keyword));
        $source = urlencode(iconv("GBK", "UTF-8//IGNORE", $source));
		$url = __BAIDU_TAG_SERVICE 
			.'/findbtag/?siteid='.self::$defaultSiteID
			.'&kw='.$requestKeyword
			.'&source='.$source
			.'&country='.self::$defaultCountry
			.'&ref='.urlencode($referer);
		$curl = new CURL();
		$curl->setTimeOut(1);
		//echo("<BR>{$url}<BR>\n");
		$content = $curl->get_contents($url);
		$bstsObject = simplexml_load_string($content);
		$tagArray = get_object_vars($bstsObject->tag);
		
		if(isset($_REQUEST['SMARTER_TEST']) && $_REQUEST['SMARTER_TEST'] == "YES") {
            echo "<PRE style='background:#fff;'>\n\n";
            echo  $url. "\n\n";
            echo htmlspecialchars($content);
            echo "\n</PRE>\n";
		}
		//ÉèÖÃchannel tag params Ä¬ÈÏÖµ
		if ($tagArray["billing-id"] && $tagArray["channel-id"]) {
			self::$kwTagParams[$keyword]["channelTag"] = 
				trim($tagArray["billing-id"]) ."|". trim($tagArray["channel-id"]);
		} else {
			self::$kwTagParams[$keyword]["channelTag"] = self::$defaultTag; 
		}
		self::$kwTagParams[$keyword]["isMatched"] =  self::$defaultMatch;
		self::$kwTagParams[$keyword]["matchType"] =  $tagArray["match-type"] ? 
			trim($tagArray["match-type"]) : self::$defaultMatchType;
		self::$kwTagParams[$keyword]["expireTime"] = $tagArray["expire-time"] ? 
			trim($tagArray["expire-time"]) : self::$defaultExpireTime;
		self::$kwTagParams[$keyword]["country"] = $tagArray["country"] ? 
			trim($tagArray["country"]) : self::$defaultCountry;
		self::$kwTagParams[$keyword]['channelTagForTrack'] = 
			self::$kwTagParams[$keyword]["channelTag"];
		if(empty($bstsObject)) {
			logError("BAIDU_TAG_SERVICE: fetch empty. maybe occurred timeout."); 
		}
		else if (empty($tagArray["billing-id"]) || empty($tagArray["channel-id"])) {
			logError("BAIDU_TAG_SERVICE: can't fount channel tag. content:\n'".$content);
		}
		return self::$kwTagParams[$keyword]["channelTag"];
	}
	
	public static function getTagParams($keyword) {
		return self::$kwTagParams[$keyword];
	}
}
?>