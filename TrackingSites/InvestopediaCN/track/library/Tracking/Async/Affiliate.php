<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Async
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Affiliate.php,v 1.1 2013/07/10 01:34:45 jjiang Exp $
 */

/**
 * parse the affiliated url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Async
 */
class Tracking_Async_Affiliate extends Tracking_Async_Abstract
{
    /**
     * click area
     *
     * @var integer
     */
    private $_clickArea     = 0;

    /**
     * destined site
     *
     * @var string
     */
    private $_destinedSite  = '';

    /**
     * merchant id
     *
     * @var integer
     */
    private $_merchantId    = 0;

    /**
     * product id
     *
     * @var integer
     */
    private $_productId     = 0;

    /**
     * get click area id from uri
     *
     * @return integer
     */
    private function _getClickArea() {
        return (integer) $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
    }

    /**
     * get destind site from uri
     *
     * @return string
     */
    private function _getDestindSite() {
        return strtoupper($this->_request->getParam(Tracking_Uri::DESTINED_SITE, 'UNKNOWN'));
    }

    /**
     * get merchant id id from uri
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
        return $this->_request->getParam(Tracking_Uri::PRODUCT_ID, 0);
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_clickArea   = $this->_getClickArea();
        $this->_destinedSite= $this->_getDestindSite();
        $this->_merchantId  = $this->_getMerchantId();
        $this->_productId   = $this->_getProductId();
    }

    /**
     * @see Tracking_Async_Abstract::doDispatch()
     */
    protected function _doDispatch ()
    {
        $logAffiliateOutgoing = array(
            'productid'  => $this->_productId,
            'merchantid' => $this->_merchantId,
            'categoryid' => $this->_categoryId,
            'channelid'  => $this->_channelId,
            'clickarea'  => $this->_clickArea,
            'destsite'   => $this->_destinedSite,
            'desturl'    => $this->_destinedUrl,
            'revenue'    => $this->_revenue,
        );
        $this->_logger->AffiliateOutgoing($logAffiliateOutgoing);
    }
}