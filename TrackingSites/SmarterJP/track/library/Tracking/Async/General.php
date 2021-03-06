<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Async
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: General.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * parse the general url and log it
 *
 * @category   Mezi
 * @package    Mezi_Async
 */
class Tracking_Async_General extends Tracking_Async_Abstract
{

	/**
	 * other redirector type
	 *
	 * @var string
	 */
	private $_asyncType = '';

    /**
     * get redirect type
     *
     * @return string
     */
    private function _getAsyncType()
    {
        return $this->_request->getParam(Tracking_Uri::ASYNC_TYPE, 'UNKNOWN');
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_asyncType = $this->_getAsyncType();
    }

    /**
     * @see Tracking_Async_Abstract->_doDispatch ()
     */
    protected function _doDispatch()
    {
        if (!Tracking_Session::getInstance()->isNormalTraffic()) {
            return ;
        }

        $log = array(
            'redirtype' => $this->_asyncType,
            'desturl'   => $this->_destinedUrl,
        );
        $this->_logger->outgoing($log);
    }
}
?>