<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Abstract.php,v 1.1 2013/07/10 01:34:46 jjiang Exp $
 */

/**
 * Base class for Tracking_Redirector
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
abstract class Tracking_Redirector_Abstract
{
    /**
     * tracking configuration
     *
     * @var Mezi_Config
     */
    protected $_config      = NULL;

    /**
     * current request
     *
     * @var Tracking_Request_Outgoing
     */
    protected $_request     = NULL;

    /**
     * http response
     *
     * @var Tracking_Response
     */
    protected $_response    = NULL;

    /**
     * current session
     *
     * @var Tracking_Session
     */
    protected $_session     = NULL;

    /**
     * request logger
     *
     * @var Tracking_Logger
     */
    protected $_logger      = NULL;

    /**
     * Channel Id
     *
     * @var integer
     */
    protected $_channelId   = 0;

    /**
     * Category Id
     *
     * @var integer
     */
    protected $_categoryId   = 0;

    /**
     * destined url of the current request
     *
     * @var string
     */
    protected $_destinedUrl = '';

    /**
     * cost per click
     *
     * @var float
     */
    protected $_revenue      = 0;

    /**
     * HTTP response code
     *
     * @var integer
     */
    protected $_httpResponseCode = 301;

    /**
     * @var array
     */
    protected $_outgoingLog;

    protected $_needRedirect = true;

    protected $_needLog = true;

    /**
     * constructor
     *
     * @param string $requestUri
     * @return Tracking_Redirector_Abstract
     */
    public function __construct($requestUri = NULL)
    {
        $this->_config      = Mezi_Config::getInstance()->tracking;
        $this->_request     = new Tracking_Request_Outgoing($requestUri);
        $this->_response    = Tracking_Response::getInstance();
        $this->_session     = Tracking_Session::getInstance();
        $this->_logger      = Tracking_Logger::getInstance();

        $this->_parseRequest();
    }

    /**
     * parse current request
     *
     */
    protected function _parseRequest()
    {
        $this->_destinedUrl = $this->_getDestinedUrl();
        $this->_channelId   = $this->_getChannelId();
        $this->_categoryId  = $this->_getCategoryId();
        $this->_revenue     = $this->_getRevenue();
    }

    /**
     * get channel id from uri
     *
     * @return integer
     */
    protected function _getChannelId() {
        return (integer) $this->_request->getParam(Tracking_Uri::CHANNEL_ID, 0);
    }

    /**
     * get category id from uri
     *
     * @return integer
     */
    protected function _getCategoryId() {
        return $this->_request->getParam(Tracking_Uri::CATEGORY_ID, '');
    }

    /**
     * get the destined url
     *
     * @return string
     */
    protected function _getDestinedUrl()
    {
        return $this->_request->getParam(Tracking_Uri::DESTINED_URL);
    }

    /**
     * get CPC id from uri
     *
     * @return float
     */
    private function _getRevenue() {
        return (float) $this->_request->getParam(Tracking_Uri::CPC);
    }

    /**
     * log error
     *
     * @param string $remark
     */
    protected function _logError($remark) {
        $log = array(
            'remark' => $remark,
            'requestUri' => $this->_request->getRequestUri(),
            'referer'    => $this->_request->getHttpReferer(),
        );

        $this->_logger->Error($log);
    }

    /**
     * Pre-dispatch routines. Called before dispatch request. If return FALSE,
     * it will skip processing the current request.
     *
     * @return boolean
     */
    protected function _preDispatch()
    {
        if ($this->_destinedUrl) {
            return TRUE;
        } else {
            $this->_logError('lost Outgoing destined url');
            $this->_destinedUrl = '/';

            return FALSE;
        }
    }

    /**
     * do the actual dispatch
     *
     * @return void
     */
    abstract protected function _doDispatch();

    /**
     * Post-dispatch routines. Called after dispatch request.
     * Common usages for postDispatch() include redirecting destined url, etc.
     *
     * @return void
     */
    protected function _postDispatch()
    {
        if ($this->_needRedirect) {
            if ($this->_request->get('debug') != 'track') {
                $this->_response->setRedirect($this->_destinedUrl, $this->_httpResponseCode)->sendResponse();
            }
        } else {
            include TRACKING_ROOT_PATH .'/scripts/redir.phtml';
        }
    }

    /**
     * dispatch current request
     * log the dispatch and return the response
     *
     * @param boolean $needRedirect
     * @param boolean $needLog
     * @return void
     */
    public function dispatch($needRedirect = true, $needLog = true)
    {
        $this->_needRedirect = (boolean) $needRedirect;
        $this->_needLog      = (boolean) $needLog;
        if ($this->_preDispatch()) {
            $this->_doDispatch();
        }

        $this->_postDispatch();
    }
}