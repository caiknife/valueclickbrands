<?php
/*
 * Created on 2010-7-8
 * 
 * 得到GOOGLE SPL内容
 * 
 * @author     Thomas_FU
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.GoogleAds.php,v 1.1 2013/04/15 10:58:02 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */

class GoogleAds extends CommonAds {
	protected $googleParams = array(
						'adtest' => 'off',
						'adsafe' => 'off', //JC 09-05
						'q' => '',
						'channel' => '',
						'ad' => '',
						'ip' => '',
						'useragent' => '',
						'timeout' => '',
					);	//请求google 参数
	protected $channelTag = "";
							
	/**
	 * 请求google广告参数设置
	 * $params array $params
	 * @return void
	 */
	public function initParams($params) {
		if (parent::initParams($params) == false) {
			return false;
		}
		//google ads 全局参数设置
		//设置是否要编码
		if (defined("__LOG_LEVEL") && __LOG_LEVEL == 1) {
			GoogleAdsDao::$useBase16 = false;
		}
		//强制指定是否采用base16请求广告
		if ($this->googleParams['useBase16'] === "yes") {
			GoogleAdsDao::$useBase16 = true;
		}
		else if ($this->googleParams['useBase16'] === "no") {
			GoogleAdsDao::$useBase16 = false;
		}
		//设置是域名还是IP
		GoogleDNSDao::setDnslookup($this->googleParams['dnslookup']);
		
		//设置google ads 基本参数
		if(defined("__LOG_LEVEL") && __LOG_LEVEL == 1 && DIRECTORY_SEPARATOR != "/") {
			$this->googleParams['adtest'] = "on";
		} else if (!empty($params['googleAdtest'])) {
			$this->googleParams['adtest'] = $params['googleAdtest'];
		}
		$this->googleParams['q'] = $this->keyword;
		$tagInfo = $this->getSemTag($this->keyword);
		$this->channelTag = $tagInfo['channelTagForTrack'];
		$this->googleParams['client'] = $tagInfo['clientID'];
		$this->googleParams['channel'] = $tagInfo['channelTag'];
		$this->googleParams['ad'] = "w".$this->requestAdsCount;
		if (empty($this->googleParams['ip'])) {
			$this->googleParams['ip'] = Utilities::onlineIP();
		}
		if (empty($this->googleParams['useragent'])) {
			$this->googleParams['useragent'] = Utilities::onlineUserAgent();
		}
		$this->googleParams['timeout'] = $this->timeout;
		return true;
	}
	
	public function getParams() {
		return $this->googleParams;
	}
	
	/**
	 * 请求google ads 内容
	 *
	 * @return array
	 */
	public function getAdsContent() {
		$params = $this->getParams();
		$ads = new GoogleAdsDao();
		$adsNum = substr($params['ad'], 1);
		$startTime = Utilities::getMicrotime();	
		$ret = $ads->getGoogleAds($params);
		$rank = $ret ? count($ret) : 0;
		$costTime = Utilities::getMicrotime() - $startTime;
		//request google ads is timeout
		if($ret === false) {
			$ret = array();
		}
		$adsData = $ads->formatToView($ret, $this->keyword, $this->chid, $this->channelTag);
		$showCount = count($adsData);
		//保存ads count
		$this->adsCount = $showCount;
        //统计
        $this->trackingFE($this->keyword, $costTime, $rank, $adsNum, $this->chid, $this->channelTag);
		return $adsData;
	}

	
	/**
	 * 消重两次请求的广告
	 * @param array $ret2 需要消重的广告
	 * @param array $ret 已有广告
	 * @return 消重后的广告数组
	 */
	protected function filterExistsAd($ret2, &$ret) {
		$existsAdUrl = array();
		foreach($ret as $index => $r) {
			$existsAdUrl[$r['visible_url']] = true;
		}
		$tmp_ret2 = array();
		foreach($ret2 as $index => $r2) {
			if(! isset($existsAdUrl[$r2['visible_url']])) {
				$tmp_ret2[] = $r2;
			}
		}
		return $tmp_ret2;
	}
	
    /**
     * 广告统计
     *
     */
    protected function trackingFE($keyword, $costTime, $ranks, $adsNum = 0, $chid = 0, $channelTag = 'Search') {
        Tracking_Logger::getInstance()->sponsorTransfer(array(
            'keyword'       => $keyword,
            'resultCount'   => $ranks,
            'costTime'      => $costTime,
            'channelTag'    => $channelTag,
            'requestIp'     => Utilities::onlineIP(),
            'sponsorType'   => Tracking_Constant::SPONSOR_GOOGLE,
            'requestcount'  => $adsNum
        ));

        Tracking_Logger::getInstance()->sponsorImpression(array(
            'keyword'           => $keyword,
            'impressionCount'   => $ranks,
            'channelTag'        => $channelTag,
            'sponsorType'       => Tracking_Constant::SPONSOR_GOOGLE,
        ));
    }
	
	/**
	 * 返回Channel Tag 根据Keyword
	 * @param string $keyword
	 * @return string
	 */
	public function getSemTag($keyword) {
		$source = Tracking_Session::getInstance()->getSource();
        $referer = Tracking_Session::getInstance()->getLandingReferer();
        $tag = GoogleTagDao::findtag($keyword, $source, $referer);
        return $tag;
	}
}

