<?php
/**
 * Tracking Component
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Dilingling.php,v 1.2 2013/04/16 05:08:31 jjiang Exp $
 */

/**
 * parse the dilingling url and log it, only for smcn
 *
 * @package    Tracking
 * @subpackage Tracking_Redirector
 */
class Tracking_Redirector_Dilingling extends Tracking_Redirector_Abstract
{

    /**
     * category id
     *
     * @var integer
     */
    protected $_categoryId        = 0;

    /**
     * display position
     *
     * @var integer
     */
    private $_displayPosition   = 0;

    /**
     * keyword
     *
     * @var string
     */
    private $_keyword           = '';

    /**
     * product id
     *
     * @var integer
     */
    private $_productId         = 0;

    /**
     * product name
     *
     * @var string
     */
    private $_productName       = '';

    /**
     * remark
     *
     * @var string
     */
    private $_remark            = '';

    /**
     * get category id from uri
     *
     * @return integer
     */
    protected function _getCategoryId() {
        return (integer) $this->_request->getParam(Tracking_Uri::CATEGORY_ID, 0);
    }

    /**
     * get display position from uri
     *
     * @return string
     */
    private function _getDisplayPosition() {
        return $this->_request->getParam(Tracking_Uri::DISPLAY_POSITION, 0);
    }

    /**
     * get keyword from uri
     *
     * @return string
     */
    private function _getKeyword() {
        return $this->_request->getParam(Tracking_Uri::KEYWORD, '');
    }

    /**
     * get product id from uri
     *
     * @return integer
     */
    private function _getProductId() {
        return (integer) $this->_request->getParam(Tracking_Uri::PRODUCT_ID, 0);
    }

    /**
     * get product name from uri
     *
     * @return string
     */
    private function _getProductName() {
        return $this->_request->getParam(Tracking_Uri::PRODUCT_NAME, '');
    }

    /**
     * get remark id from uri
     *
     * @return string
     */
    private function _getRemark() {
        return $this->_request->getParam(Tracking_Uri::REMARK, '');
    }

    /**
     * parse current request
     */
    protected function _parseRequest()
    {
        parent::_parseRequest();

        $this->_categoryId      = $this->_getCategoryId();
        $this->_displayPosition = $this->_getDisplayPosition();
        $this->_keyword         = $this->_getKeyword();
        $this->_productId       = $this->_getProductId();
        $this->_productName     = $this->_getProductName();
        $this->_remark          = $this->_getRemark();
    }

    /**
     * @see Tracking_Redirector_Abstract::doDispatch()
     */
    protected function _doDispatch ()
    {
        $logDilinglingOutgoing = array(
            'channelid'         => $this->_channelId,
            'categoryid'        => $this->_categoryId,
            'desturl'           => $this->_destinedUrl,
            'displayposition'   => $this->_displayPosition,
            'keyword'           => $this->_keyword,
            'productid'         => $this->_productId,
            'productname'       => $this->_productName,
            'remark'            => $this->_remark,
        );
        $this->_logger->DilinglingOutgoing($logDilinglingOutgoing);
    }
}