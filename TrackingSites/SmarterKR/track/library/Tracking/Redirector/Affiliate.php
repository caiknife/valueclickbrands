<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Affiliate.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
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
     * generate a unique outgoing id with prefix, max length = 32
     *
     * @param int       $length
     * @param string    $prefix
     * @return string
     */
    private function _generateOutgoingId($length = 32, $prefix='')
    {
        return substr($prefix . md5(uniqid(mt_rand(), true)), 0, (int) $length);
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
        switch(strtoupper($this->_destinedSite)) {
            case 'YAHOOSHOPPING':
                $affiliateTag   = 'vcptn';
                break;

            case 'SHOPPING':
                $affiliateTag   = 'op';
                break;

            default:
                $affiliateTag   = '';
                break;
        }

        if(!empty($affiliateTag)) {
            $siteId     = (int) Mezi_Config::getInstance()->tracking->site->id;
            $outgoingId = 'a' . $this->_generateOutgoingId(24, $siteId . date('md'));   // 'a' means sdc api

            if(stripos($this->_destinedUrl, "&{$affiliateTag}=") !== false || stripos($this->_destinedUrl, "?{$affiliateTag}=") !== false ) {
                $this->_destinedUrl  = preg_replace("/([\?|&]){$affiliateTag}=([^&]*)/i", '${1}' . "{$affiliateTag}=" . $outgoingId, $this->_destinedUrl);
            } else {
                $this->_destinedUrl .= "&{$affiliateTag}={$outgoingId}";
            }
        }

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