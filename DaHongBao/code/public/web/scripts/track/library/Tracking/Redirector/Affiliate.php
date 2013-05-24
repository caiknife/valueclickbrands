<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Affiliate.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

/**
 * parse the affiliated url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Affiliate extends Tracking_Redirector_Abstract
{

    /**
     * category id
     *
     * @var integer
     */
    protected $_categoryId    = 0;

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
     * get category id from uri
     *
     * @return integer
     */
    protected function _getCategoryId() {
        return (integer) $this->_request->getParam(Tracking_Uri::CATEGORY_ID, 0);
    }

    /**
     * get destind site from uri
     *
     * @return string
     */
    private function _getDestindSite() {
        return $this->_request->getParam(Tracking_Uri::DESTINED_SITE, 'UNKNOWN');
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

        $this->_categoryId  = $this->_getCategoryId();
        $this->_destinedSite= $this->_getDestindSite();
        $this->_merchantId  = $this->_getMerchantId();
        $this->_productId   = $this->_getProductId();
    }

    /**
     * @see Tracking_Redirector_Abstract::doDispatch()
     */
    protected function _doDispatch ()
    {
        if (strcasecmp($this->_destinedSite, "SHOPPING")==0) {
            $sessionId          = Tracking_Session::getInstance()->getSessionId();
            $requestId          = Tracking_Session::getInstance()->getRequestId();
            $op                 = substr(str_replace(array('+', '/', '='), '', base64_encode(pack('H*', md5($sessionId)))), 0, 3) .
                                  substr(str_replace(array('+', '/', '='), '', base64_encode(pack('H*', md5($requestId)))), 0, 4);
            $this->_destinedUrl = $this->_destinedUrl . '&op=' . $op;
        }

        /** for techDiscount
        $siteName               = (string) $this->_config->site->name;
        $requestId              = (string) $this->_session->getRequestId();
        $connector              = strpos($this->_destinedUrl, '?')===FALSE ? '?': '&';
        $this->_destinedUrl     = "{$this->_destinedUrl}{$connector}source={$siteName}_{$requestId}";
        */
        if (Tracking_Session::getInstance()->isNormalTraffic()) {
            $logAffiliateOutgoing = array(
                'productid'  => $this->_productId,
                'merchantid' => $this->_merchantId,
                'categoryid' => $this->_categoryId,
                'channelid'  => $this->_channelId,
                'clickarea'  => 0,
                'destsite'   => $this->_destinedSite,
                'desturl'    => $this->_destinedUrl,
                'revenue'    => $this->_revenue,
            );
            $this->_logger->AffiliateOutgoing($logAffiliateOutgoing);
        }
    }

}