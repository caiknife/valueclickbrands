<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Offer.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
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

	/*CPA outgoing tracking id*/
	private $_outgoingId = '';

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

        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_merchantCount   = $this->_getMerchantCount();
        $this->_offerId         = $this->_getOfferId();
        $this->_rateRank        = $this->_getRateRank();
        $this->_priceRank       = $this->_getPriceRank();
        $this->_sortBy          = $this->_getSortBy();
        $this->_outgoingId      = 'f' . $this->_generateOutgoingId(24, $this->_session->getSiteId() . date('mdHy'));
    }

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
     * charge Merchant CPC
     *
     * @param Tracking_Merchant $merchant
     * @param array $params
     * @return boolean
     */
    private function _chargeMerchant($merchant, $params)
    {
        $params['chargeid']     = '';
        $params['oldbalance']   = 0;
        $params['newbalance']   = 0;

        if(isset($params['businesstype']) && ($params['businesstype'] == 'CPC')){
            try {
                $chargeOk   = (boolean) ($params['chargeid'] = $merchant->chargeCpcLog($params));
            } catch (Exception $exception) {
                $chargeOk = false;
                $this->_logError($exception->getMessage());
            }
        } else {
            $params['revenue']      = 0;
            $chargeOk = true;
        }

        if($chargeOk){
            $this->_logger->bidCpc($params);
        }

        return $chargeOk;
    }

    /**
     * ROI Tracker for Smarter offer outgoing
     * ROI tracker save multi-mechant data
     *
     * @param integer $merchantId
     * @param integer $channelId
     * @param integer $productId
     * @param string $randString
     * @param string $businessType
     * @param string $dateSource
     *
     * @return TrackingRedirect
     */
    private function _updateRoiTracker($merchantId, $channelId, $productId)
    {
        $roiTags = explode('@', isset($_COOKIE['SM_ROI']) ? trim($_COOKIE['SM_ROI']) : '');

        foreach ($roiTags as $index => $roiTag){
            if (empty($roiTag) || (($exp = explode('|', $roiTag)) && (array_shift($exp) == $merchantId))) {
                unset($roiTags[$index]);
            }
        }

        if ($merchantId > 0 && $channelId > 0 && $productId >0){
            $roiTag = "{$merchantId}|{$channelId}|{$productId}";
            array_push($roiTags, $roiTag);
            setcookie("SM_ROI",  implode("@", $roiTags), strtotime('+60 day'), "/", Mezi_Config::getInstance()->tracking->session->domain);
        }

        return $this;
    }

    /**
     * @see Tracking_Redirector_Abstract->_preDispatch()
     */
    protected function _preDispatch()
    {
        if ($this->_offerId && $this->_channelId) {
            return TRUE;
        } else {
            $this->_logError('lost offerId or channelId');
            $this->_destinedUrl = '/';

            return FALSE;
        }
    }

    /**
     * append outgoing beacon to destined url
     *
     * @param string $url
     * @return string
     */
    protected function _appendOutgoingBeacon($url, $beaconTag, $outgoingId)
	{
		if(stripos($url, $beaconTag) !== false) {
			if ($beaconTag[0]=='?' && ($pos = strpos($url, '?')) !== false) {
				$url = substr($url, 0, $pos) . "{$beaconTag}{$outgoingId}&" . substr($url, $pos+1);
			} else {
				$url .= "{$beaconTag}{$outgoingId}";
			}
		}else{
			$url .= "{$beaconTag}{$outgoingId}";
		}
	    return $url;
	}
    /**
     * @see Tracking_Redirector_Abstract->_doDispatch()
     */
    protected function _doDispatch()
    {    
        /* fetch offer by api*/
        $retry = 0;
        while ($retry++ < Mezi_Config::getInstance()->tracking->tracking->service->offer->api->timeout) {
            try {
                if ($offer = $this->_fetchOfferByApi($this->_channelId, $this->_offerId)) {
                    break;
                }
            } catch (Tracking_Exception $exception) {
                $this->_logError("Code: {$exception->getCode()}, Message: {$exception->getMessage()}");
            }
        }
        /* end */
        /**
        $merchant = new Tracking_Merchant();
        $offer = $merchant->fetchOffer($this->_channelId, $this->_offerId);
         */

        if (empty($offer['URL']) || strtolower(substr($offer['URL'], 0, 4)) != 'http') {
            $this->_logError('can not redir to merchant site, dest url lost!');
            $this->_destinedUrl = '/';
            return;
        }

        $productId      = (integer) $offer['ProductID'];
        $merchantId     = (integer) $offer['MerchantID'];
        $bidPosition    = (integer) $offer['Position'];
        $dataSource     = (integer) $offer['DataSource'];
        $destUrl        = $offer['URL'];
        $sdcOfferId     = '';
        $businessType   = $dataSource ? 'CPA' : $offer['r_BusinessType'];
        $cpcForLogo     = 0;
        $revenue        = (float) $offer['r_CPC'];
        $extraCpc       = (float) $offer['r_ExtraCPC'];
        $revenue        = $revenue + $cpcForLogo + $extraCpc;

        /** get outgoing type */
        $outgoingType = $this->_session->getTrafficType();
        $outgoingType = $outgoingType < 0 ? $outgoingType : 1;

        /** check merchant */
        if ($outgoingType == 1 && isset($_COOKIE['SAM_MERCHANT']) && ((integer)$_COOKIE['SAM_MERCHANT'] == $merchantId)) {
            $outgoingType =  -6;
        }

        /** check fraud click, click same list > 4 */
        if ($outgoingType == 1 && $this->_isFraud($this->_channelId, $this->_offerId)) {
        	$outgoingType = -4;
        }

        if ($outgoingType == 1 && $this->_isLimit()) {
        	$outgoingType = -5;
        }

		if(strpos($destUrl,"click.linkprice.com")!==false){
			$destUrl = $this->_appendOutgoingBeacon($destUrl, "&u_id=", $this->_outgoingId);
		}
        $offerOutgoingLog = array(
            'bidposition'        => $bidPosition,
            'businesstype'       => $businessType,
            'channelid'          => $this->_channelId,
            'categoryid'         => $this->_categoryId,
            'clickarea'          => 0,
            'cpcforlogo'         => $cpcForLogo,
            'datasource'         => $dataSource,
            'desturl'            => $destUrl,
            'displayposition'    => $this->_displayPosition,
            'extracpc'           => $extraCpc,
            'merchantid'         => $merchantId,
            'offerid'            => $this->_offerId,
            'outgoingtype'       => $outgoingType,
            'pricerank'          => $this->_priceRank,
            'productid'          => $productId,
            'raterank'           => $this->_rateRank,
            'revenue'            => $revenue,
            'sdcofferid'         => $sdcOfferId,
            'sortby'             => $this->_sortBy,
            'totalmerchantcount' => $this->_merchantCount,
        );
        
        /*
        if (1 == $outgoingType) {
            $offerOutgoingLog['outgoingtype'] = $this->_chargeMerchant($merchant, $offerOutgoingLog) ? 1 : -7;
        }
        */

        $this->_updateRoiTracker($merchantId, $this->_channelId, $productId);

        $this->_logger->offerOutgoing($offerOutgoingLog);

        $this->_destinedUrl = $destUrl;
    }
    
    /**
     * 
     * fetch offer by smarter api
     * @param integer_type $channelId
     * @param integer $offerId
     * 
     * @throws Tracking_Exception
     * @return array
     */
    protected function _fetchOfferByApi($channelId, $offerId) {
        $serviceOffer = Mezi_Config::getInstance()->tracking->tracking->service->offer->api;
        
        // request uri
        $requestHost = rtrim($serviceOffer->host, '/');
        $requestQuery = "reqtype=list&restype=" . $serviceOffer->resource . "&ch={$channelId}&offerid={$offerId}&clientid=" . $serviceOffer->username;
        // $requestDigest = md5($requestQuery . $serviceOffer->password);
        // $requestUri = $requestHost . "/offer?" . $requestQuery . "&digest=" . $requestDigest;
        $requestUri = $requestHost . "/offer?" . $requestQuery;
               
        // request an api and get response
        $client = new Tracking_Client($requestUri, array('timeout' => $serviceOffer->timeout));
        $beginTime = microtime(true);
        $response = $client->request();
        
        $responseXml = simplexml_load_string($response);
        $endTime = microtime(true) - $beginTime;
        
        // search log
        $searchLog = array(
            'keyword'          => $offerId,
            'matchkey'         => '',
            'categoryid'       => 0,
            'resulttype'       => 1,
            'resultcount'      => 1,
            'source'           => Tracking_Session::getInstance()->getSource(),
        	'isrealsearch'     => 'YES',
            'iscached'         => 'no',
            'searchenginetype' => 1,
            'channelid'         => $channelId,
            'costtime'          => sprintf('%.5f', $endTime),
            'totalcosttime'     => sprintf('%.5f', $endtime),
            'pid'               => 0,
            'responsetime'		=> (float) $responseXml->response['total_time'],
            'desturl'			=> '',
            'resultsize'		=> strlen($response),
        );
        Tracking_Logger::getInstance()->search($searchLog);
        
        if (isset($_GET['debug']) && $_GET['debug'] == 'yes') {
            var_dump($requestUri, $response);
        }
        unset($client);
        
        if (!$offer = $responseXml->content->offers->offer) {
            throw new Tracking_Exception("could NOT fetch offer information by service: {$requestUri}");
        }
        
        return array(
            'ProductID'      => (integer) $offer->product['id'],
        	'MerchantID'     => (integer) $offer->merchantid,
            'DataSource'     => '', //(integer) $offer->datasource,
            'r_BusinessType' => (string) $offer->businesstype,
            'Position'       => '', //(integer) $offer->merchanturl['bidposition'],
            'affiliateTag'   => '', //(string) $offer->merchant->AffiliateKey,
            'SDCOfferID'     => '',
            'r_CPC'          => (float) $offer->cpc,
            'r_ExtraCPC'     => (float) $offer->extracpc,
            'DisplayLogo'    => (string) $offer->displaylogo,
            'URL'            => (string) $offer->url,
        );
        
    }
}