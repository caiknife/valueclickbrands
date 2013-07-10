<?php
/**
 * Tracking Incoming
 *
 * @category   Tracking
 * @package    Tracking_Incoming
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Incoming.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * Tracking Incoming
 *
 * @category Tracking
 * @package Tracking_Incoming
 */
class Tracking_Incoming
{

    /**
     * Tracking Session
     *
     * @var Tracking_Session
     */
    protected $_session;

    /**
     * tracking log api
     *
     * @var Tracking_Logger
     */
    protected $_logger;

    /**
     * Tracking Request paser
     *
     * @var Tracking_Request_Incoming
     */
    protected $_request;

    /**
     * request uri
     * 
     * @var string
     */
    protected $_requestUri;

    /**
     * HTTP Referer
     *
     * @var string
     */
    protected $_referer;

    /**
     * Channel Id
     *
     * @var integer
     */
    protected $_channelId;

    /**
     * Product Id
     *
     * @var integer
     */
    protected $_productId;

    /**
     * Category Id
     *
     * @var integer
     */
    protected $_categoryId;

    /**
     * Http Host
     *
     * @var String
     */
    protected $_HttpHost;
   
    /**
     * detect fraud
     *
     * @return integer
     */
    protected function _getTrafficType ()
    {
        $config = Mezi_Config::getInstance()->tracking->service->strategy;
        $strategy = new Tracking_Strategy(empty($config) ? null : $config->toArray());
        
        $ip = $this->_request->getClientIp();
        $userAgent = $this->_request->getUserAgent();
        
        if ($strategy->isGoodUserAgent($userAgent)) {
            $trafficType = Tracking_Constant::TRAFFIC_GOOD_USERAGENT; // -11
        } elseif ($strategy->isGoodIp($ip)) {
            $trafficType = Tracking_Constant::TRAFFIC_GOOD_IP; // -12
        } else 
            if ($strategy->isBadUserAgent($userAgent)) {
                $trafficType = Tracking_Constant::TRAFFIC_BAD_USERAGENT; // -1
            } elseif ($strategy->isEmptyUserAgent($userAgent)) {
                $trafficType = Tracking_Constant::TRAFFIC_EMPTY_USERAGENT; // -3
            } elseif ($strategy->isBadIp($ip)) {
                $trafficType = Tracking_Constant::TRAFFIC_BAD_IP; // -2
            } elseif ($strategy->isPrivateIp($ip)) {
                $trafficType = Tracking_Constant::TRAFFIC_PRIVATE_IP; // -4
            } else {
                $trafficType = Tracking_Constant::TRAFFIC_NORMAL; // 0
            }
        
        return $trafficType;
    }

    /**
     * log incoming and user session
     *
     * @param Tracking_Session $session            
     */
    protected function _logLanding ()
    {
        $session = $this->_session;
        $request = $this->_request;
        
        $userId = $session->getUserId();
        $isRepeatUser = 0;
        if (empty($userId)) {
            $keyPrefix = $request->getServer('SERVER_ADDR') . '-' . $request->getClientIp();
            $userId = $session->generateUniqueId('userId' . $keyPrefix);
            $userId = date("ymdh") . substr($userId, 8);
            $session->setUserId($userId); // update new userid
        } else {
            $isRepeatUser = 1;
        }
        
        $incomingLog = array(
                'userID' => $userId, // new UserId
                'visitIP' => $request->getClientIp(),
                'httpReferer' => $request->getHttpReferer(),
                'httpUserAgent' => $request->getUserAgent(),
                'requestUri' => $request->toString(), // can use (string)$request on php 5.2+
                'valid' => $session->getTrafficType(),
                'pageType' => $request->getPageType(),
                
                'channelID' => $this->_channelId,
                'categoryID' => $this->_categoryId,
                'productID' => $this->_productId,
                
                'source' => $session->getSource(),
                'sourceGroup' => $session->getSourceGroup(),
                'testKey' => $session->getTestKey()
        );
        
        $this->_logger->incoming($incomingLog);
    }

