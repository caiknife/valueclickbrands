<?php
/**
 * class.GoogleAds.php
 *-------------------------
 *
 * This file include Book product classes' definetions.
 * It will be include in PHP files, and create a respected instance.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare
 * the attributes of over one million products in the Book and consumer electronics categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then
 * research the latest promotional and pricing information on products listed at a wide selection of
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @author     Kevin <Kevin@mezimedia.com>
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.BaiduAdsDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */

class BaiduAdsDao {

	private $baiduDomain = __AD_BAIDUDOMAIN;

	private $baiduads = "";
	private $askYahoo = 20;
	private $askNormal = 10;
	private $countAll = 0;

	private $searchText = "";
	private $source = "";

	private $timelimit = 5;  //baidu广告请求超时时间

    public function  __construct(){

    }

    public function  __destruct(){
    }


    /**
     * 获取Baidu广告
     *
     * @param array $params
     * @return array,false,null 正常返回array，没有广告返回null，超时放回false
     */
    public function getBaiduAds($searchText,$source) {
		$this->source = $source;
		if($source=='YAHOO'){
			$asknum = $this->askYahoo;
		}else{
			$asknum = $this->askNormal;
		}
		$this->searchText = $searchText;
		$url = $this->baiduDomain."&cl=3&rn=".$asknum."&ip=".Utilities::onlineIP()."&wd=".urlencode($searchText);

		$curl = CURL::getInstance();
		$curl->setTimeout($this->timelimit);
		$contents = $curl->get_contents($url);

		if($contents === false){ //超时
			return false;
		}

		if($contents){
			$this->baiduads = simplexml_load_string($contents);
			return true;
		}
    }

    public function parseBaiduAds(){
		$adsbd = $this->baiduads;
		if($adsbd->resultlist->result){
			foreach ($adsbd->resultlist->result as $key=>$value){
				$value->title = iconv("UTF-8","gb2312",$value->title);
				$value->title = str_ireplace($this->searchText,"<strong>".$this->searchText."</strong>",$value->title);

				$value->abstract = iconv("UTF-8","gb2312",$value->abstract);
				$value->abstract = strip_tags($value->abstract);
				$value->abstract = str_ireplace($this->searchText,"<strong>".$this->searchText."</strong>",$value->abstract);

				$value->uri = PathManager::sponsorLink($this->searchText,$value->uri,$value->seqnum);


				$uriarray = explode("/",$value->SHORTURL);
				$uriarray = explode("?",$uriarray[0]);
				$value->SHORTURL = $uriarray[0];
			}
		}
		$this->countAll = $adsbd->num;
		return $adsbd;
    }

    public function registerSponsorTransfer($keyword, $costTime, $ranks, $chid=0, $masterKeyword="", $channelTag = '') {
		Tracking_Logger::getInstance()->sponsorTransfer(array(
            'keyword'       => $keyword,
            'resultCount'   => $ranks,
            'costTime'      => $costTime,
            'channelTag'    => $channelTag,
            'requestIp'     => Utilities::onlineIP(),
        ));

		Tracking_Logger::getInstance()->sponsorTransfer(array(
            'keyword'           => $keyword,
            'impressionCount'   => $ranks,
            'channelTag'        => $channelTag,
            'sponsorType'       => Tracking_Constant::SPONSOR_BAIDU,
        ));
    }

	public function getShowCount($nums){
		if($this->source=="YAHOO"){
			if($nums>12){
				$sponsorCount = 12;
			}else{
				$sponsorCount = $nums;
			}
		}else{
			if($nums>8){
				$sponsorCount = 8;
			}else{
				$sponsorCount = $nums;
			}
		}
		return $sponsorCount;
	}
}
?>