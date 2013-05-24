<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Offer.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

/**
 * parse the offer's url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Offer extends Tracking_Redirector_Abstract
{

    /**
     * the beacon of coupon
     *
     * @var string
     */
    private $_couponBeacon = '';

    /**
     * coupon id
     *
     * @var integer
     */
    private $_couponId = 0;

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
     * offer Id
     *
     * @var integer
     */
    private $_offerId = 0;

    /**
     * product Id
     *
     * @var integer
     */
    private $_productId = 0;

    /**
     * merchant Id
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
     * rank by price
     *
     * @var integer
     */
    private $_priceRank = 0;

    /**
     * rank by rate
     *
     * @var integer
     */
    private $_rateRank = 0;

    /**
     * sort by
     *
     * @var string
     */
    private $_sortBy = 0;

    /**
     * generate a unique outgoing id with prefix, max length = 32
     *
     * @param int       $length
     * @param string    $prefix
     * @return string
     */
    private function _generateOutgoingId($length = 32, $prefix = '')
    {
        return substr($prefix . md5(uniqid(mt_rand(), true)), 0, (int) $length);
    }

    /**
     * get coupon beacon
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
     * get gisplay position
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
     * get offer id
     *
     * @return integer
     */
    private function _getOfferId()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::OFFER_ID, 0);
    }

    /**
     * get product id
     *
     * @return integer
     */
    private function _getProductId()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::PRODUCT_ID, 0);
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
     * get rank by rate
     *
     * @return integer
     */
    private function _getRateRank()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::PRICE_RANK, 0);
    }

    /**
     * get rank by price
     *
     * @return integer
     */
    private function _getPriceRank()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::PRICE_RANK, 0);
    }

    /**
     * get sortBy
     *
     * @return string
     */
    private function _getSortBy()
    {
        return $this->_request->getParam(Tracking_Uri::SORT_BY);
    }

    /**
     * parse current request
     *
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_couponBeacon    = $this->_getCouponBeacon();
        $this->_couponId        = $this->_getCouponId();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_merchantCount   = $this->_getMerchantCount();
        $this->_offerId         = $this->_getOfferId();
        $this->_productId       = $this->_getProductId();
        $this->_merchantId      = $this->_getMerchantId();
        $this->_rateRank        = $this->_getRateRank();
        $this->_priceRank       = $this->_getPriceRank();
        $this->_sortBy          = $this->_getSortBy();

        $this->_outgoingId      = 'f' . $this->_generateOutgoingId(24, $this->_session->getSiteId() . date('md'));
    }

    /**
     * detect fraud click
     *
     * @param integer $channelId
     * @param integer $productId
     * @param integer $merchantId
     * @return boolean
     */
    private function _isFraud($channelId, $productId, $merchantId)
    {
        $result = FALSE;
        $count = 0;

        $cookieOffers = $this->_session->getOfferClick();
        $cookieOffers = empty($cookieOffers) ? array() : explode('|', $cookieOffers);

        $currentOffer = "{$channelId}/{$productId}/{$merchantId}";
        foreach ($cookieOffers as $key => $value) {
            list($cookieOffer, $cnt) = explode(':', $value);
            if ($cookieOffer == $currentOffer) {
                $count = $cnt;
                unset($cookieOffers[$key]);
                break;
            }
        }

        if(++$count > (integer) $this->_config->strategy->fraudclick){
            $result = TRUE;
        }

        /** update offer click count */
        $cookieOffers[] = "{$currentOffer}:{$count}";
        $this->_session->setOfferClick(implode('|', $cookieOffers))->update();

        return $result;
    }

    /**
     * detect offer limit clicks
     *
     * @return boolean
     */
    private function _isLimit()
    {
        $cookieOffers   = $this->_session->getOfferClick();
        $limitClicks     = (integer) $this->_config->strategy->limitclick;
        if (empty($cookieOffers) || empty($limitClicks)) {
        	return FALSE;
        }

        $total = 0;
        $cookieOffers = explode('|', $cookieOffers);
        foreach ($cookieOffers as $value) {
            list(, $count) = explode(':', $value);
            $total += $count;
        }

        return $total > $limitClicks;
    }

    /**
     * charge Merchant CPC
     *
     * @param Tracking_Merchant $merchant
     * @param array $params
     * @return boolean
     */
    private function _chargeMerchant($merchant, $params)
    {
        $balance        = $merchant->modifyBalance($params['merchantid'], $params['revenue'], $params['businesstype']);
        if($balance['successful']){
            $params['chargeid']     = '';
            $params['oldbalance']   = $balance['old'];
            $params['newbalance']   = $balance['new'];
            $this->_logger->bidCpc($params);
        }

        return $balance['successful'];
    }

    /**
     * @see Tracking_Redirector_Abstract->_preDispatch()
     */
    protected function _preDispatch()
    {
        if ($this->_offerId && $this->_channelId) {
            return TRUE;
        } else if ($this->_destinedUrl) {
            return TRUE;
        } else {
            $this->_logError('lost destined url');
            $this->_destinedUrl = '/';

            return FALSE;
        }
    }

    /**
     * detecte outgoing type
     *
     * @param array $offer
     * @return integer
     */
    protected function _detecteOutgoingType($offer)
    {
        $outgoingType = $this->_session->getTrafficType();
        if($outgoingType>=0) {
            $outgoingType   = 1;

            /** check merchant */
            if (isset($_COOKIE['SAM_MERCHANT']) && ((integer)$_COOKIE['SAM_MERCHANT'] == $merchantId)) {
                $outgoingType =  -6;
            }

            /** is offer online? */
            if ($outgoingType == 1 && isset($offer['IsOnline']) && strtoupper($offer['IsOnline'])=='NO') {
                $outgoingType =  -8;
            }

            /** check fraud click, click same list > 4 */
            if ($outgoingType == 1 && $this->_isFraud($this->_channelId, $this->_productId, $this->_merchantId)) {
                $outgoingType = -4;
            }

            /** check limit click, click same list > 30 */
            if ($outgoingType == 1 && $this->_isLimit()) {
                $outgoingType = -5;
            }
        }

        return $outgoingType;
    }

    /**
     * append outgoing beacon to destined url
     *
     * @param string $url
     * @return string
     */
    protected function _appendOutgoingBeacon($url)
    {
        if(($url !='') && (strpos($this->_couponBeacon, '|') !== false)) {
            list($beaconPattern, $beaconTag) = explode('|', $this->_couponBeacon);

            if(stripos($url, $beaconPattern) !== false) {
                if ($beaconTag[0]=='?' && ($pos = strpos($url, '?')) !== false) {
                    $url = substr($url, 0, $pos) . "{$beaconTag}{$this->_outgoingId}&" . substr($url, $pos+1);
                } else {
                    $url .= "{$beaconTag}{$this->_outgoingId}";
                }
            }
        }

       return $url;
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch()
     */
    protected function _doDispatch()
    {
        $offerOutgoingLog   = array(
            'bidposition'        => 0,
            'businesstype'       => '',
            'channelid'          => $this->_channelId,
            'clickarea'          => 0,
            'datasource'         => '',
            'desturl'            => $this->_destinedUrl,
            'displayposition'    => $this->_displayPosition,
            'merchantid'         => $this->_merchantId,
            'offerid'            => $this->_offerId,
            'outgoingid'         => $this->_outgoingId,
            'outgoingtype'       => $this->_detecteOutgoingType(array()),
            'pricerank'          => $this->_priceRank,
            'productid'          => $this->_productId,
            'raterank'           => $this->_rateRank,
            'revenue'            => 0,
            'cpcforlogo'         => 0,
            'extracpc'           => 0,
            'sdcofferid'         => '',
            'sessionid'          => $this->_session->getSessionId(),
            'sortby'             => $this->_sortBy,
            'totalmerchantcount' => $this->_merchantCount,
        );

        $this->_logger->offerOutgoing($offerOutgoingLog);
    }
}