<?php

/**
 * parse the offer's url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Offer extends Tracking_Redirector_Abstract
{
    /**
     * click area
     *
     * @var integer
     */
    private $_clickArea = 0;

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
     * Offer Id
     *
     * @var integer
     */
    private $_offerId = 0;

    /**
     * rank by rate
     *
     * @var integer
     */
    private $_rateRank = 0;

    /**
     * rank by price
     *
     * @var integer
     */
    private $_priceRank = 0;

    /**
     * sort by
     *
     * @var string
     */
    private $_sortBy = 0;

    /**
     * get click area
     *
     * @return integer
     */
    private function _getClickArea()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
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
     * get rank by rate
     *
     * @return integer
     */
    private function _getRateRank()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::RATE_RANK, 0);
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

        $this->_clickArea       = $this->_getClickArea();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_merchantCount   = $this->_getMerchantCount();
        $this->_offerId         = $this->_getOfferId();
        $this->_rateRank        = $this->_getRateRank();
        $this->_priceRank       = $this->_getPriceRank();
        $this->_sortBy          = $this->_getSortBy();
    }

    /**
     * detect fraud click
     *
     * @param integer $channelId
     * @param integer $offerId
     * @return boolean
     */
    private function _detectOfferFraudClick($channelId, $offerId)
    {
        $result = FALSE;

        $cookieOffers = $this->_session->getOfferClick();
        $cookieOffers = empty($cookieOffers) ? array() : explode('|', $cookieOffers);

        $count = 0;
        // find offer
        $currentOffer = $channelId . '/' . $offerId;
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
     * charge Merchant CPC
     *
     * @param Tracking_Merchant $merchant
     * @param array $params
     * @return boolean
     */
    private function _chargeMerchant($merchant, $params)
    {
        $sessionId       = $params['sessionid'];
        $businessType    = $params['businesstype'];
        $channelId       = (integer) $params['channelid'];
        $merchantId      = (integer) $params['merchantid'];
        $revenue         = (float) $params['revenue'];
        $cpcLogo         = $params['cpcforlogo'];
        $productId       = (integer) $params['productid'];
        $sdcOfferId      = $params['sdcofferid'];
        $dataSource      = $params['datasource'];
        $bidPosition     = (integer) $params['bidposition'];
        $displayPosition = (integer) $params['displayposition'];
        $extraCpc        = $params['extracpc'];

        // charge merchent cpc
        if($businessType == 'CPC'){
            $chargeCpcLog = array(
                'type'            => 'CHARGE',   //log type, charge/present
                'sessionid'       => $sessionId,
                'merchantid'      => $merchantId,
                'revenue'         => $revenue,
                'cpcforlogo'      => $cpcLogo,
                'channelid'       => $channelId,
                'productid'       => $productId,
                'sdcofferid'      => $sdcOfferId,
                'datasource'      => $dataSource,
                'bidposition'     => $bidPosition,
                'displayposition' => $displayPosition,
                'extracpc'        => $extraCpc,
            );

            /** set charge cpc log for data team */
            try {
                $isChargeOk   = $merchant->chargeCpcLog($chargeCpcLog);
            } catch (Exception $exception) {
                $isChargeOk = FALSE;
                Tracking_Debug::dump($exception->getMessage());
            }
        }else{
            /** if a CPA Merchant we think the charge always ok. */
            $isChargeOk = TRUE;
        }

        return $isChargeOk;
    }

    /**
     * @see Tracking_Redirector_Abstract->_preDispatch()
     */
    protected function _preDispatch()
    {
        if ($this->_offerId && $this->_channelId) {
            return TRUE;
        } else {
            $this->_logError('lost offerId or hannelId');
            $this->_destinedUrl = '/';

            return FALSE;
        }
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch()
     */
    protected function _doDispatch()
    {
        $merchant = new Tracking_Merchant();
        $offer = $merchant->fetchOffer($this->_channelId, $this->_offerId);

        if (!count($offer) || !trim($offer['URL'])) {
            $this->_logError('lost destination offline url');
            $offlineUrl = $merchant->getOfflineOfferUrl($this->_channelId, $this->_offerId);
            $this->_destinedUrl = $offlineUrl ? $offlineUrl: '/';
            return;
        }

        $productId      = (integer) $offer['ProductID'];
        $merchantId     = (integer) $offer['MerchantID'];
        $dataSource     = (integer) $offer['DataSource'];
        $revenue        = (float) $offer['r_CPC'];
        $sdcOfferId     = (string) $offer['SDCOfferID'];
        $businessType   = (string) $offer['r_BusinessType'];
        $destUrl        = $offer['URL'] ? $offer['URL'] : $offlineUrl;
        $bidPosition    = (integer) $offer['Position'];
        $extraCpc       = (float) $offer['r_ExtraCPC'];

        if ('CPC-H' == $businessType) {
            $curRandStr = $this->_session->getRequestId();
            $sessionId  = $this->_session->getSessionId();
            $op         = substr(str_replace(array('+', '/', '='), '', base64_encode(pack('H*', md5($sessionId)))), 0, 3) .
                          substr(str_replace(array('+', '/', '='), '', base64_encode(pack('H*', md5($curRandStr)))), 0, 4);
            $destUrl    = $destUrl . '&op=' . $op;
        }

        $siteName       = (string) $this->_config->site->name;
        $requestId      = (string) $this->_session->getRequestId();
        $destUrl        = "{$destUrl}&source={$siteName}_{$requestId}";

        /*************** Add charge partner xml feed logic ***************
        * 1.    charge cpc                         feed-1-<partnername>
        * 2.    charge cpc + logocpc               feed-2-<partnername>
        * 3.    charge cpc + extracpc              feed-3-<partnername>
        * 4.    charge cpc + extracpc + logocpc    feed-4-<partnername>
        *************** End charge cpc logic ****************************/
        $cpcForLogo = 0;
        if ('YES' == $offer['DisplayLogo']) {
            if ('CPC' == $businessType) {
                $cpcForLogo = (float) $this->_config->revenue->logodefault;
            } elseif ('CPC-H' == $businessType) {
                 $cpcForLogo = (float) $this->_config->revenue->logosdc;
            }
        }
        if (preg_match('/^feed-1-.+/i', $this->_session->getSource())) {
            $cpcForLogo = $extraCpc = 0;
        }
        $revenue = $revenue + $cpcForLogo + $extraCpc;
        /*************** End charge cpc logic ****************************/

        /** get outgoing type */
        $outgoingType = $this->_session->getTrafficType();
        $outgoingType = $outgoingType < 0 ? $outgoingType : 1;

        /** check merchant */
        if ($outgoingType == 1 && isset($_COOKIE['SAM_MERCHANT']) && ($_COOKIE['SAM_MERCHANT'] == $merchantId)) {
            $outgoingType =  -6;
        }

        /** check fraud click, click same list > 4 */
        if ($outgoingType == 1 && $this->_detectOfferFraudClick($this->_channelId, $this->_offerId)) {
        	$outgoingType = -4;
        }

        $this->_outgoingLog = array(
            'productId'          => $productId,
            'merchantId'         => $merchantId,
            'offerId'            => $this->_offerId,
            'outgoingType'       => $outgoingType,
            'bidPosition'        => $bidPosition,
            'totalMerchantCount' => $this->_merchantCount,
            'clickArea'          => $this->_clickArea,
            'displayPosition'    => $this->_displayPosition,
            'priceRank'          => $this->_priceRank,
            'rateRank'           => $this->_rateRank,
            'sortBy'             => $this->_sortBy,
            'channelId'          => $this->_channelId,
            'businessType'       => $businessType,
            'revenue'            => $revenue,
            'logoRevenue'        => $cpcForLogo,
            'extraRevenue'       => $extraCpc,
            'destUrl'            => $destUrl,
            'sdcOfferId'         => $sdcOfferId,
            'dataSource'         => $dataSource,
        );

        if ($this->_needLog) {
            $this->_logger->offerOutgoing($this->_outgoingLog);
        }

        $this->_destinedUrl = $destUrl;
    }
}