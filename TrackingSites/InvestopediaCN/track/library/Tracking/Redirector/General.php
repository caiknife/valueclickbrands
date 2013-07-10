<?php

/**
 * parse the general url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_General extends Tracking_Redirector_Abstract
{
    /**
     * keyword
     *
     * @var string
     */
    private $_keyword = '';

    /**
     * product id
     *
     * @var string
     */
    private $_productId     = '';

	/**
	 * other redirector type
	 *
	 * @var string
	 */
	private $_redirectorType = '';

    /**
     * get keyword
     *
     * @return string
     */
    private function _getKeyword() {
        return $this->_request->getParam(Tracking_Uri::KEYWORD);
    }

    /**
     * get product id from uri
     *
     * @return string
     */
    private function _getProductId() {
        return $this->_request->getParam(Tracking_Uri::PRODUCT_ID, '');
    }

    /**
     * get redirect type
     *
     * @return string
     */
    private function _getRedirectorType()
    {
        return $this->_request->getParam(Tracking_Uri::BUILD_TYPE, 'UNKNOWN');
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_keyword         = $this->_getKeyword();
        $this->_productId       = $this->_getProductId();
        $this->_redirectorType  = $this->_getRedirectorType();
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch ()
     */
    protected function _doDispatch()
    {
        $log = array(
            'channelid'     => $this->_channelId,
            'categoryid'    => $this->_categoryId,
            'keyword'       => $this->_keyword,
            'productid'     => $this->_productId,
            'redirtype'     => $this->_redirectorType,
            'desturl'       => $this->_destinedUrl,
        );
        $this->_logger->outgoing($log);
    }
}