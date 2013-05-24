<?php
/*
 * package_name : CouponController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: CouponController.php,v 1.10 2013/04/22 04:22:06 rizhang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use Custom\Util\PathManager;
use CommModel\Category\Category;
use CommModel\Coupon\Coupon;
use CommModel\Coupon\CouponExtra;
use CommModel\Coupon\UserCouponCode;
use Zend\Paginator\Paginator;
use CommModel\Ads\Google;
use CommModel\Coupon\Favorite;
use Custom\Util\TrackingFE;
use Custom\Util\Utilities;

class CouponController extends FrontEndController 
{
    const PAGE = 20;//这里分页20条
    /*
     * Coupon Overall List Page
     */
    public function indexAction()
    {
        //获取page sortby
        preg_match('/^(\/quan-all)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
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

        $this->layout()->currentPage = PathManager::getDhbCouponUrl(); //导航选中

        //top 9个category
        $categoryTable  = $this->_getTable('Category');
        $topCategoryList = $categoryTable->getDhbTopCategoryList(self::SITEID);

        //热门商家48个，先显示16个
        $couponTable   = $this->_getTable('Coupon');
        $merchantTable   = $this->_getTable('Merchant');
        $hotMerchantList = $merchantTable->getHotMerchantList(self::SITEID, 48);
        foreach ($hotMerchantList as $key => $hotMerchant) {
            $count = $couponTable->getMerchantCouponCount($hotMerchant['MerchantID']);
            if ($count == 0) {
                unset($hotMerchantList[$key]);
            } else {
                $hotMerchantList[$key]['CouponCount'] = $count;
            }
        }
        $hotMerchantList = array_values($hotMerchantList);

        //获取优惠券列表
        $adapter = $couponTable->getCouponList(self::SITEID, NULL, NULL, $sortby);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);

        $couponList = $paginator->getCurrentItems()->toArray();
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($coupon['CouponID']);
            //$couponList[$key]['CouponDescriptionSort'] = Utilities::cutString(strip_tags($couponList[$key]['CouponDescription']), 200);
            $couponList[$key]['MerchantInfo'] = $merchantTable->getMerchantInfoByID($coupon['MerchantID'], self::SITEID);
            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getDhbMerchantDetailUrl($coupon['MerchantID']);
            TrackingFE::registerDHBOfferLink($coupon, true, false);
        }
        //echo "<pre>";print_r($couponList);exit;

        $res = array(
            'topCategoryList' => $topCategoryList,
            'hotMerchantList' => $hotMerchantList,
            'hotMerchantShow' => false, //是否全展示，没有更多
            'topCategoryAll'  => PathManager::getDhbCouponUrl(),
            'sortbyNew'       => PathManager::getDhbCouponUrl(null, 'new'),
            'sortbyHot'       => PathManager::getDhbCouponUrl(null, 'hot'),
            'page'            => $page,
            'sortby'          => $sortby,
            'couponList'      => $couponList,
            'paginator'       => $paginator,
        );
        return new ViewModel($res);
    }

    /*
     * Coupon Detail Page
     */
    public function detailAction()
    {
        preg_match('/^(\/quan-[0-9]+)\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("quan id is error");
        }
        $id = str_replace("/quan-", "", $matches[1]);
        $couponTable = $this->_getTable('Coupon');
        $couponInfo = $couponTable->getCouponInfoByID($id, self::SITEID, 'COUPON', true, true, true);
        if (empty($couponInfo)) {
            throw new \Exception("coupon id is not exist");
        }
        //echo '<pre>';print_r($couponInfo);exit;

        //展示数 + 1
        $couponExtraTable = $this->_getTable('CouponExtra');
        $couponExtraTable->addCouponViewCnt($id);
        $couponInfo['MerchantDetailUrl'] = PathManager::getDhbMerchantDetailUrl($couponInfo['MerchantID']);
        $offerUrl = TrackingFE::registerDHBOfferLink($couponInfo, false);
        $couponInfo['OfferUrl'] = $offerUrl;

        $uid = (int)$_COOKIE['UID'];
        $couponInfo['CouponFavorite'] = false;
        $couponInfo['CouponCodeUser'] = false;
        if ($uid) {
            $favoriteTable = $this->_getTable('Favorite');
            $result = $favoriteTable->isFavoriteCoupon($uid, $couponInfo['CouponID']);
            if ($result) {
                $couponInfo['CouponFavorite'] = true;
            }
            $userCouponCodeTable = $this->_getTable('UserCouponCode');
            $result = $userCouponCodeTable->isUserCouponCode($_COOKIE['UID'], $couponInfo['CouponID']);
            if ($result) {
                $couponInfo['CouponCodeUser'] = true;
            }
        }

        //使用限制
        $couponInfo['CouponReduction'] = Utilities::cutString(strip_tags($couponInfo['CouponReduction']), 30);
        //echo '<pre>';print_r($couponInfo);exit;

        $this->layout()->currentPage = PathManager::getDhbCouponUrl(); //导航选中

        //top 9个category
        $categoryTable  = $this->_getTable('Category');
        $topCategoryList = $categoryTable->getDhbTopCategoryList(self::SITEID);

        //热门商家48个，先显示16个
        $merchantTable   = $this->_getTable('Merchant');
        $hotMerchantList = $merchantTable->getHotMerchantList(self::SITEID, 48);
        foreach ($hotMerchantList as $key => $hotMerchant) {
            $count = $couponTable->getMerchantCouponCount($hotMerchant['MerchantID']);
            if ($count == 0) {
                unset($hotMerchantList[$key]);
            } else {
                $hotMerchantList[$key]['CouponCount'] = $count;
            }
        }
        $hotMerchantList = array_values($hotMerchantList);

        //商家其他优惠券
        $couponList = $couponTable->getCouponList(self::SITEID, $couponInfo['MerchantID'], 9, 'new', $id);
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($coupon['CouponID']);
            $couponList[$key]['MerchantInfo']['LogoFile'] = $couponInfo['LogoFile'];
            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($coupon['MerchantID']);
            TrackingFE::registerDHBOfferLink($coupon, true, false);
        }
        //echo '<pre>';print_r($couponList);exit;

        //右侧googleAds
        $googleAdsList = Google::getGoogleAds($couponInfo['MerchantName']);//keyword为当前商家名

        //分类相关的优惠券
        $relatedCouponList = $couponTable->getDhbNewCouponList(self::SITEID, 10, $id, $couponInfo['CategoryID']);

        //右侧：最新优惠券发布
        $newCouponList = $couponTable->getDhbNewCouponList(self::SITEID, 10, $id);

        //右侧：最新优惠券排行  = 热券推荐
        $hotCouponList = $couponTable->getDhbHotCouponList(self::SITEID);

        $res = array(
            'isCouponDetail'  => true,
            'topCategoryList' => $topCategoryList,
            'hotMerchantList' => $hotMerchantList,
            'hotMerchantShow' => false, //是否全展示，没有更多
            'topCategoryAll'  => PathManager::getDhbCouponUrl(),
            'couponUrl'       => PathManager::getDhbCouponUrl(),
            'couponInfo'      => $couponInfo,
            'couponList'      => $couponList,
            'googleAdsList'   => $googleAdsList,
            'relatedCouponList' => $relatedCouponList,
            'newCouponList'     => $newCouponList,
            'hotCouponList'     => $hotCouponList,
        );
        return new ViewModel($res);
    }
}
?>