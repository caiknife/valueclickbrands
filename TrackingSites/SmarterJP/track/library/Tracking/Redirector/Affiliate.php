<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Affiliate.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
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
	 * Click area for each outgong clicks
	 * @var varchar
	 */
	private $_clickArea = '';

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

	private function _getClickArea()
    {
        return $this->_request->getParam(Tracking_Uri::CLICK_AREA, 0);
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
		$this->_clickArea	= $this->_getClickArea();
    }

    /**
     * @see Tracking_Redirector_Abstract::doDispatch()
     */
    protected function _doDispatch ()
    {
        switch(strtoupper($this->_destinedSite)) {
            case 'YAHOOSHOPPING':
            case 'YAHOOAUCTION':
            case 'VCSHOPPING':
            case 'VCTRAVEL':
            case 'VCBANNER-MONEY':
                $affiliateTag   = 'vcptn';
                break;
            case 'SHOPPING':
                $affiliateTag   = 'op';
                break;
			case 'AMAZON':                
			case 'AMAZONSHOPPING':
				$affiliateTag   = 'ascsubtag';
                break;
			default:
                $affiliateTag   = '';
                break;
        }

        if(!empty($affiliateTag)) {
            $siteId     = (int) Mezi_Config::getInstance()->tracking->site->id;
            $outgoingId = 'a' . $this->_generateOutgoingId(24, $siteId . date('mdHy'));   // 'a' means sdc api

            if(stripos($this->_destinedUrl, "&{$affiliateTag}=") !== false || stripos($this->_destinedUrl, "?{$affiliateTag}=") !== false ) {
                $this->_destinedUrl  = preg_replace("/([\?|&]){$affiliateTag}=([^&]*)/i", '${1}' . "{$affiliateTag}=" . $outgoingId, $this->_destinedUrl);
            } else {
                $this->_destinedUrl .= "&{$affiliateTag}={$outgoingId}";
            }
        }
		if (preg_match ( '/rakuten.co.jp/', $this->_destinedUrl ) && trim(strtoupper($this->_destinedSite))!="RAKUTENWIDGET") {
			$siteId = ( int ) Mezi_Config::getInstance ()->tracking->site->id;
			$outgoingId = 'a' . $this->_generateOutgoingId ( 24, $siteId . date ( 'mdHy' ) ); // 'a' means sdc api
			$this->_destinedUrl = preg_replace ( '|http://(.+?)/(.+?)/(.+?)/(?:.+?)?(\?.*)|', "http://$1/$2/$3/{$outgoingId}$4", $this->_destinedUrl );
		}

        $logAffiliateOutgoing = array(
                'productid'     => $this->_productId,
                'merchantid'    => $this->_merchantId,
                'categoryid'    => $this->_categoryId,
                'channelid'     => $this->_channelId,
                'clickarea'     => $this->_clickArea,
                'destsite'      => $this->_destinedSite,
                'desturl'       => $this->_destinedUrl,
                'revenue'       => $this->_revenue
        );
        $this->_logger->AffiliateOutgoing($logAffiliateOutgoing);
	}

}