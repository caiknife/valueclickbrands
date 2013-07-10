<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Affiliate.php,v 1.1 2013/07/10 01:34:46 jjiang Exp $
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
     * get click area id from uri
     *
     * @return integer
     */
    private function _getClickArea() {
        return (integer) $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
    }

    /**
     * get destind site from uri
     *
     * @return string
     */
    private function _getDestinedSite() {
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
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_clickArea   = $this->_getClickArea();
        $this->_destinedSite= $this->_getDestinedSite();
        $this->_merchantId  = $this->_getMerchantId();
        $this->_productId   = $this->_getProductId();
        $this->_outgoingId      = 'f' . $this->_generateOutgoingId(24, Tracking_Session::getInstance()->getSiteId().date('mdHy'));
    }

    private function _generateOutgoingId($length = 32, $prefix = '')
    {
        return substr($prefix . md5(uniqid(mt_rand(), true)), 0, (int) $length);
    }

    private function _appendOutgoingBeacon($url, $beaconTag, $outgoingId)
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

        if (strcasecmp($this->_destinedSite, "SMARTER")==0) {
            $siteId             = Tracking_Session::getInstance()->getSiteId();
            $sessionId          = Tracking_Session::getInstance()->getSessionId();
            $requestId          = Tracking_Session::getInstance()->getRequestId();
            $connector          = strpos($this->_destinedUrl, '?')===false ? '?': '&';
            $this->_destinedUrl = $this->_destinedUrl . "{$connector}si={$siteId}&se={$sessionId}&cr={$requestId}";
        } else {
			if(strpos($this->_destinedUrl,"linksynergy.com")!==false){
				$this->_destinedUrl = $this->_appendOutgoingBeacon($this->_destinedUrl, "&u1=", $this->_outgoingId);
			}else if(strpos($this->_destinedUrl,".cj.com")!==false){
				$this->_destinedUrl = $this->_appendOutgoingBeacon($this->_destinedUrl, "&SID=", $this->_outgoingId);
			}
            $this->_outgoingLog = array(
                'productId'  => $this->_productId,
                'merchantId' => $this->_merchantId,
                'categoryId' => $this->_categoryId,
                'channelId'  => $this->_channelId,
                'clickArea'  => $this->_clickArea,
                'destSite'   => $this->_destinedSite,
                'destUrl'    => $this->_destinedUrl,
                'revenue'    => $this->_revenue,
            );
            if ($this->_needLog) {
                $this->_logger->AffiliateOutgoing($this->_outgoingLog);
            }
        }
    }
}