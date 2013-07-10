<?php

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
        $log = array(
            'redirtype' => $this->_asyncType,
            'desturl'   => $this->_destinedUrl,
        );
        $this->_logger->outgoing($log);
    }
}
?>