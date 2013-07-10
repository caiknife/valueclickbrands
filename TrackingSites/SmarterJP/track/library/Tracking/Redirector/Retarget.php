<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Retarget.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * parse the general url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Retarget extends Tracking_Redirector_Abstract
{
    /**
     * keyword
     *
     * @var string
     */
    private $_keyword = '';

    /**
     * merchant id
     *
     * @var integer
     */
    private $_merchantId = '';

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
     * get merchant id from uri
     *
     * @return integer
     */
    private function _getMerchantId() {
        return (integer) $this->_request->getParam(Tracking_Uri::MERCHANT_ID, 0);
    }

    /**
     * get product id from uri
     *
     * @return integer
     */
    private function _getProductId() {
        return (integer) $this->_request->getParam(Tracking_Uri::PRODUCT_ID, 0);
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

        $this->_categoryId      = $this->_getCategoryId();
        $this->_keyword         = $this->_getKeyword();
        $this->_merchantId      = $this->_getMerchantId();
        $this->_productId       = $this->_getProductId();
        $this->_redirectorType  = $this->_getRedirectorType();
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch ()
     */
    protected function _doDispatch()
    {
        
        //$site = 'http://www.couponmountain.com';
        //$aParam['requestid'] = Tracking_Session::getInstance()->getRequestId();
        //$aParam['from'] = 'smjp';
        
        $log = array(
            'channelid'     => $this->_channelId,
            'categoryid'    => $this->_categoryId,
            'keyword'       => $this->_keyword,
            'merchantid'    => $this->_merchantId,
            'productid'     => $this->_productId,
            'redirtype'     => $this->_redirectorType,
            'desturl'       => $this->_destinedUrl,
        );
        $this->_logger->outgoing($log);
    }
}