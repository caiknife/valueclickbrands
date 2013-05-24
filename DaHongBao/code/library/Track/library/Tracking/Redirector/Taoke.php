<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Taoke.php,v 1.2 2013/04/16 05:09:25 jjiang Exp $
 */

/**
 * parse the taoke url and log it, only for smcn
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Taoke extends Tracking_Redirector_Abstract
{

    /**
     * category id
     *
     * @var integer
     */
    protected $_categoryId    = 0;

    /**
     * destined site
     *
     * @var string
     */
    private $_categoryName  = '';

    /**
     * merchant id
     *
     * @var integer
     */
    private $_merchantId    = 0;

    /**
     * merchant name
     *
     * @var string
     */
    private $_merchantName   = '';

    /**
     * offer id
     *
     * @var integer
     */
    private $_offerId    = 0;

    /**
     * product name
     *
     * @var string
     */
    private $_productName     = 0;

    /**
     * get category id from uri
     *
     * @return integer
     */
    protected function _getCategoryId() {
        return (integer) $this->_request->getParam(Tracking_Uri::CATEGORY_ID, 0);
    }

    /**
     * get category name from uri
     *
     * @return string
     */
    private function _getCategoryName() {
        return $this->_request->getParam(Tracking_Uri::CATEGORY_NAME, 'UNKNOWN');
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
     * get merchant name from uri
     *
     * @return string
     */
    private function _getMerchantName() {
        return $this->_request->getParam(Tracking_Uri::MERCHANT_NAME, '');
    }

    /**
     * get offer id from uri
     *
     * @return integer
     */
    private function _getOfferId() {
        return (integer) $this->_request->getParam(Tracking_Uri::OFFER_ID, 0);
    }

    /**
     * get product name from uri
     *
     * @return string
     */
    private function _getProductName() {
        return $this->_request->getParam(Tracking_Uri::PRODUCT_NAME, '');
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_categoryId      = $this->_getCategoryId();
        $this->_categoryName    = $this->_getCategoryName();
        $this->_merchantId      = $this->_getMerchantId();
        $this->_merchantName    = $this->_getMerchantName();
        $this->_offerId         = $this->_getOfferId();
        $this->_productName     = $this->_getProductName();
    }

    /**
     * @see Tracking_Redirector_Abstract::doDispatch()
     */
    protected function _doDispatch ()
    {
        $logTaokeOutgoing = array(
            'categoryid'    => $this->_categoryId,
            'categoryname'  => $this->_categoryName,
            'merchantid'    => $this->_merchantId,
            'merchantname'  => $this->_merchantName,
            'offerid'       => $this->_offerId,
            'productname'   => $this->_productName,
            'desturl'       => $this->_destinedUrl,
        );
        $this->_logger->TaokeOutgoing($logTaokeOutgoing);
    }

}