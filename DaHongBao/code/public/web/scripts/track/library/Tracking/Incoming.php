<?php
/**
 * Tracking Incoming
 *
 * @category   Tracking
 * @package    Tracking_Incoming
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Incoming.php,v 1.1 2013/04/15 10:58:19 rock Exp $
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

        $incomingLog = array(
            'userId'        => $session->getUserId(),
            'visitIp'       => $request->getClientIp(),
            'httpReferer'   => $request->getHttpReferer(),
            'httpUserAgent' => $request->getUserAgent(),
            'requestUri'    => $request->toString(),    //can use (string)$request on php 5.2+
            'valid'         => $session->getTrafficType(),
            'pageType'      => $request->getPageType(),

            'channelId'     => $this->_channelId,
            'categoryId'    => $this->_categoryId,
            'productId'     => $this->_productId,

            'source'        => $session->getSource(),
            'sourceGroup'   => $session->getSourceGroup(),
            'testKey'       => $session->getUiType(),
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
            'visitIp'        => $request->getClientIp(),
            'httpUserAgent'  => $request->getUserAgent(),
            'pageType'       => $request->getPageType(),
            'trafficType'    => $session->getTrafficType(),

            'requestUri'     => $request->toString(),
            'httpReferer'    => $request->getHttpReferer(),
            'pageVisitOrder' => $session->getVisitOrder(),

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

        $this->_initLog($request->getParam('debug'));
    }

    /**
     * initialize Tracking_Logger
     *
     * @param bool $debug
     */
    protected function _initLog($debug = FALSE)
    {
        $this->_logger = Tracking_Logger::getInstance();
        $this->_logger->registerShutdown()->setDebug($debug);
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
        if ($this->_request->isRedirPage() || $this->_request->isSpecialPage()) {
            $this->_session->setUpdateRequestId(FALSE);
        }

        $source = $this->_request->getSource();
        if (!$this->_session->isLanding() && $source!='' && $source!=$this->_session->getSource()) {
        	$this->_session->setIsLanding(TRUE);
        }

        if ($this->_session->isLanding()) {
            if (!$this->_session->getUserId()) {
                $this->_session->setUserId(Mezi_Utility::generateUniqueId('userId' . $keyPrefix));
            }

            if (!$this->_session->getUiType()) {
                $this->_session->setUiType(mt_rand(1, 20));
            }

            if($this->_request->getParam('track_traffic') === 'test') {      // for testing
                $trafficType = 0;
            } else {
                $trafficType = $this->_getTrafficType();
            }

            /** initialize session */
            $this->_session->setSessionId(Mezi_Utility::generateUniqueId('sessionId' . $keyPrefix))
                 ->setTrafficType($trafficType)
                 ->setSource($this->_request->getSource())
                 ->setSourceGroup($this->_request->getSourceGroup())
                 ->setOfferClick('')
                 ->setSponsorClick('');

            /** For SEM Would like to add referer based yahoo subtag mapping **/
            $this->_session->setLandingReferer($this->_request->getHttpReferer());

            /** log incoming & user session */
            $this->_logLanding();
        }

        /** log page visit if normal traffic */
        $this->_logPageVisit();

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
            $this->_session->setKeyword($this->_request->getKeyword())->update();
            Tracking_Response::getInstance()
                             ->setRedirect($this->_request->normalize(), 301)
                             ->sendHeaders();
            exit;
        }

        /** update session cookies */
        $this->_session->update();
    }
}