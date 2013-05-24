<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Coupon
 * @author     Ben Yan <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Coupon.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

/**
 * parse the coupon's url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Coupon extends Tracking_Redirector_Abstract
{

    /**
     * business type, CPA/CPC/FREE
     *
     * @var string
     */
    private $_businessType = '';

    /**
     * the beacon of coupon
     *
     * @var string
     */
    private $_couponBeacon = '';

    /**
     * coupon Id
     *
     * @var integer
     */
    private $_couponId = 0;

    /**
     * customer Id
     *
     * @var integer
     */
    private $_customerId = 0;

    /**
     * display position
     *
     * @var integer
     */
    private $_displayPosition = 0;

    /**
     * merchant count
     *
     * @var integer
     */
    private $_merchantCount = 0;

    /**
     * merchant id
     *
     * @var integer
     */
    private $_merchantId = 0;

    /**
     * outgoing id
     *
     * @var string
     */
    private $_outgoingId = '';

    /**
     * outgoing type
     *
     * @var integer
     */
    private $_outgoingType = 0;

    /**
     * get the beacon of coupon
     *
     * @return string
     */
    private function _getBusinessType() {
        return strtoupper($this->_request->getParam(Tracking_Uri::PAYMENT_TYPE, ''));
    }

    /**
     * get business type, CPA/CPC/FREE
     *
     * @return string
     */
    private function _getCouponBeacon() {
        return $this->_request->getParam(Tracking_Uri::COUPON_BEACON, '');
    }

    /**
     * get coupon id
     *
     * @return integer
     */
    private function _getCouponId()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::COUPON_ID, 0);
    }

    /**
     * get customer id
     *
     * @return integer
     */
    private function _getCustomerId()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::CUSTOMER_ID, 0);
    }

    /**
     * get display position
     *
     * @return integer
     */
    private function _getDisplayPosition()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::DISPLAY_POSITION, 0);
    }

    /**
     * get merchant count
     *
     * @return integer
     */
    private function _getMerchantCount()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::MERCHANT_COUNT, 0);
    }

    /**
     * get merchant id
     *
     * @return integer
     */
    private function _getMerchantId()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::MERCHANT_ID, 0);
    }

    /**
     * get outgoing type
     *
     * @return integer
     */
    private function _getOutgoingType()
    {
        $result = $this->_session->getTrafficType();

        /* fraud click filter for normal traffic */
        if ($result>=0) {
            $targetId   = empty($this->_couponId) ? 'm'. $this->_merchantId : 'c'.$this->_couponId ;
        	$result = $this->_isFraud($targetId) ? -4 : 1;
        }

        return $result;
    }

    /**
     * generate a unique outgoing id
     *
     * @return string
     */
    private function _generateOutgoingId()
    {
        return uniqid(mt_rand(1000000, 9999999));
    }

    /**
     * detect the coupon's fraud click
     *
     * @param integer $couponId
     * @return boolean
     */
    private function _isFraud($couponId)
    {
        $result = false;
        $count = 0;

        $clicks = $this->_session->getOfferClick();
        $clicks = empty($clicks) ? array() : explode('|', $clicks);

        foreach ($clicks as $index => $preClick) {
            list($preCouponId, $preCount) = explode(':', $preClick);
            if ($preCouponId == $couponId) {
                $count = $preCount;
                unset($clicks[$index]);
                break;
            }
        }

        if(++$count > (integer) $this->_config->strategy->fraudclick){
            $result = TRUE;
        }

        /** update offer click count */
        $clicks[] = "{$couponId}:{$count}";
        $this->_session->setOfferClick(implode('|', $clicks))->update();

        return $result;
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_businessType    = $this->_getBusinessType();
        $this->_couponBeacon    = $this->_getCouponBeacon();
        $this->_couponId        = $this->_getCouponId();
        $this->_customerId      = $this->_getCustomerId();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_merchantCount   = $this->_getMerchantCount();
        $this->_merchantId      = $this->_getMerchantId();
        $this->_outgoingId      = $this->_generateOutgoingId();
        $this->_outgoingType    = $this->_getOutgoingType();
    }

    /**
     * @see Tracking_Redirector_Abstract->_preDispatch()
     */
    protected function _preDispatch()
    {
        if ($this->_couponId > 0) {
            require_once __INCLUDE_ROOT . 'lib/classes/class.Coupon.php';
            $coupon = new Coupon($this->_couponId);
            $this->_merchantId = $this->_merchantId > 0 ? $this->_merchantId : $coupon->get('Merchant_');
            // invalid coupon
            if ($coupon->canShow() == 0 || $coupon->get('isActive') == 0){
                $this->_logError('invalid coupon');
                $this->_destinedUrl = '/';
                return false;
            }

            $this->_destinedUrl = $coupon->get('URL');
            if($this->_destinedUrl == '' && $this->_merchantId > 0){//get merchant url
                $oMerchant = new Merchant($this->_merchantId);
                $this->_destinedUrl = $oMerchant->get('URL');
            }

            if(!$this->_destinedUrl){
                $this->_logError('lost coupon url');
                $this->_destinedUrl = '/';
                return false;
            }

            return true;
        } else if ($this->_merchantId > 0) {
            require_once __INCLUDE_ROOT . 'lib/classes/class.Merchant.php';
            $oMerchant = new Merchant($this->_merchantId);
            $this->_destinedUrl = $oMerchant->get('URL');

            return true;
        } else {
            $this->_logError('lost offerId or channelId');
            $this->_destinedUrl = '/';

            return false;
        }
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch()
     */
    protected function _doDispatch ()
    {
        $logCouponOutgoing = array(
            'bidposition'        => 0,
            'businesstype'       => $this->_businessType,
            'channelid'          => $this->_channelId,
            'categoryid'         => $this->_categoryId,
            'clickarea'          => 0,
            'datasource'         => 0,
            'desturl'            => $this->_destinedUrl,
            'displayposition'    => $this->_displayPosition,
            'extracpc'           => 0,
            'cpcforlogo'         => 0,
            'merchantid'         => $this->_merchantId,
            'offerid'            => $this->_couponId,
            'outgoingid'         => $this->_outgoingId,
            'outgoingtype'       => $this->_outgoingType,
            'pricerank'          => 0,
            'productid'          => 0,
            'raterank'           => 0,
            'revenue'            => $this->_revenue,
            'sdcofferid'         => 0,
            'sortby'             => '',
            'totalmerchantcount' => $this->_merchantCount,
        );
        $this->_logger->offerOutgoing($logCouponOutgoing);

        $this->_httpResponseCode = 301;
    }
}