    /**
     * log page visit
     *
     * @param Tracking_Session $session            
     */
    protected function _logPageVisit ()
    {
        $request = $this->_request;
        $session = $this->_session;
        
        $requestUri=strtolower($request->toString());
        
        if (stripos($requestUri, 'wishlist.php?reqtype=ajax') === false && stripos($requestUri, 'tracking_uiname=addFavorite') === false) {
            $pageVisitLog = array(
                    'visitIP' => $request->getClientIp(),
                    'httpUserAgent' => $request->getUserAgent(),
                    'pageType' => $request->getPageType(),
                    'trafficType' => $session->getTrafficType(),
                    
                    'requestUri' => $request->toString(),
                    'httpReferer' => $request->getHttpReferer(),
                    'pagevisitorder' => $session->getVisitOrder(),
                    
                    'channelId' => $this->_channelId,
                    'productId' => $this->_productId,
                    'categoryId' => $this->_categoryId
            );
            $this->_logger->pageVisit($pageVisitLog);
        }
    }

    /**
     * constructor
     *
     * @return Tracking_Incoming
     */
    public function __construct ()
    {
        // init session
        $this->_session = Tracking_Session::getInstance();
        $this->_session->setSiteId((integer) Mezi_Config::getInstance()->tracking->site->id);
        
        $this->_channelId = '';
        $this->_categoryId = '';
        $this->_productId = '';
        $request = new Tracking_Request_Incoming();
        if (! $request->hasSource()) {
            $this->_channelId = $request->getChannelId();
            $this->_productId = $request->getProductId();
            $this->_categoryId = $request->getCategoryId();
        }
        $this->_referer = $request->getHttpReferer();
        $this->_HttpHost = $request->getServer('HTTP_HOST');
        $this->_request = $request;
        
        $this->_initLog($request->getParam('debug'));
    }

    /**
     * initialize Tracking_Logger
     *
     * @param bool $debug            
     */
    protected function _initLog ($debug = FALSE)
    {
        /* init logger */
        $log = Tracking_Logger::getInstance();
        $log->registerShutdown()->setDebug($debug);
        
        $this->_logger = $log;
    }

    /**
     * run tracking
     */
     public function run()
     {
        $keyPrefix = $this->_request->getServer('SERVER_ADDR') . '-' . $this->_request->getClientIp();

        /** set request id for normal page visit log */
        $this->_session->setRequestId(Mezi_Utility::generateUniqueId('requestId' . $keyPrefix));

        /** asynchronous page will not update request id */
        if ($this->_request->isRedirPage() || $this->_request->isSpecialPage()){
            $this->_session->setUpdateRequestId(FALSE);
        }
		
        // get product # and SL # from front end UI start - add by sanp 2011-10-9
		$getProdcnt = $this->_request->getParam("getprodcnt");
		if ($getProdcnt == 'yes') {
			$this->_session->setIsLanding(TRUE);
		}
		
		// Add logic to separate traffic for product related (TraType=yp) andnon-product related (TraType=ynp)
		// @see bugzilla ticket id 262596
		if ($this->_request->getServer('SCRIPT_URL') == '/search.php') {
	    	$traType = $this->_request->getParam("TraType");
	    	if (in_array($traType, array('yp', 'ynp')) || 
	    		($this->_session->getTraType() != '' && $traType == '')) {
	    		$this->_session->setIsLanding(TRUE);
	    	}
		}
        
        $source = $this->_request->getSource();
        if (!$this->_session->isLanding() && $source!='' && $source!=$this->_session->getSource()) {
        	$this->_session->setIsLanding(TRUE);
        }

        if ($this->_session->isLanding()) {
            if($this->_request->getParam('track_traffic') === 'test') {      // for testing
                $trafficType = 0;
            } else {
            	$trafficType = $this->_getTrafficType();
            }

            /** initialize session */
            $this->_session->setSessionId(Mezi_Utility::generateUniqueId('sessionId' . $keyPrefix))
                 ->setTrafficType($trafficType) //fraud strategy
                 ->setSource($this->_request->getSource())
                 ->setSourceGroup($this->_request->getSourceGroup())
                 ->setOfferClick('')
                 ->setSponsorClick('')
                 ->setTraType($traType)
                 ->setProdcnt($getProdcnt)
                 ->setMobileTrafficType();
            if (NULL===$this->_session->getTestKey()) {
                $this->_session->newTestKey();
            }

            /** For SEM Would like to add referer based yahoo subtag mapping **/
            $this->_session->setReferer($this->_request->getHttpReferer());

            /** log incoming & user session */
            //$this->_logLanding();
        }
            // SMJP is testing on overture_seoFashion traffic for AE Tag(Original Url cookie change)
            // if (preg_match('/^overture_SEOFashion/i', $this->_session->getSource())) {
            
        $landingUrl = 'http://' . $this->_HttpHost . $this->_request->getServer('REQUEST_URI');
        
        if ($this->_session->isLanding()) {
            $this->_logLanding();
            $this->set_orig_url($landingUrl);
        } else {
            if ($this->_session->getRedirectType() != '301') {
                if (! empty($this->_referer)) {
                    $referer = parse_url($this->_referer);
                    if (stripos($referer['host'], $this->_HttpHost) === false) {
                        $this->set_orig_url($landingUrl);
                    }
                }
            } else {
                $this->_session->setRedirectType('0');
            }
        }

        $this->_logPageVisit();
    }

