<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Sponsor.php,v 1.1 2013/07/10 01:34:46 jjiang Exp $
 */

/**
 * parse the sponsor's url and log it
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Sponsor extends Tracking_Redirector_Abstract
{
    const COOKIE_SPONSOR_CLICKS = 'mm_spn';

    /**
     * keyword
     *
     * @var string
     */
    private $_keyword;

    /**
     * click area
     *
     * @var integer
     */
    private $_clickArea;

    /**
     * rank
     *
     * @var string
     */
    private $_displayPosition;

    /**
     * channelTag
     *
     * @var string
     */
    private $_channelTag;

    /**
     * sponsor type
     *
     * @var string
     */
    private $_sponsorType;

    /**
     * advertiser host
     *
     * @var string
     */
    private $_advertiserHost;

    /**
     * outgoing type
     *
     * @var integer
     */
    private $_outgoingType;

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_channelTag      = $this->_getChannelTag();
        $this->_keyword         = $this->_getKeyword();
        $this->_clickArea       = $this->_getClickArea();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_sponsorType     = $this->_getSponsorType();
        $this->_advertiserHost  = $this->_getAdvertiserHost();
    }

    /**
     * get advertiser host
     *
     * @return string
     */
    private function _getAdvertiserHost()
    {
        return $this->_request->getParam(Tracking_Uri::ADVERTISER_HOST, '');
    }

    /**
     * get channel tag
     *
     * @return string
     */
    private function _getChannelTag()
    {
        return $this->_request->getParam(Tracking_Uri::CHANNEL_TAG, '');
    }

    /**
     * get click area
     *
     * @return integer
     */
    private function _getClickArea()
    {
        return '1';//default set to 1
        //return (integer) $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
       // return $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
    }

    /**
     * get keyword
     *
     * @return string
     */
    private function _getKeyword()
    {
        return $this->_request->getParam(Tracking_Uri::KEYWORD, '');
    }

    /**
     * get rank
     *
     * @return float
     */
    private function _getDisplayPosition()
    {
        return (float) $this->_request->getParam(Tracking_Uri::DISPLAY_POSITION, 0);
    }

    /**
     * get sponsor type
     *
     * @return string
     */
    private function _getSponsorType()
    {
        return $this->_request->getParam(Tracking_Uri::SPONSOR_TYPE, '');
    }

    /**
     * invalid outgoing clicks filter
     * the multi clicks for per keyword per rank in one session just count as one click.
     *
     * @param string $keyword
     * @param integer $rank
     * @param string $sponsorType
     * @return boolean
     */
    private function _isFraud($keyword, $rank, $sponsorType)
    {
        $result = FALSE;

        $curClick = md5("$rank|$keyword|$sponsorType");
        $preClick = $this->_session->getSponsorClick();

        if(stripos($preClick, $curClick) !== FALSE) {
            $result = TRUE;
        } else {
            $this->_session->setSponsorClick("$curClick|$preClick")
                           ->update();
        }

        return $result;
    }

    /**
     * log special infomation
     */
    private function _logOutgoing()
    {
        $this->_outgoingLog = array(
            'destUrl'           => $this->_destinedUrl,
            'keyword'           => $this->_keyword,
            'clickArea'         => $this->_clickArea,
            'displayPosition'   => $this->_displayPosition,
            'channelId'         => $this->_channelId,
            'categoryId'        => $this->_categoryId,
            'channelTag'        => $this->_channelTag,
            'sponsorType'       => $this->_sponsorType,
            'advertiserHost'    => $this->_advertiserHost,
            'revenue'           => $this->_revenue,
            'outgoingType'      => $this->_outgoingType,
        );
        if ($this->_needLog) {
            $this->_logger->sponsorOutgoing($this->_outgoingLog);
        }
    }

    /**
     * request url data
     *
     * @param integer $url
     * @param integer $timeout
     */
    private function _requestData($url, $timeout=2)
    {
        $handle = curl_init();

        /* set URL and other appropriate options */
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        /* grab URL */
        $result = curl_exec($handle);

        if (empty($result)) {
            throw new Exception(curl_error($handle), curl_errno($handle));
        }

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        /* close cURL resource, and free up system resources */
        curl_close($handle);

        if($httpCode>=400) {
            throw new Exception("response code - {$httpCode}");
        }

        return $result;
    }

    /**
     * request for channel tag to sem service
     *
     * @param string $keyword
     * @param string $country
     * @param string $channelTag
     * @param string $sponsorType
     */
    private function _requestChannelTag($keyword, $country, $channelTag, $sponsorType)
    {
        $session    =  Tracking_Session::getInstance();
        $query = array (
            'siteid'    => $session->getSiteId(),
            'kw'        => $keyword,
            'source'    => $session->getSource(),
            'ref'       => $session->getLandingReferer(),
            'country'   => $country,
            'landingurl'=> $session->getLandingUri(),
            'userip'    => $this->_request->getClientIp(),
            'useragent' => $this->_request->getUserAgent(),
            'sessionid' => $session->getSessionId(),
            'channeltag'=> $channelTag,
        );

        if ($sponsorType == Tracking_Constant::SPONSOR_GOOGLE) {
            $requestHost = rtrim(TAG_SERVICE_GSTS_BASE_URL, '/') . '/setgtag/?';
        } else {
            $requestHost = rtrim(TAG_SERVICE_BASE_URL, '/') . '/settag/?';
        }

        $requestUrl  = $requestHost . http_build_query($query);
        if ($this->_request->getCookie('debug')=='yes') {
            $requestType = ($sponsorType == Tracking_Constant::SPONSOR_GOOGLE) ? 'G-STS' : 'Y-STS';
            echo $requestType . " Url: " . $requestUrl;
        }

        try {
            $this->_requestData($requestUrl, 1);
        } catch (Exception $exception) {
            $this->_logger->Error(array(
                'remark'        => "request channel tag error: {$exception->getMessage()}",
                'requestUri'    => $requestUrl,
                'referer'       => $this->_request->getRequestUri(),
            ));
        }
    }

    /**
     * @see Tracking_Redirector_Abstract->_doDispatch()
     */
    protected function _doDispatch ()
    {
        $isMatched      = $this->_request->getParam(Tracking_Uri::IS_MATCHED, null);
        $expireTime     = $this->_request->getParam(Tracking_Uri::EXPIRED_TIME, 0);
        $requestCountry = $this->_request->getParam(Tracking_Uri::REQUEST_COUNTRY, '');
        if (($this->_sponsorType == Tracking_Constant::SPONSOR_OVERTURE) ||
            (!is_null($isMatched) && (!$isMatched || ((integer)$expireTime) < time()))) {
            $this->_requestChannelTag($this->_keyword, $requestCountry, $this->_channelTag, $this->_sponsorType);
        }

        /* invalid outgoing clicks filter */
        $this->_outgoingType = $this->_session->getTrafficType();
        if($this->_session->isNormalTraffic() && $this->_isFraud($this->_keyword, $this->_displayPosition, $this->_sponsorType)) {
            $this->_outgoingType = -4;
        }

        $this->_logOutgoing();
        
        $this->_GARI_Yahoo();
        
        if ($this->_request->getParam('track_traffic') === 'test') {
            echo '<pre>';
            print_r($this->_outgoingLog);
            echo '</pre>';
            exit;
        }
        $this->_httpResponseCode = ($this->_sponsorType == Tracking_Constant::SPONSOR_GOOGLE) ? 301 : 302 ;
    }

    protected function _GARI_Yahoo ()
    {
        if (strtoupper($this->_sponsorType) == 'YAHOO') {
            $pathname = $this->_request->getParam('pathname');
            $revPartnerType = $this->_request->getParam('revPartnerType');
            $revPartner = $this->_request->getParam('revPartner');
            if (null == $revPartner) {
                $revPartner = 'yahoosl';
            }
            $country = $this->_request->getParam('country');
            $clickArea = $this->_request->getParam(Tracking_Uri::CLICK_AREA);
            $currandstr = Tracking_Session::getInstance()->getRequestId();
            if ($currandstr == false) {
                $keyPrefix = $_SERVER['SERVER_ADDR'] . '-' . $_SERVER['REMOTE_ADDR'];
                $currandstr = Tracking_Session::getInstance()->generateUniqueId('userId' . $keyPrefix);
            }
            $ga_params = array(
                    // 'config_track_ecomm' => false,
                    // 'config_track_event' => false,
                    'kw' => $this->_keyword,
                    'channelTag' => $this->_channelTag,
                    'clickArea' => $clickArea,
                    'curRandStr' => $currandstr,
                    'pathname' => $pathname,
                    'revPartnerType' => $revPartnerType,
                    'revPartner' => $revPartner,
                    'country' => $country
            );
            
            Tracking_Revenue_Ingest::getInstance($ga_params)->GALogger();
        }
    }
    
}