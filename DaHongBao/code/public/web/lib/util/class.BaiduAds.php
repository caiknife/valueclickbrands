<?php
/*
 * Created on 2010-10-18
 * 
 * 得到SPL内容
 * 
 * @author     Thomas_FU
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.BaiduAds.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */

class BaiduAds extends CommonAds {
	protected $baiduDomain = "http://www.baidu.com/s";
	protected $tn = '23092097_5_pg';	//默认百度计费账号
	protected $ch = '1';	//默认的渠道名
	protected $channelTag = '';
	protected $adsTypeMap = array(
		"advertizing-link"      => Tracking_Constant::SPONSOR_BAIDU_PROMOTION,
		"accurate-matching"     => Tracking_Constant::SPONSOR_BAIDU_ACCURATE,
		"intelligent-matching"  => Tracking_Constant::SPONSOR_BAIDU_INTELLIGENT
	);
	protected $totalCount = 0; //百度返回的广告数量
    protected $IsDisplayKeywordArr = array();   //显示广告模板样式
	
	public function initParams($params) {
        if (parent::initParams($params) == false) {
			return false;
		}
		$this->channelTag = $this->getSemTag($this->keyword);
		if (strpos($this->channelTag, "|") !== false) {
			list($this->tn, $this->ch) = explode("|", $this->channelTag);
		} else {
			logError("Baidu Channel Tag return error.{$this->channelTag}");
		}
		if (defined("__LOG_LEVEL") && __LOG_LEVEL <= 1
			&& (Utilities::onlineIP(false) == '127.0.0.1'
					|| strpos(Utilities::onlineIP(false), '192.168.') === 0)) {
			$this->tn = "baidu_xmltest_3";	//测试账号
		}
		return true;
	}
    
	/**
	 * 得到百度广告内容
	 * @return array $adsArr
	 */
	public function  getAdsContent() {
	    // 暂停百度广告
	    return false;
		$startTime = Utilities::getMicrotime();
		//1.得到请求百度广告的URL
		$reqUrl = $this->makeRequestUrl();
		//2.得到百度广告内容
		$adsArr = $this->parseAds($reqUrl);
		//3.格式化广告显示的内容
		$adsArr = $this->formatToView($adsArr);
		//4.tracking Sponsor Transfer数据记录
		$costTime = Utilities::getMicrotime() - $startTime;
        Tracking_Logger::getInstance()->sponsorTransfer(array(
            'keyword'       => $this->keyword,
            'resultCount'   => $this->totalCount,
            'costTime'      => $costTime,
            'channelTag'    => $this->channelTag,
            'requestIp'     => Utilities::onlineIP(),
            'sponsorType'   => Tracking_Constant::SPONSOR_BAIDU,
            'requestcount'  => $this->requestAdsCount,
        ));
		//5.打印信息
		if (isset($_REQUEST['SMARTER_TEST']) && $_REQUEST['SMARTER_TEST'] == "YES") {
			logWarn("Test outer: ".$reqUrl);
            echo "<PRE style='background:#fff;'>\n\n";
			echo $reqUrl . "\n<BR><BR><PRE>\n";
			print_r($adsArr);
			echo "\n\n</PRE>\n";
		}
		return $adsArr;
	}

	/**
	 * 得到请求的URL
	 */
	public function makeRequestUrl() {
		$clientIP = Utilities::onlineIP(false);//得到客户端请求的IP
		$url = $this->baiduDomain."?wd=".$this->keyword."&tn=".$this->tn."&ch=".$this->ch."&ip=".$clientIP;
		return $url;
	}
	