    private function set_orig_url ($landingUrl)
    {
       if (! $this->is_special_url($landingUrl)) {
            setcookie('_orig_url', $landingUrl, 0, '/', preg_replace('/^(www|dev|beta)[0-9]*\./i', '', $this->_HttpHost));
            $_COOKIE['_orig_url'] = $landingUrl;
        }
    }
    
    private function is_special_url ($landingUrl)
    {
        $arr = array(
                'async_',
                'popup_',
                '.jpg',
                '.png',
                '.gif',
                '.css',
                '.js'
        );
        foreach ($arr as $v) {
            if (stripos($landingUrl, $v) !== false) {
                return true;
            }
        }
        return false;
    }
    
    private function debugInfo ()
    {
        $landingUrl = 'http://' . $this->_HttpHost . $this->_request->getServer('REQUEST_URI');
        $arr = array(
                'referer' => $this->_referer,
                'landingUrl' => $landingUrl,
                'httpHost' => $this->_HttpHost,
                'redirectType'=>$this->_session->getRedirectType(),
                'source'=>$this->_session->getSource(),
                'testKey'=>$this->_session->getTestKey(),
                'isLanding'=>$this->_session->isLanding()
        );
    
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    /**
     * convert encoding
     *
     * @param string $str            
     * @return string
     */
    private function _convertEncoding ($str)
    {
        $str = urldecode($str);
        if (mb_detect_encoding($str, "UTF-8,SJIS") == "SJIS") {
            $str = mb_convert_encoding($str, "UTF-8", "UTF-8,SJIS");
        }
        return urlencode($str);
    }
    
    function checkUrl ($redirUrl)
    {
        // check redirect by smjp client side
        require_once TRACKING_ROOT_PATH . '/../../../library/Tracking/Helper.php';
        $trackingHelper = new Tracking_Helper();
        $checkedUrl = $trackingHelper->checkRedirect();
        if ($checkedUrl) {
            return $checkedUrl;
        }
        return $redirUrl;
    }

    /**
     * normalize the request uri
     * remove source tag
     *
     * @return void
     */
     public function normalize()
     { 
		/** filter source & redirect if landing from market SEM traffic */
		if (!preg_match('/^google_001\+001/', $this->_request->getSource()) && !preg_match('/^overture_JP13/', $this->_request->getSource())) {
			if ($this->_session->isLanding() && ! $this->_request->isSpecialPage() && $this->_request->hasSource()) {
	            /** for SEM traffic search, check is realSearch use it */
	
	            $this->_session->setKeyword(urldecode($this->_convertEncoding($this->_request->getKeyword())));
	
				//required by Martin on SMJP web performance opt to remove the 2nd 301 redirect for url correcting. --9/24/2011
				$redirUrl = $this->_request->normalize();
				if (preg_match("/^\/search\.php\/?.*?[?|&]q=([^&]+)/", $redirUrl, $kwmatches)) {
					// redirect to correct search url with http 301 status code
					$keyword = $this->_convertEncoding($kwmatches[1]);
					$redirUrl = '/'. 'se--qq-'. trim($keyword). '.html';
				}
				//end change --9/24/2011
				
				$this->_session->setRedirectType('301')->update();
	
	            Tracking_Response::getInstance()->setRedirect($this->checkUrl($redirUrl), 301)->sendHeaders();
	            exit;
	        }else{
				//required by Martin to remove all parameters to make a redirect.
				$redirUrl = $this->_request->normalize();
				if (preg_match("/^\/search\.php\/?.*?[?|&]q=([^&]+)/", $redirUrl, $kwmatches)) {
					// redirect to correct search url with http 301 status code
					$keyword = $this->_convertEncoding($kwmatches[1]);
					$redirUrl = '/'. 'se--qq-'. trim($keyword). '.html';
					
					$this->_session->setRedirectType('301')->update();
					
					Tracking_Response::getInstance()->setRedirect($this->checkUrl($redirUrl), 301)->sendHeaders();
					exit;
				}
				//end change --9/24/2011
			}
			
			if (! $this->_request->isSpecialPage() && $this->_request->hasGAParam()) {
			    $this->_session->setRedirectType('301')->update();
			    die(Tracking_Response::getInstance()->setRedirect($this->_request->normalize(), 301)->sendHeaders());
			}
		}
        /** update session cookies */
        Tracking_Session::getInstance()->update();
    }

    protected function _callTQService ()
    {
        $request = $this->_request;
        $session = $this->_session;
        
        $sessionid = $session->getSessionId();
        $visittime = date('Y-m-d H:i:s');
        $website = $session->getSiteId();
        $visitip = $request->getClientIp();
        $pagevisitorder = $session->getVisitOrder();
        $requesturi = urlencode($request->toString());
        $httpreferer = urlencode($request->getHttpReferer());
        $httpuseragent = urlencode($request->getUserAgent());
        $httpstatus = '200'; // todo change to actual one
        $randstr = $session->getPreRequestId();
        $currandstr = $session->getRequestId();
        $isincoming = '0';
        if ($this->_session->isLanding()) {
            $isincoming = '1';
        }
        $query = "sessionid=$sessionid&visittime=$visittime&website=$website&visitip=$visitip&pagevisitorder=$pagevisitorder&requesturi=$requesturi&httpreferer=$httpreferer&httpuseragent=$httpuseragent&httpstatus=$httpstatus&randstr=$randstr&currandstr=$currandstr&isincoming=$isincoming";
        
        $res = $this->curl_post(Mezi_Config::getInstance()->tracking->fsapi->uri, $query);
        
        $arr = array();
        if (strstr($res, 'message=successful')) {
            $res = substr(trim($res), 1, - 1);
            $res = explode(',', $res);
            // var_dump($res);
            
            foreach ($res as $val) {
                if (strstr($val, 'incomingValidKey=')) {
                    $arr['incomingValidKey'] = trim(str_replace('incomingValidKey=', '', $val));
                }
                
                if (strstr($val, 'pvValidKey=')) {
                    $arr['pvValidKey'] = trim(str_replace('pvValidKey=', '', $val));
                }
                
                if (strstr($val, 'original_sessionid=')) {
                    $arr['original_sessionid'] = trim(str_replace('original_sessionid=', '', $val));
                }
                
                if (strstr($val, 'sessionid=')) {
                    $arr['sessionid'] = trim(str_replace('sessionid=', '', $val));
                }
            }
        }
        return $arr;
    }

    function curl_post ($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $fields_string .= "{$key}={$value}&";
            }
        } else {
            $fields_string = $params;
        }
        
        rtrim($fields_string, '&');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}