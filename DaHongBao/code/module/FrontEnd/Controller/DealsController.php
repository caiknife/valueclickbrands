<?php
/*
 * package_name : DealsController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: DealsController.php,v 1.4 2013/05/08 04:26:43 rizhang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use Custom\Util\PathManager;
use CommModel\Category\Category;
use CommModel\Coupon\Coupon;
use CommModel\Coupon\CouponExtra;
use Zend\Paginator\Paginator;
use CommModel\Ads\Google;
use Custom\Util\TrackingFE;
use Custom\Util\Utilities;

class DealsController extends FrontEndController 
{
    /*
     * Deals List Page
     */
    public function indexAction()
    {
        //获取page sortby
        preg_match('/^(\/cuxiao)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches)) {
            throw new \Exception("deals list  error");
        }
        if (isset($matches[2])) {
            $str = str_replace(array('/', 'page-', 'sortby-'), "", $matches[2]);
            $str = explode('-', $str);
            if (count($str) == '1' && is_numeric($str[0])) {
                $page = $str[0] ? $str[0] : "1";
            } elseif (count($str) == '1' && !is_numeric($str[0])) {
                $sortby = $str[0] ? $str[0] : 'new';
            } elseif(count($str) == '2' && is_numeric($str[0])) {
                $page = $str[0];
                $sortby = $str[1] ? $str[1] : 'new';
            } else {
                $page = $str[1];
                $sortby = $str[0] ? $str[0] : 'new';
            }
        }
        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        $this->layout()->currentPage = PathManager::getDhbDealsUrl(); //导航选中
        //$this->layout()->siteConfg = $this->_getSiteConfg();

        //获取促销列表
        $couponTable   = $this->_getTable('Coupon');
        $adapter = $couponTable->getDealsList(self::SITEID, $sortby);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);

        $dealsList = $paginator->getCurrentItems()->toArray();
        foreach($dealsList as $key => $deals) {
            $dealsList[$key]['DealsdetailUrl'] = PathManager::getDhbDealsDetailUrl($deals['CouponID']);
            $dealsList[$key]['OfferUrl'] = TrackingFE::registerDHBOfferLink($deals);
        }
        //echo '<pre>';print_r($dealsList);exit;

        //右侧热券推荐
        $hotCouponList = $couponTable->getDhbHotCouponList(self::SITEID, 20);

        $res = array(
            'sortbyNew'       => PathManager::getDhbDealsUrl(null, 'new'),
            'sortbyHot'       => PathManager::getDhbDealsUrl(null, 'hot'),
            'page'            => $page,
            'sortby'          => $sortby,
            'dealsList'       => $dealsList,
            'paginator'       => $paginator,
            'hotCouponList'   => $hotCouponList,
        );
        return new ViewModel($res);
    }
    /*
     * Deals Detail Page
     */
    public function detailAction()
    {
        preg_match('/^(\/cuxiao-[0-9]+)\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("quan id is error");
        }
        $id = str_replace("/cuxiao-", "", $matches[1]);

        //获取促销的详细信息
        $couponTable = $this->_getTable('Coupon');
        $dealsInfo = $couponTable->getCouponInfoByID($id, self::SITEID, 'DISCOUNT');
        if (empty($dealsInfo)) {
            throw new \Exception("cuxiao {$id} is error");
        }

        //展示数 + 1
        $couponExtraTable = $this->_getTable('CouponExtra');
        $couponExtraTable->addCouponViewCnt($id);

        $offerUrl = TrackingFE::registerDHBOfferLink($dealsInfo, false);
        $dealsInfo['OfferUrl'] = $offerUrl;
        $dealsInfo['CouponNameSort'] = Utilities::cutString(strip_tags($dealsInfo['CouponName']), 100);

        $this->layout()->currentPage = PathManager::getDhbDealsUrl(); //导航选中
        //$this->layout()->siteConfg = $this->_getSiteConfg();

        $merchantTable = $this->_getTable('Merchant');

        //商家其他优惠券
        $merid = $dealsInfo['MerchantID'];
        $couponTable   = $this->_getTable('Coupon');
        $couponList = $couponTable->getCouponList(self::SITEID, $merid, 51, 'new');
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($coupon['CouponID']);
            $couponList[$key]['MerchantInfo'] = $merchantTable->getMerchantInfoByID($merid, self::SITEID);
            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($coupon['MerchantID']);
            TrackingFE::registerDHBOfferLink($coupon, true, false);
        }
        //echo '<pre>';print_r($couponList);exit;

        //右侧googleAds
        $googleAdsList = Google::getGoogleAds($dealsInfo['MerchantName']);//keyword为当前商家名

        //右侧最新精选折扣
        $couponTable = $this->_getTable('Coupon');
        $newDealsList = $couponTable->getDhbNewDealsList(self::SITEID);

        //右侧热券推荐
        $couponTable = $this->_getTable('Coupon');
        $hotCouponList = $couponTable->getDhbHotCouponList(self::SITEID, 20);

        $res = array(
            'dealsUrl'        => PathManager::getDhbDealsUrl(),
            'dealsInfo'       => $dealsInfo,
            'couponList'      => $couponList,
            'googleAdsList'   => $googleAdsList,
            'newDealsList'    => $newDealsList,
            'hotCouponList'   => $hotCouponList,
        );
        return new ViewModel($res);
    }
}
?>