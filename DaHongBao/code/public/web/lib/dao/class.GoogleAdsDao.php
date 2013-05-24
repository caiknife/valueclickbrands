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
 * @version    CVS: $Id: class.GoogleAdsDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */

class GoogleAdsDao {

	public $googleDomain = "http://www.google.com.cn";
    public $googleParams = array();
    public $breakMarking = "<br>";
    public static $useBase16 = true;

    public function  __construct(){
			$this->googleParams = array(
			 	"client" => "dahongbao-cn",
				"q" => "",
				"ip" => "",
				"ad" => "w10",
				"output" => "xml_no_dtd",
				"num" => 0,
				"channel" => "", //modify by fan 061221

				"adpage" => "",
				"adsafe" => "",
				"adtest" => "",
				"gl" => "cn",
				"hl" => "zh-CN",
				"ie" => "gb",
				"oe" => "gb",

				"useragent" => ""
			);
    }

    public function  __destruct(){
    }


    /**
     * ��ȡGoogle���
     *
     * @param array $params
     * @return array,false,null ��������array��û�й�淵��null����ʱ�Ż�false
     */
    public function getGoogleAds($params) {
		if ($params["ad"]) {
			$count = substr($params["ad"], 1);
//		$params["ad"] = substr($params["ad"], 0, 1) . ($count + 1);
		}
		if($params["visitHost"] == "google.cn") {
      $ads->googleDomain = "http://www.google.cn";
    }
    	//assign parameters
    	reset($this->googleParams);
    	while(list($k,$v)=each($params)){
    		if(isset($this->googleParams[$k])){
    			$this->googleParams[$k] = $v;
    		}
    	}
    	$this->googleParams['q'] = urlencode(trim($params['q']));
    	$this->googleParams['useragent'] = urlencode(trim($params['useragent']));
    	//�ؼ��ֱ��벻Ϊ��
    	if($this->googleParams['q'] == "") {
    		return false;
    	}

    	//make up request url and get the xml doc
    	$reqUrl = $this->makeRequestUrl();
		// echo "$reqUrl<BR>";
	    if($reqUrl != ""){
	    	if(isset($params['timeout']) && ($params['timeout'] > 0 && $params['timeout'] < 30)) {
	    		$timeout = intval($params['timeout']);
	    	} else {
	    		$timeout = 10;
	    	}
	    	$ADSArr = $this->parseGoogleAds($reqUrl, $timeout);
	    	if($ADSArr === false) {
	    		return false;
	    	}
	    	/*logs for tracking goes here */
	    	$b64eKey = base64_encode($params['q']);
	    	//����5�д���û���κ����ã��Ƿ���BUG��
	    	foreach($ADSArr as $v) {
	    		$tmpUrl = __REDIR_URL."sponsorRedir.php?statDestUrl=".
	    			base64_encode($v['url'])."&statBidPosition=".$v['n']."&searchTerm=".$b64eKey;
	    		$v['url'] = $tmpUrl ;
	    	}

	    	$strLog = $params['q']."|" . sizeof($ADSArr) . "|" . getenv("REMOTE_ADDR");
				$para = array('sessionID'=>0, 'keyword'=>$strLog,
					'sponsorType' => __SPONSOR_TYPE, 'channelID'=> $nChannelID);
			//TEST
			if(isset($_REQUEST['SMARTER_TEST']) && $_REQUEST['SMARTER_TEST'] == "YES") {
				logWarn("Test outer: ".$reqUrl);
				echo "<PRE style='background:#fff;'>\n\n";
				echo $reqUrl . "\n\n";
				print_r($ADSArr);
				echo "\n</PRE>\n";
			}


		}
		if (isset($count)) {
			$ADSArrTmp = $ADSArr;
			$ADSArr = NULL;
			$currentNum = 0;
			for($loop=0, $cnt = count($ADSArrTmp); $loop<$cnt; $loop++) {
				if (strpos($ADSArrTmp[$loop]["visible_url"], "smarter.com") !== false) {
					continue;
				}
				$ADSArr[$currentNum] = $ADSArrTmp[$loop];
				$currentNum++;
				if ($currentNum >= $count) {
					break;
				}
			}
		}
		return $ADSArr;
    }

