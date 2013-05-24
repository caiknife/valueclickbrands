<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Async
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Sponsor.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

/**
 * parse the sponsor's url and log it
 *
 * @category   Mezi
 * @package    Mezi_Async
 */
class Tracking_Async_Sponsor extends Tracking_Async_Abstract
{
    const COOKIE_SPONSOR_CLICKS = 'mm_spn';

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
     * cpc
     *
     * @var string
     */
    private $_cpc = 0;

    /**
     * impression count
     *
     * @var integer
     */
    private $_impressionCount = 0;

    /**
     * sponsor type
     *
     * @var integer
     */
    private $_sponsorType = 0;

    /**
     * advertiser host
     *
     * @var string
     */
    private $_advertiserHost = '';

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_channelTag      = $this->_getChannelTag();
        $this->_destinedUrl     = $this->_getDestinedUrl();
        $this->_keyword         = $this->_getKeyword();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_sponsorType     = $this->_getSponsorType();
        $this->_advertiserHost  = $this->_getAdvertiserHost();
        $this->_cpc             = $this->_getCpc();
        $this->_impressionCount = $this->_getImpressionCount();
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
        return $this->_request->getParam(Tracking_Uri::CHANNEL_TAG);
    }

    /**
     * get channel tag
     *
     * @return string
     */
    private function _getCpc() {
        return $this->_request->getParam(Tracking_Uri::CPC, 0);
    }

    /**
     * get the destined url
     *
     * @return string
     */
    protected function _getDestinedUrl()
    {
        return $this->_request->getParam(Tracking_Uri::DESTINED_URL);
    }

    /**
     * get the impression count
     *
     * @return integer
     */
    protected function _getImpressionCount()
    {
        return (integer) $this->_request->getParam(Tracking_Uri::IMPRESSION_COUNT, 0);
    }

    /**
     * get keyword
     *
     * @return string
     */
    private function _getKeyword() {
        return $this->_request->getParam(Tracking_Uri::KEYWORD);
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
     * @return integer
     */
    private function _getSponsorType()
    {
        return isset(Mezi_Config::getInstance()->tracking->sponsor->type)
               ? (integer) Mezi_Config::getInstance()->tracking->sponsor->type
               : 0;
    }

    /**
     * invalid outgoing clicks filter
     * the multi clicks for per keyword per rank in one session just count as one click.
     *
     * @param string $keyword
     * @param integer $rank
     * @return boolean
     */
    private function _isFraud($keyword, $rank)
    {
        $result = FALSE;

        $curClick = md5("$rank|$keyword");
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
        $logSponsorOutgoing = array(
            'desturl'           => $this->_destinedUrl,
            'keyword'           => $this->_keyword,
            'displayPosition'   => $this->_displayPosition,
            'channelid'         => $this->_channelId,
            'channeltag'        => $this->_channelTag,
            'sponsortype'       => $this->_sponsorType,
            'advertiserHost'    => $this->_advertiserHost,
            'revenue'           => $this->_cpc,
        );
        $this->_logger->sponsorOutgoing($logSponsorOutgoing);
    }

    /**
     * log special infomation
     */
    private function _logImpression()
    {
        $logSponsorOutgoing = array(
            'channelid'         => $this->_channelId,
            'channeltag'        => $this->_channelTag,
            'impressionCount'   => $this->_impressionCount,
            'keyword'           => $this->_keyword,
            'sponsortype'       => $this->_sponsorType,
        );
        $this->_logger->sponsorOutgoing($logSponsorOutgoing);
    }

    /**
     * @see Tracking_Async_Abstract->_doDispatch()
     */
    protected function _doDispatch ()
    {
        /** invalid outgoing clicks filter */
        if($this->_session->isNormalTraffic()) {
            $this->_logImpression();
        }
    }
}
?>
