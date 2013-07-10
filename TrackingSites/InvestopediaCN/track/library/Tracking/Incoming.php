<?php

/**
 * Tracking Incoming
 *
 * @category   Tracking
 * @package    Tracking_Incoming
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Incoming.php,v 1.1 2013/07/10 01:34:45 jjiang Exp $
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
     * Tracking Request parser
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
     * file suffix
     * @var Array
     */
    protected $_suffixs = array(
            '.jpg',
            '.ico',
            '.png',
            '.gif',
            '.jpeg',
            '.css',
            '.js',
            '.bmp',
            //'/dbupload_show' //only for ttw on drupal
    );

    /**
     * detect fraud
     *
     * @param Tracking_Session $session            
     * @return integer traffic type
     */
    protected function _getTrafficType ()
    {
        $request = $this->_request;
        $ip = $request->getClientIp();
        $userAgent = $request->getUserAgent();
        
        $strategy = new Tracking_Strategy();
        
        /**
         * -1 robots
         */
        if ($strategy->isRobot($userAgent)) {
            $trafficType = Tracking_Session::TRAFFIC_ROBOT;
        } elseif ($strategy->isFraudIp($ip) || $strategy->isPrivateIp($ip)) {
            /**
             * -2 black ips && internal ignored IP
             */
            $trafficType = Tracking_Session::TRAFFIC_IGNORE_IP;
        } elseif ($strategy->emptyUserAgent($userAgent)) {
            /**
             * -3 fraud user Agent (that is empty user Agent)
             */
            $trafficType = Tracking_Session::TRAFFIC_EMPTY_USERAGENT;
        } else {
            /**
             * 0 normal click
             */
            $trafficType = Tracking_Session::TRAFFIC_NORMAL;
        }
        
        return $trafficType;
    }

    protected function _isValidRequest ()
    {
        $requestUri = $this->_request->getRequestUri();
        foreach ($this->_suffixs as $v) {
            if (stripos($requestUri, $v) !== false) {
                return false;
            }
        }
        return true;
    }
 
    /**
     * log incoming and user session
     *
     * @param Tracking_Session $session            
     */
    protected function _logLanding ()
    {
        if ($this->_isValidRequest()) {
            $session = $this->_session;
            $request = $this->_request;
            
            $userId = $session->getUserId();
            if (empty($userId)) {
                $keyPrefix = $request->getServer('SERVER_ADDR') . '-' . $request->getClientIp();
                $userId = $session->generateUniqueId('userId' . $keyPrefix);
                $userId = date("ymdH") . substr($userId, 8);
                $session->setUserId($userId); // update new userid
            }
            
            $trafficType = 'other';
            if ($session->isMobileTraffic()) {
                $trafficType = 'mobile';
            }
            
            $incomingLog = array(
                    'userId' => $session->getUserId(),
                    'visitIp' => $request->getClientIp(),
                    'httpReferer' => $request->getHttpReferer(),
                    'httpUserAgent' => $request->getUserAgent(),
                    'requestUri' => $request->toString(), // can use (string)$request on php 5.2+
                    'valid' => $session->getTrafficType(),
                    'pageType' => $request->getPageType(),
            
                    'channelId' => $this->_channelId,
                    'categoryId' => $this->_categoryId,
                    'productId' => $this->_productId,
            
                    'source' => $session->getSource(),
                    'sourceGroup' => $session->getSourceGroup(),
                    'testKey' => $session->getTestKey(). '|' . $trafficType
            );
            
            $this->_logger->incoming($incomingLog);
        }
    }
    
    // copy protected function _logPageVisit() to _callTQService()
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

    /**
     * log page visit
     *
     * @param Tracking_Session $session            
     */
    protected function _logPageVisit ()
    {
        if ($this->_isValidRequest()) {
            $request = $this->_request;
            $session = $this->_session;
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

        $request = new Tracking_Request_Incoming();
        
        $this->_referer = $request->getHttpReferer();
        $this->_channelId = $request->getChannelId();
        $this->_productId = $request->getProductId();
        $this->_categoryId = $request->getCategoryId();
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
        $this->_logger = Tracking_Logger::getInstance();
        $this->_logger->registerShutdown()->setDebug($debug);
    }

    /**
     * run tracking
     */
    public function run ()
    {
        $keyPrefix = $this->_request->getServer('SERVER_ADDR') . '-' . $this->_request->getClientIp();
        
        /**
         * set request id for normal page visit log
         */
        $this->_session->setRequestId(Mezi_Utility::generateUniqueId('requestId' . $keyPrefix));
        
        /**
         * asynchronous page will not update request id
         */
        if ($this->_request->isRedirPage() || $this->_request->isSpecialPage()) {
            $this->_session->setUpdateRequestId(FALSE);
        }
        
        $source = $this->_request->getSource();
        if (! $this->_session->isLanding() && $source != '' && $source != $this->_session->getSource()) {
            $this->_session->setIsLanding(TRUE);
        }
        
        if ($this->_session->isLanding()) {
            if ($this->_request->getParam('track_traffic') === 'test') { // for testing
                $trafficType = 0;
            } else {
                $trafficType = $this->_getTrafficType();
            }
            
            /**
             * initialize session
             */
            $this->_session->setSessionId(Mezi_Utility::generateUniqueId('sessionId' . $keyPrefix))
                ->setTrafficType($trafficType)
                ->setSource($this->_request->getSource())
                ->setSourceGroup($this->_request->getSourceGroup())
                ->setOfferClick('')
                ->setSponsorClick('')
                ->setMobileTrafficType();
            
            if (NULL === $this->_session->getTestKey()) {
                $this->_session->setTestKey(mt_rand(1, 100));
            }
            
            /**
             * For SEM Would like to add referer based yahoo subtag mapping *
             */
            $this->_session->setLandingReferer($this->_request->getHttpReferer())->setLandingUri($this->_request->toString());
        
        /**
         * log incoming & user session
         */
           //$this->_logLanding ();
        }
        
        $landingUrl = 'http://' . $this->_HttpHost . $this->_request->getRequestUri();
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
    
   // $landingUrl = 'http://' . $this->_HttpHost . $this->_request->getServer('REQUEST_URI');
      
    private function set_orig_url ($landingUrl)
    {
        if (! $this->is_special_url($landingUrl)) {
            setcookie('_orig_url', $landingUrl, 0, '/', preg_replace('/^([a-z0-9]*)?(www|dev|beta)[0-9]*\./i', '', $this->_HttpHost));
            $_COOKIE['_orig_url'] = $landingUrl;
        }
    }
    
    private function is_special_url ($landingUrl)
    {
        $arr = array_merge(array(
                'async_',
                'popup_'
        ), $this->_suffixs);
        foreach ($arr as $v) {
            if (stripos($landingUrl, $v) !== false) {
                return true;
            }
        }
        return false;
    }

    private function curl_post ($url, $params)
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

    private function sock_post ($url, $query)
    {
        $info = parse_url($url);
        if (array_key_exists('port', $info)) {
            $port = $info['port'];
        } else {
            $port = '80';
        }
        
        $fp = @fsockopen($info['host'], $port, $errno, $errstr, 2);
        // $fp=stream_socket_client($info['host'].':'.$port, $errno, $errstr, 3);
        
        if (empty($info["query"])) {
            $head = "POST " . $info['path'] . " HTTP/1.0\r\n";
        } else {
            $head = "POST " . $info['path'] . "?" . $info['query'] . " HTTP/1.0\r\n";
        }
        
        $head .= "Host: " . $info['host'] . "\r\n";
        $head .= "Referer: http://" . $info['host'] . $info['path'] . "\r\n";
        $head .= "Content-type: application/x-www-form-urlencoded" . "\r\n";
        $head .= "Content-Length: " . strlen(trim($query)) . "\r\n";
        $head .= "\r\n";
        $head .= trim($query);
        
        $write = @fputs($fp, $head);
        while (! feof($fp)) {
            $line = fread($fp, 1024);
            echo $line;
        }
        fclose($fp);
    }

    /**
     * normalize the request uri
     * remove source tag
     *
     * @return void
     */
    public function normalize ()
    {
        /**
         * filter source & redirect if landing from market SEM traffic
         */
        $isSpeciaPage = $this->_request->isSpecialPage();
        if ($this->_session->isLanding() && ! $isSpeciaPage && $this->_request->hasSource()) {
            $this->_session->setKeyword($this->_request->getKeyword());
            
            $this->_session->setRedirectType('301')->update();
            // for SEM traffic search, check is realSearch use it
            die(Tracking_Response::getInstance()->setRedirect($this->_request->normalize(), 301)->sendHeaders());
        }
        
        if (! $isSpeciaPage && $this->_request->hasGAParam()) {
            $this->_session->setRedirectType('301')->update();
            die(Tracking_Response::getInstance()->setRedirect($this->_request->normalize(), 301)->sendHeaders());
        }
        
        /**
         * update session cookies
         */
        $this->_session->update();
    }
}