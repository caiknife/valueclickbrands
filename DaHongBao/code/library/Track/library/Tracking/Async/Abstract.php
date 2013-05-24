<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Async
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Abstract.php,v 1.1 2013/04/15 10:56:35 rock Exp $
 */

/**
 * Base class for Tracking_Async
 *
 * @category   Tracking
 * @package    Tracking_Async
 */
abstract class Tracking_Async_Abstract
{

    /**
     * current request
     *
     * @var Tracking_Request_Outgoing
     */
    protected $_request = NULL;

    /**
     * http response
     *
     * @var Tracking_Response
     */
    protected $_response = NULL;

    /**
     * current session
     *
     * @var Tracking_Session
     */
    protected $_session = NULL;

    /**
     * request logger
     *
     * @var Tracking_Logger
     */
    protected $_logger = NULL;

    /**
     * Channel Id
     *
     * @var integer
     */
    protected $_channelId = 0;

    /**
     * destined url of the current request
     *
     * @var string
     */
    protected $_destinedUrl = '';

    /**
     * HTTP response code
     *
     * @var integer
     */
    protected $_httpResponseCode = 301;

    /**
     * contructor
     *
     * @return Tracking_Async_Abstract
     */
    public function __construct($requestUri = NULL)
    {
        $this->_request     = new Tracking_Request_Outgoing($requestUri);
        $this->_response    = Tracking_Response::getInstance();
        $this->_session     = Tracking_Session::getInstance();
        $this->_logger      = Tracking_Logger::getInstance();

        $this->_parseRequest();
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        $this->_channelId   = $this->_getChannelId();
    }

    /**
     * get channel id from uri
     *
     * @return integer
     */
    protected function _getChannelId() {
        return $this->_request->getParam(Tracking_Uri::CHANNEL_ID, 0);
    }

    /**
     * log error
     *
     * @param string $remark
     */
    protected function _logError($remark) {
        $log = array(
            'remark' => $remark,
            'requesturi' => $this->_request->getRequestUri(),
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
        return TRUE;
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
        echo '<!-- If you see this text, you must have too much time~ -->';
    }

    /**
     * dispatch current request
     * log the dispatch and return the response
     *
     * @return void
     */
    public function dispatch()
    {
        if ($this->_preDispatch()) {
            $this->_doDispatch();
        }

        $this->_postDispatch();
    }
}