    public function formatToView($adsData, $keyword, $chid, $channelTag='', $baseBidPos=0, $absRedirUrl=false, $masterKeyword="") {
    	if(empty($adsData) || count($adsData) == 0) {
    		return NULL;
    	}
    	for($loop=0,$cnt=count($adsData); $loop<$cnt; $loop++) {

	    	$baseBidPos++;
    		$adsData[$loop]['LINE1'] = addslashes($adsData[$loop]['LINE1']);
    		$adsData[$loop]['LINE2'] = addslashes($adsData[$loop]['LINE2']);
    		$adsData[$loop]['SiteUrl'] = addslashes($adsData[$loop]['visible_url']);
    		if(!empty($adsData[$loop]['LINE3'])) {
    			$adsData[$loop]['LINE3'] = addslashes($adsData[$loop]['LINE3']);
    			$adsData[$loop]['SiteUrl'] = Utilities::cutString(
    									$adsData[$loop]['SiteUrl'], 17);
    		}
    		$adsData[$loop]['OriginalUrl'] = $adsData[$loop]['url'];
    		//SponsorRedir URL
    		/*$adsData[$loop]['url'] = TrackingFE::registerSponsorLink($keyword,
				                                                     $baseBidPos,
				                                                     $adsData[$loop]['url'],
				                                                     $adsData[$loop]['visible_url'],
				                                                     array("chid"          =>$chid,
				                                                           "masterKeyword" =>$masterKeyword,
				                                                           "channelTag"    =>$channelTag));*/
    		$adsData[$loop]['url'] = PathManager::sponsorLink($keyword, $adsData[$loop]['url'], $baseBidPos, $channelTag);
    		if($absRedirUrl) {
    			$adsData[$loop]['url'] = __WEB_DOMAIN_NAME . substr($adsData[$loop]['url'], 1);
    		}
    	}
    	return $adsData;
    }


    /*===== Util functions=====*/
    function makeRequestUrl(){
    	$url = $this->googleDomain."/search";

    	//base16
    	if(self::$useBase16) {
	    	$this->googleParams['q'] = bin2hex($this->googleParams['q']);
//	    	$this->googleParams['useragent'] = bin2hex($this->googleParams['useragent']);
//			foreach($this->googleParams as $key => $val) {
//				$this->googleParams[$key] = bin2hex($this->googleParams[$key]);
//			}
    	}

		$paramstr = "";
    	while(list($k,$v)=each($this->googleParams)){
    		if(trim($v)==""){
    			continue;
    		}else{
    			$paramstr .= "&".$k."=".$v;
    		}
    	}
    	if($paramstr==""){
    		return "";
    	}else{
    		$url .= "?".substr($paramstr,1);
    		if(self::$useBase16) {
	    		$url .= "&base16=2";
    		}
    		return $url;
    	}
    }