	public function parseAds($reqUrl) {	
		$curl = CURL::getInstance();
		$curl->setTimeout($this->timeout);
		$str = $curl->get_contents($reqUrl);
        
        if (empty($str)) {
			logWarn("Baidu ads XML Result Is Empty (URL={$reqUrl}\nContent:\n{$str}\n).");
			return array();
		}
		$dom = new DomDocument();
		$dom->loadXML($str);
		if (is_object($dom->documentElement) == false) {
			//注:只能记正式环境中的XML文件内容
			logWarn("Baidu ads XML error(URL={$reqUrl}\nContent:\n{$str}\n).");
			return array();
		}
		$arr = array();
		$adsCount = 0;
		$adResults = $dom->documentElement->getElementsByTagName("adresults")->item(0);
		$resultSets = $adResults->getElementsByTagName('resultset');
		foreach ($resultSets as $resultSet) {
			$adsResults = $resultSet->getElementsByTagName('result');
			//广告类型
			$adsType = trim($resultSet->getAttributeNode('type')->value);
			//百度返回广告数量
			$this->totalCount += intval($resultSet->getAttributeNode('numResults')->value);	
			foreach ($adsResults as $adsResult) {
				if ($adsCount >= $this->requestAdsCount) {
					break;
				}
				$tmpArr['rank'] = $adsResult->getAttributeNode('rank')->value;
				$tmpArr['sponsorType'] = $this->adsTypeMap[$adsType];
				$lines = $adsResult->childNodes;
				foreach ($lines as $line) {
					if ($line->nodeName == "#text") {
						continue;
					}
					if ($line->nodeName == "title") {
						$tmpArr['title'] = $this->formatAds($line->nodeValue);
					}
					if ($line->nodeName == "url") {
						$tmpArr['url'] = $this->formatAds($line->nodeValue);
					}
					if ($line->nodeName == "abstract") {
						$tmpArr['abstract'] = $this->formatAds($line->nodeValue);
					}
					if ($line->nodeName == "site") {
						$tmpArr['site'] = $this->formatAds($line->nodeValue);
					}
				}
				$arr[] = $tmpArr;
				$adsCount++;
			}
		}
		return $arr;
	}
	
	//格式化广告显示的内容
	public function formatToView($adsData, $masterKeyword="") {
		if (count($adsData) == 0) {
			return NULL;
		}
		//统计百度三种类型的广告数量
		$sponsorTypeCountArr = array();
		for ($loop=0, $cnt=count($adsData); $loop<$cnt; $loop++) {
			$bidPos = $adsData[$loop]['rank'];
			$adsData[$loop]['abstract'] = $adsData[$loop]['abstract'];
			$sponsorTypeCountArr[$adsData[$loop]['sponsorType']]++;
			//SponsorRedir URL
			$adsData[$loop]['url'] = PathManager::sponsorLink(
                    $this->keyword,
                    $adsData[$loop]['url'],
                    $bidPos,
                    $this->channelTag,
                    $adsData[$loop]['sponsorType']
            );
		}
		//记录sponsor Impression
		foreach ($sponsorTypeCountArr as $sponsorType => $showCount) {
			Tracking_Logger::getInstance()->sponsorImpression(array(
                'keyword'           => $this->keyword,
                'impressionCount'   => $showCount,
                'channelTag'        => $this->channelTag,
                'sponsorType'       => $sponsorType,
            ));
		}
		return $adsData;
	}
	
	/**
	 * 格式化百度广告元素
	 * @param string $adsFieldText
	 * @return string
	 */
	public function formatAds($adsFieldText) {
		if (empty($adsFieldText)) {
			return '';
		}
        $adsFieldText = iconv("UTF-8", "GBK", $adsFieldText);
		$searchWords = array('<font color=#CC0000>', '</font>');
		$replaceWords = array('<strong>', '</strong>');
		$adsFieldText = str_ireplace($searchWords, $replaceWords, $adsFieldText);
		return trim(addslashes($adsFieldText));
	}
	
	/**
	 * 返回Channel Tag 根据tn, ch
	 * @return string
	 */
	public function getSemTag($keyword) {
        $source = Tracking_Session::getInstance()->getSource();
        $referer = Tracking_Session::getInstance()->getLandingReferer();
		$tag = BaiduTagDao::findtag($keyword, $source, $referer);
		return $tag;
	}
}

