<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Offer.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * parse the offer's url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Offer extends Tracking_Redirector_Abstract
{
    const COOKIE_ROI            = 'SM_ROI';

    /**
     * the beacon of coupon
     *
     * @var string
     */
    private $_couponBeacon = '';

    /**
     * display position
     *
     * @var integer
     */
    private $_displayPosition = 0;
	private $_clickArea = '';

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
     * outgoing id
     *
     * @var string
     */
    private $_outgoingId = '';

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
    private $_sortBy = '';

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
     * get gisplay position
     *
     * @return integer
     */
    private function _getDisplayPosition()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::DISPLAY_POSITION, 0);
    }

    private function _getClickArea()
    {
        return $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
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
        return $this->_request->getParam(Tracking_Uri::OFFER_ID, 0);
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

        $this->_couponBeacon    = $this->_getCouponBeacon();
        $this->_displayPosition = $this->_getDisplayPosition();
		$this->_clickArea		= $this->_getClickArea();
        $this->_merchantCount   = $this->_getMerchantCount();
        $this->_offerId         = $this->_getOfferId();
        $this->_rateRank        = $this->_getRateRank();
        $this->_priceRank       = $this->_getPriceRank();
        $this->_sortBy          = $this->_getSortBy();
        $this->_outgoingId      = 'f' . $this->_generateOutgoingId(24, $this->_session->getSiteId() . date('mdHy'));
    }

    /**
     * detect fraud click
     *
     * @param integer $channelId
     * @param integer $offerId
     * @return boolean
     */
    private function _isFraud($channelId, $offerId)
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
     * ROI Tracker for Smarter offer outgoing
     * ROI tracker save multi-merchant data
     *
     * @param integer $merchantId
     * @param integer $channelId
     * @param integer $productId
     * @param integer $siteId
     * @param string $businessType
     * @param string $dateSource
     * @param string $requestId
     * @return Tracking_Redirector_Offer
     */
    private function _updateRoiTracker($merchantId, $channelId, $productId, $siteId, $businessType, $dateSource, $requestId)
    {
        $roiTags = explode('@', isset($_COOKIE[self::COOKIE_ROI]) ? trim($_COOKIE[self::COOKIE_ROI]) : '');

        foreach ($roiTags as $index => $roiTag){
            if (empty($roiTag) || (($roiElements = explode('|', $roiTag)) && (array_shift($roiElements) == $merchantId))) {
                unset($roiTags[$index]);
            }
        }

        /* remove the 1st roiTag if the roiTags was so much */
        if (count($roiTags)>=10) {
        	array_shift($roiTags);
        }

        $businessTypeMapping = array(1=>'CPA', 2=>'CPC', 3=>'FREE', 4=>'CPC-H',);
        $businessType = (integer) array_search($businessType, $businessTypeMapping);

        if ($merchantId && $channelId && $productId && $siteId){
            $roiTag =  "{$merchantId}|{$channelId}|{$productId}|{$siteId}|{$businessType}|{$dateSource}|{$requestId}";
            array_push($roiTags, $roiTag);
            setcookie(self::COOKIE_ROI,  implode('@', $roiTags), strtotime('+30 day'), '/', Mezi_Config::getInstance()->tracking->session->domain);
        }

        return $this;
    }

    /**
     * @see Tracking_Redirector_Abstract->_preDispatch()
     * @return boolean
     */
    protected function _preDispatch()
    {
        if ($this->_offerId && $this->_channelId) {
            return true;
        } else {
            $this->_logError('lost offerId or channelId');
            $this->_destinedUrl = '/';

            return false;
        }
    }

    /**
     * detecte outgoing type
     *
     * @param array $offer
     * @return integer
     */
    protected function _detectOutgoingType($offer)
    {
        $outgoingType = $this->_session->getTrafficType();
        if($outgoingType>=0) {
            $outgoingType   = 1;

            /** check merchant */
            if (isset($_COOKIE['SAM_MERCHANT']) && ((integer)$_COOKIE['SAM_MERCHANT'] == $offer['MerchantID'])) {
                $outgoingType =  -6;
            }

            /** is offer online? */
            if ($outgoingType == 1 && isset($offer['IsOnline']) && strtoupper($offer['IsOnline'])=='NO') {
                $outgoingType =  -8;
            }

            /** check fraud click, click same list > 4 */
            if ($outgoingType == 1 && $this->_isFraud($this->_channelId, $this->_offerId)) {
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
     * @param string $affiliateTag
     * @return string
     */
    protected function _appendOutgoingBeacon($url, $affiliateTag)
    {
        if(($url !='') && ($affiliateTag != '')) {
            if ($affiliateTag[0]=='?' && ($pos = strpos($url, '?')) !== false) {
                $url = substr($url, 0, $pos) . "{$affiliateTag}{$this->_outgoingId}&" . substr($url, $pos+1);
            } else {
                $url .= "{$affiliateTag}{$this->_outgoingId}";
            }
        }

       return $url;
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch()
     */
    protected function _doDispatch()
    {
        /* fetch offer information by smarter api */
        $retry          = 0;
        while ($retry++ < Mezi_Config::getInstance()->tracking->service->offer->retry) {
            try {
                if ($offer = $this->_fetchOfferByApi($this->_channelId, $this->_offerId)) { break; };
            } catch (Tracking_Exception $exception) {
                $this->_logError("Code: {$exception->getCode()}, Message: {$exception->getMessage()}");
            }
        }

        if (empty($offer['URL']) || strtolower(substr($offer['URL'], 0, 4))!='http') {
            $this->_logError('destined url lost!');
            $this->_destinedUrl = '/';
            return;
        }

        $productId      = (integer) $offer['ProductID'];
        $merchantId     = (integer) $offer['MerchantID'];
        $bidPosition    = (integer) $offer['Position'];
        $dataSource     = (integer) $offer['DataSource'];
        $sdcOfferId     = '';
        $businessType   = $dataSource ? 'CPA' : strtoupper($offer['r_BusinessType']);
        $cpcForLogo     = 0;
        $revenue        = (float) $offer['r_CPC'];
        $extraCpc       = (float) $offer['r_ExtraCPC'];
        $revenue        = $revenue + $cpcForLogo + $extraCpc;

        /** get outgoing type */
        $outgoingType   = $this->_detectOutgoingType($offer);
        if ($businessType == 'CPA') {
            $this->_destinedUrl = $this->_appendOutgoingBeacon($offer['URL'], $offer['affiliateTag']);
        } else {
            $this->_destinedUrl = $offer['URL'];
        }
		
        if($businessType != 'CPC'&&$merchantId!='10729'){
            if (preg_match ( '/rakuten.co.jp/', $this->_destinedUrl )) {
                $siteId = ( int ) Mezi_Config::getInstance ()->tracking->site->id;
                $outgoingId = 'a' . $this->_generateOutgoingId ( 24, $siteId . date ( 'mdHy' ) ); // 'a' means sdc api
                $this->_destinedUrl = preg_replace ( '|http://(.+?)/(.+?)/(.+?)/(?:.+?)?(\?.*)|', "http://$1/$2/$3/{$outgoingId}$4", $this->_destinedUrl );
            }
        }
		
        $offerOutgoingLog = array(
            'bidPosition'        => $bidPosition,
            'businessType'       => $businessType,
            'channelId'          => $this->_channelId,
            'categoryId'         => $this->_categoryId,
            'clickArea'          => $this->_clickArea,
            'cpcForLogo'         => $cpcForLogo,
            'dataSource'         => $dataSource,
            'desturl'            => $this->_destinedUrl,
            'displayPosition'    => $this->_displayPosition,
            'extraCpc'           => $extraCpc,
            'merchantId'         => $merchantId,
            'offerId'            => $this->_offerId,
            'outgoingType'       => $outgoingType,
            'priceRank'          => $this->_priceRank,
            'productId'          => $productId,
            'rateRank'           => $this->_rateRank,
            'revenue'            => $revenue,
            'sdcOfferId'         => $sdcOfferId,
            'sortBy'             => $this->_sortBy,
            'totalMerchantCount' => $this->_merchantCount,
        );

        $this->_updateRoiTracker(
            $merchantId, $this->_channelId, $productId, $this->_session->getSiteId(), $businessType, $dataSource, $this->_session->getRequestId()
        );

        $this->_logger->offerOutgoing($offerOutgoingLog);
    }

    /**
     * fetch offer by smarter api
     *
     * @param integer $channelId
     * @param integer $offerId
     *
     * @throws Tracking_Exception
     * @return array
     */
    private function _fetchOfferByApi($channelId, $offerId)
    {
        $serviceOffer   = Mezi_Config::getInstance()->service->offer->api;

        $requestHost    = rtrim($serviceOffer->host, '/');
        $requestQuery   = "reqtype=list&restype=" . $serviceOffer->resource . "&ch={$channelId}&offerid={$offerId}&clientid=" . $serviceOffer->username;
        $requestDigest  = md5($requestQuery . $serviceOffer->password);
        $requestUri     = $requestHost . '/offer?' . $requestQuery . '&digest=' .$requestDigest;

        $client         = new Tracking_Client($requestUri, array('timeout' => Mezi_Config::getInstance()->tracking->service->offer->timeout, ));
        $beginTime      = microtime(true);
        $response       = $client->request();
        $responseXml    = simplexml_load_string($response);
        $endTime        = microtime(true) - $beginTime;

        $searchLog = array (
            'keyword'           => $offerId,
            'matchkey'          => '',
            'categoryid'        => 0,
            'resulttype'        => 1,
            'resultcount'       => 1,
            'source'            => Tracking_Session::getInstance()->getSource(),
            'isrealsearch'      => 'YES',
            'iscached'          => 'NO',
            'searchenginetype'  => 1,
            'channelid'         => $channelId,
            'costtime'          => sprintf('%.5f', $endTime),
            'totalcosttime'     => sprintf('%.5f', $endTime),
            'pid'               => 0,
            'responsetime'      => (float) $responseXml->response['time'],
            'desturl'           => '',
            'resultsize'        => strlen($response),
        );
        Tracking_Logger::getInstance()->search($searchLog);

        if (isset($_GET['debug']) && $_GET['debug'] == 'yes') { var_dump($requestUri, $response); }
        unset($client);


        if (!$offer = $responseXml->content->offers->offer) {
            throw new Tracking_Exception("could NOT fetch offer infomation by service: {$requestUri}");
        }

        return array (
            'ProductID'         => (integer) $offer->ProductId,
            'MerchantID'        => (integer) $offer->merchant['id'],
            'DataSource'        => (integer) $offer->datasource,
            'r_BusinessType'    => (string) $offer->businesstype,
            'Position'          => (integer) $offer->merchanturl['bidposition'],
            'affiliateTag'      => (string) $offer->merchant->AffiliateKey,
            'SDCOfferID'        => '',
            'r_CPC'             => (float) $offer->CPC,
            'r_ExtraCPC'        => (float) $offer->ExtraCPC,
            'DisplayLogo'       => (string) $offer->displaylogo,
            'URL'               => (string) $offer->TrackingURL,
        );
    }
}