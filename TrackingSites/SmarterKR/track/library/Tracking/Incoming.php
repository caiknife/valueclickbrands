<?php
/**
 * Tracking Incoming
 *
 * @category   Tracking
 * @package    Tracking_Incoming
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Incoming.php,v 1.1 2012/07/17 07:34:22 she Exp $
 */

/**
 * Tracking Incoming
 *
 * @category   Tracking
 * @package    Tracking_Incoming
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
    
    protected $_HttpHost;

    /**
     * detect fraud
     *
     * @param Tracking_Session $session
     * @return integer  traffic type
     */
    protected function _getTrafficType()
    {
        $config   = Mezi_Config::getInstance()->tracking->service->strategy;
        $strategy = new Tracking_Strategy(empty($config) ? null : $config->toArray());

        $ip        = $this->_request->getClientIp();
        $userAgent = $this->_request->getUserAgent();

        if ($strategy->isGoodUserAgent($userAgent)) {
            $trafficType = Tracking_Constant::TRAFFIC_GOOD_USERAGENT;   // -11
        } elseif ($strategy->isGoodIp($ip)) {
            $trafficType = Tracking_Constant::TRAFFIC_GOOD_IP;          // -12
        } else if ($strategy->isBadUserAgent($userAgent)) {
            $trafficType = Tracking_Constant::TRAFFIC_BAD_USERAGENT;    // -1
        } elseif ($strategy->isEmptyUserAgent($userAgent)) {
            $trafficType = Tracking_Constant::TRAFFIC_EMPTY_USERAGENT;  // -3
        } elseif ($strategy->isBadIp($ip)) {
            $trafficType = Tracking_Constant::TRAFFIC_BAD_IP;           // -2
        } elseif ($strategy->isPrivateIp($ip)) {
            $trafficType = Tracking_Constant::TRAFFIC_PRIVATE_IP;       // -4

        } else {
            $trafficType = Tracking_Constant::TRAFFIC_NORMAL;           // 0
        }

        return $trafficType;
    }

    /**
     * log incoming and user session
     *
     * @param Tracking_Session $session
     */
    protected function _logLanding()
    {
        $session = $this->_session;
        $request = $this->_request;

        $userId   = $session->getUserId();
        if (empty($userId)) {
            $keyPrefix = $request->getServer('SERVER_ADDR') . '-' . $request->getClientIp();
            $userId = $session->generateUniqueId('userId' . $keyPrefix);
			$userId = date("ymdH").substr($userId,8);
            $session->setUserId($userId); //update new userid
        }

        $incomingLog = array(
            'userID'        => $userId, //new UserId
            'visitIP'       => $request->getClientIp(),
            'httpReferer'   => $request->getHttpReferer(),
            'httpUserAgent' => $request->getUserAgent(),
            'requestUri'    => $request->toString(),    //can use (string)$request on php 5.2+
            'valid'         => $session->getTrafficType(),
            'pageType'      => $request->getPageType(),

            'channelID'     => $this->_channelId,
            'categoryID'    => $this->_categoryId,
            'productID'     => $this->_productId,

            'source'        => $session->getSource(),
            'sourceGroup'   => $session->getSourceGroup(),
            'testKey'       => $session->getTestKey(),
        );

        $this->_logger->incoming($incomingLog);
    }

    /**
     * log page visit
     *
     * @param Tracking_Session $session
     */
    protected function _logPageVisit()
    {
        $request = $this->_request;
        $session = $this->_session;

        $pageVisitLog = array(
            'visitIP'        => $request->getClientIp(),
            'httpUserAgent'  => $request->getUserAgent(),
            'pageType'       => $request->getPageType(),
            'trafficType'    => $session->getTrafficType(),

            'requestUri'     => $request->toString(),
            'httpReferer'    => $request->getHttpReferer(),
            'pagevisitorder' => $session->getVisitOrder(),

            'channelId'      => $this->_channelId,
            'productId'      => $this->_productId,
            'categoryId'     => $this->_categoryId,
        );
        $this->_logger->pageVisit($pageVisitLog);
    }

    /**
     * constructor
     *
     * @return Tracking_Incoming
     */
    public function __construct()
    {
        //init session
        $this->_session = Tracking_Session::getInstance();
        $this->_session->setSiteId((integer) Mezi_Config::getInstance()->tracking->site->id);

        $request = new Tracking_Request_Incoming();

        $this->_referer     = $request->getHttpReferer();
        $this->_channelId   = $request->getChannelId();
        $this->_productId   = $request->getProductId();
        $this->_categoryId  = $request->getCategoryId();
        $this->_request     = $request;
        
        $this->_HttpHost    = $request->getServer('HTTP_HOST');
        
        $this->_initLog($request->getParam('debug'));
    }

    /**
     * initialize Tracking_Logger
     *
     * @param bool $debug
     */
    protected function _initLog($debug = FALSE)
    {
        /* init logger */
        $log = Tracking_Logger::getInstance();
        $log->registerShutdown()
            ->setDebug($debug);

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
                 ->setSponsorClick('');
            if (NULL===$this->_session->getTestKey()) {
                $this->_session->newTestKey();
            }

            /** For SEM Would like to add referer based yahoo subtag mapping **/
            $this->_session->setReferer($this->_request->getHttpReferer());

            /** log incoming & user session */
            // $this->_logLanding();
        }
        
        $landingUrl = 'http://' . $this->_HttpHost . $this->_request->getServer('REQUEST_URI');    
        if ($this->_session->isLanding()) {
            $this->_logLanding();
            $this->_set_orig_url($landingUrl);
        } else {
            if ($this->_session->getRedirectType() != '301') {
                if (!empty($this->_referer)) {
                    $referer = parse_url($this->_referer);
                    if (stripos($referer['host'], $this->_HttpHost) === false) {
                        $this->_set_orig_url($landingUrl);
                    }
                }
            } else {
                $this->_session->setRedirectType('0');
            }
        }

        $this->_logPageVisit();
    }

    /**
     * convert encoding
     *
     * @param string $str
     * @return string
     */
    private function _convertEncoding($str)
    {
        $str = urldecode($str);
        if(mb_detect_encoding($str, "UTF-8, EUC-KR") == "EUC-KR") {
            $str = mb_convert_encoding($str, "UTF-8", "UTF-8, EUC-KR");
        }
        return urlencode($str);
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
        if ($this->_session->isLanding() &&
            !$this->_request->isSpecialPage() &&
            $this->_request->hasSource()) {
            /** for SEM traffic search, check is realSearch use it */

            $this->_session->setKeyword(urldecode($this->_convertEncoding($this->_request->getKeyword())))->update();
            $this->_session->setRedirectType('301')->update();
            
            Tracking_Response::getInstance()
                             ->setRedirect($this->_request->normalize(), 301)
                             ->sendHeaders();
            exit;
        }
        if (!$this->_request->isSpecialPage() && $this->_request->hasGAParam()) {
            $this->_session->setRedirectType('301')->update();
            die(Tracking_Response::getInstance()->setRedirect(
                    $this->_request->normalize(), 301
                )->update()
            );
        }
        /** update session cookies */
        $this->_session->update();
    }
    
    private function _set_orig_url($landingUrl) 
    {
        if (!$this->_is_special_url($landingUrl)) {
            // trans encoding
            $landingUrl = $this->_trans_encoding($landingUrl);
            
            setcookie('_orig_url', $landingUrl, 0, '/', preg_replace('/^(www|dev|beta)[0-9]*\./i', '', $this->_HttpHost));
            $_COOKIE['_orig_url'] = $landingUrl;
        }
    }
    
    private function _is_special_url($landingUrl) 
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
    
    private function _trans_encoding($landingUrl) 
    {
        $urls = parse_url($landingUrl);
        parse_str($urls['query'], $query);
        foreach ($query as &$q) {
            //$q = iconv('shift-jis', 'utf-8', $q);
            if (mb_detect_encoding($q, "UTF-8, EUC-KR") == "EUC-KR") {
                $q = mb_convert_encoding($q, "UTF-8", "UTF-8, EUC-KR");
            }
        }
        $urls['query'] = http_build_query($query);
        return $this->_buildUrl($urls);
    }
    
	/**
     * build url for trans-encoded url
     * @param array $urls
     * @return string
     */
    private function _buildUrl($urls)
    {
        $url = $urls['path'];
        if (isset($urls['query'])) {
            $url = $url . '?' . $urls['query'];
        }
        if (isset($urls['host'])) {
            $url = $urls['host'] . $url;
        }
        if (isset($urls['scheme'])) {
            $url = $urls['scheme'] . '://' . $url;
        }
        return $url;
    }
}