    function parseGoogleAds($xmlUrl, $timeout=30){
		$curl = CURL::getInstance();
		$curl->setTimeout($timeout);
		if(GoogleDNSDao::getDnslookup() == "IP"
			&& ($googleip=GoogleDNSDao::getGoogleIP()) != "") {
			$xmlUrl = "http://{$googleip}".substr($xmlUrl, strlen($this->googleDomain));
			$curl->setOption(CURLOPT_HTTPHEADER, array("Host: www.google.com"));
		}
		logInfo($xmlUrl);

		//������ڲ� ORIGINAL �����������Ľ������������¼
        $isPrivateIP = Tracking_Session::getInstance()->getTrafficType() == Tracking_Constant::TRAFFIC_PRIVATE_IP;
		if(isset($_REQUEST['SMARTER_TEST']) && $_REQUEST['SMARTER_TEST'] == "ORIGINAL" && $isPrivateIP) {
			//������ҪLog CURL���������ݣ��ʽ�$curl->get_contents���������Ƴ���
			$curl->setHeaderEnable(false);
			$info = $curl->get($xmlUrl);
			if(200 <= $info['http_code'] && $info['http_code'] < 300) {
				$str = $info['data'];
			}
			else {
				$str = false;
			}

			$urlinfo = parse_url($info['url']);
			$info['ip'] = gethostbyname($urlinfo['host']);
			$info['dnslookup'] = GoogleDNSDao::getDnslookup();
			echo "<!--[GOOGLELOG] ";
			echo htmlentities(serialize($info));
			echo " [GOOGLELOG]-->";

		}
		else {
			$str = $curl->get_contents($xmlUrl);
		}

		if($str === false) {
			return false;
		}
    	if(empty($str)) {
    		return array();
		}
    	if(self::$useBase16) {
    		$str = pack("H*",$str);
    	}
    	//modify by Fan(2009-08-05): convert '&' to '&#x26;' for display Trademark symbol.
    	$str = str_replace("&#x", "&#x26;#x", $str);
 		//convert GB2312 to GBK
 		if(($pos=strpos($str, "\n")) > 0) {
 			$str = str_ireplace("encoding=\"GB2312\"", "encoding=\"GBK\"", substr($str, 0, $pos))
 				. substr($str, $pos);
 		}
		$str = iconv("GBK", "GBK//IGNORE", $str);
    	//use dom to parse the xml doc
    	$dom = new DomDocument();
	    $dom->loadXML($str);
    	//add by Fan(2007-08-13): Google XML maybe cann't parsed.
    	if(is_object($dom->documentElement) == false) {
    		//ע:ֻ�ܼ���ʽ�����е�XML�ļ�����
    		logWarn("Google ads XML error(URL={$xmlUrl}\nContent:\n{$str}\n).");
    		return array();
    	}
    	//end add.
     	$ADS = $dom->documentElement->getElementsByTagName('AD');
     	$arr = array();
     	$sortarr = array();
     	foreach ($ADS as $AD) {
     		if ($AD->nodeName == 'AD') {
     			$tmparr['n'] = $AD->getAttributeNode('n') ->value;
     			$sortarr[] = $tmparr['n'];

     			$tmparr['url'] = $AD->getAttributeNode('url') ->value;
     			$tmparr['visible_url'] = $AD->getAttributeNode('visible_url') ->value;
     			$tmparr['visible_url'] = iconv("UTF-8", "GBK", trim($tmparr['visible_url']));
     			//fetch lines
     			$lines = $AD->childNodes;

     			foreach ($lines as $line) {
     				if ($line->nodeName == 'LINE1') {
     					$tmparr['LINE1'] = iconv("UTF-8", "GBK", trim($line->nodeValue));
     				}
     				if ($line->nodeName == 'LINE2') {
     					$tmparr['LINE2'] = iconv("UTF-8", "GBK", trim($line->nodeValue));
     				}
     				if ($line->nodeName == 'LINE3') {
     					$tmparr['LINE3'] = iconv("UTF-8", "GBK", trim($line->nodeValue));
     				}
     			}
     			$arr[] = $tmparr;
     		}
     	}

     	//asort by n
     	array_multisort($sortarr,SORT_ASC,$arr);
     	reset($arr);
     	return $arr;
    }

    function splitKeyword ($strKeyword) {
        $arrKeyword = explode("|", $strKeyword);
        $nCount = sizeof($arrKeyword);
        if ($arrKeyword[$nCount - 1] <> "") {
            $arrtmpKeyword = array_slice($arrKeyword, 0, $nCount-1);
        }else {
            $arrtmpKeyword = array_slice($arrKeyword, 0, $nCount-2);
        }
        return (implode("|", $arrtmpKeyword));
    }

    //get last word of keyword in strKeyword
    function getSingleKeyword($strKeyword) {
        $arrKeyword = explode("|", $strKeyword);
        $nCount = sizeof($arrKeyword);
        if($nCount > 1) {
            return $arrKeyword[$nCount-2];
        }else {
            return $strKeyword;
        }

    }
}
?>