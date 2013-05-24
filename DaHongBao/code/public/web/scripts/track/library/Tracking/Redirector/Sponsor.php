<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Sponsor.php,v 1.1 2013/04/15 10:58:19 rock Exp $
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

    const TAG_SERVICE_BASE_URL  = TAG_SERVICE_BASE_URL;

    /**
     * keyword
     *
     * @var string
     */
    private $_keyword = '';

    /**
     * rank
     *
     * @var string
     */
    private $_displayPosition = '';

    /**
     * channelTag
     *
     * @var string
     */
    private $_channelTag = '';

    /**
     * sponsor type
     *
     * @var string
     */
    private $_sponsorType = '';

    /**
     * advertiser host
     *
     * @var string
     */
    private $_advertiserHost = '';

    /**
     * outgoing type
     *
     * @var integer
     */
    private $_outgoingType   = 0;

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_channelTag      = $this->_getChannelTag();
        $this->_keyword         = $this->_getKeyword();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_sponsorType     = $this->_getSponsorType();
        $this->_advertiserHost  = $this->_getAdvertiserHost();
    }

    /**
     * get advertiser host
     *
     * @return string
     */
    private function _getAdvertiserHost() {
        return $this->_request->getParam(Tracking_Uri::ADVERTISER_HOST, '');
    }

    /**
     * get channel tag
     *
     * @return string
     */
    private function _getChannelTag() {
        return $this->_request->getParam(Tracking_Uri::CHANNEL_TAG, '');
    }

    /**
     * get keyword
     *
     * @return string
     */
    private function _getKeyword() {
        return $this->_request->getParam(Tracking_Uri::KEYWORD, '');
    }

    /**
     * get rank
     *
     * @return integer
     */
    private function _getDisplayPosition()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::DISPLAY_POSITION, 0);
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
     * @param string $sponsorType
     * @param string $keyword
     * @param integer $rank
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
     * log special information
     */
    private function _logOutgoing()
    {
        $logSponsorOutgoing = array(
            'desturl'           => $this->_destinedUrl,
            'keyword'           => $this->_keyword,
            'displayPosition'   => $this->_displayPosition,
            'channelId'         => $this->_channelId,
            'categoryId'        => $this->_categoryId,
            'channelTag'        => $this->_channelTag,
            'sponsorType'       => $this->_sponsorType,
            'advertiserHost'    => $this->_advertiserHost,
            'revenue'           => $this->_revenue,
            'outgoingType'      => $this->_outgoingType,
        );
        $this->_logger->sponsorOutgoing($logSponsorOutgoing);
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
     * @param integer $siteId
     * @param string  $keyword
     * @param string  $source
     * @param string  $country
     */
    private function _requestChannelTag($siteId, $keyword, $source, $country)
    {
        $query      = array('siteid' => $siteId, 'kw'=>$keyword, 'source'=> $source, 'country'=>$country);
        $requstUrl  = self::TAG_SERVICE_BASE_URL . '/setgtag/?' . http_build_query($query);
        try {
            $this->_requestData($requstUrl, 1);
        } catch (Exception $exception) {
            $errorLog = array(
                'remark'        => "request channel tag error: {$exception->getMessage()}",
                'requesturi'    => $requstUrl,
                'referer'       => $this->_request->getRequestUri(),
            );
            $this->_logger->Error($errorLog);
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
        $siteId         = $this->_session->getSiteId();
        $source         = $this->_session->getSource();
        if (($this->_sponsorType==Tracking_Constant::SPONSOR_GOOGLE) && !is_null($isMatched) && (!$isMatched || ((int) $expireTime) < time())) {
            $this->_requestChannelTag($siteId, $this->_keyword, $source, $requestCountry);
        }

        /* invalid outgoing clicks filter */
        $this->_outgoingType = $this->_session->getTrafficType();
        if($this->_session->isNormalTraffic() && $this->_isFraud($this->_keyword, $this->_displayPosition, $this->_sponsorType)) {
            $this->_outgoingType = -4;
        }

        $this->_logOutgoing();

        $this->_httpResponseCode = ($this->_sponsorType==Tracking_Constant::SPONSOR_GOOGLE) ? 301 : 302;
    }
}