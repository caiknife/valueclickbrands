<?php
/*
* package_name : CategoryController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: CategoryController.php,v 1.6 2013/04/26 04:09:43 rizhang Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Category\Category;
use CommModel\Merchant\Merchant;
use CommModel\Merchant\MerchantCategory;
use CommModel\Ads\Google;
use CommModel\Coupon\Coupon;
use CommModel\Coupon\CouponCategory;
use Custom\Util\Utilities;
use Custom\Util\TrackingFE;
use Custom\Util\PathManager;
use Zend\Paginator\Paginator;

class CategoryController extends UsController 
{
    /*
     * All Category
     */
    public function indexAction()
    {
        //获取merid
        preg_match('/^(\/category)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (isset($matches[2])) {
            $page = str_replace(array('/', 'page-'), "", $matches[2]);
        }

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        // head footer，共同数据，初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantTable = $this->_getTable('Merchant');
        $merchantPaymentTable = $this->_getTable('MerchantPayment');

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        // 站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        // 帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        // 热门优惠券
        $hotCouponList = $couponTable->getHotCouponList();

        // 热门促销
        $hotDealsList = $couponTable->getHotDealsList();

        // 热门商家
        $hotMerchantList = $merchantTable->getHotMerchantList(self::SITEID);

        // 热门优惠券
        $uid = (int)$_COOKIE['UID'];
        $adapter = $couponTable->getCouponList(self::SITEID);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $couponList = $paginator->getCurrentItems()->toArray();
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponFavorite'] = false;
            if ($uid) {
                $result = $favoriteTable->isFavoriteCoupon($uid, $coupon['CouponID']);
                if ($result) {
                    $couponList[$key]['CouponFavorite'] = true;
                }
            }
            $offerUrl = TrackingFE::registerOfferLink($coupon);
            $couponList[$key]['OfferUrl'] = $offerUrl;
            $couponList[$key]['MerchantInfo'] = $merchantTable->getMerchantInfoByID($coupon['MerchantID'], self::SITEID);
            $couponList[$key]['MerchantInfo']['Payment'] = $merchantPaymentTable->getMerchantPayment($coupon['MerchantID']);
            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($coupon['MerchantID']);
            $couponList[$key]['MerchantInfo']['DescriptionCN'] = Utilities::cutString(strip_tags($couponList[$key]['MerchantInfo']['DescriptionCN']), 120);
        }

        $assign = array(
            'categoryList'    => $categoryList,
            'siteHelpList'    => $siteHelpList,
            'helpCenterList'  => $helpCenterList,
            'hotCouponList'  => $hotCouponList,
            'hotCouponMore'  => PathManager::getAllCateUrl(),
            'hotDealsList'   => $hotDealsList,
            'hotDealsMore'   => PathManager::getDealsListUrl(),
            'googleAdsList'   => Google::getGoogleAds(),
            'hotMerchantList' => $hotMerchantList,
            'hotMerchantMore' => PathManager::getAllMerchantUrl(),
            'page'            => $page,
            'paginator'       => $paginator,
            'couponList'      => $couponList,
        );
        return new ViewModel($assign);
    }

    /*
     * Category List
     */
    public function categoryAction()
    {
        preg_match('/^(\/category-[0-9]+)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("catid is error");
        }
        $catid = str_replace("/category-", "", $matches[1]);
        if (isset($matches[2])) {
            $page = str_replace(array('/', 'page-'), "", $matches[2]);
        }

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $favoriteTable = $this->_getTable('Favorite');
        $categoryTable = $this->_getTable('Category');
        $couponCategoryTable = $this->_getTable('CouponCategory');
        $merchantTable = $this->_getTable('Merchant');
        $merchantCategoryTable = $this->_getTable('MerchantCategory');
        $merchantPaymentTable = $this->_getTable('MerchantPayment');

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        //category存不存在
        $categoryTable  = $this->_getTable('Category');
        $categoryName = $categoryTable->getCategoryNameByID($catid);
        if (empty($categoryName)) {
            throw new \Exception('catid {$catid} not exist');
        }

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        // 帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        // 推荐优惠券
        $hotCouponList = $couponTable->getHotCouponList();

        // 热门促销
        $hotDealsList = $couponTable->getHotDealsList();

        // 推荐商家商家
        $recommendMerchantList = $merchantCategoryTable->getRecommendMerchantList(self::SITEID, $catid);

        //热门优惠券
        $uid = (int)$_COOKIE['UID'];
        $adapter = $couponCategoryTable->getCategoryCouponList(self::SITEID, $catid);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $couponList = $paginator->getCurrentItems()->toArray();
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponFavorite'] = false;
            if ($uid) {
                $result = $favoriteTable->isFavoriteCoupon($uid, $coupon['CouponID']);
                if ($result) {
                    $couponList[$key]['CouponFavorite'] = true;
                }
            }
            $offerUrl = TrackingFE::registerOfferLink($coupon);
            $couponList[$key]['OfferUrl'] = $offerUrl;
            $couponList[$key]['MerchantInfo'] = $merchantTable->getMerchantInfoByID($coupon['MerchantID'], self::SITEID);
            $couponList[$key]['MerchantInfo']['Payment'] = $merchantPaymentTable->getMerchantPayment($coupon['MerchantID']);
            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($coupon['MerchantID']);
            $couponList[$key]['MerchantInfo']['DescriptionCN'] = Utilities::cutString(strip_tags($couponList[$key]['MerchantInfo']['DescriptionCN']), 120);
        }

        $assign = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'hotCouponList'  => $hotCouponList,
            'hotCouponMore'  => PathManager::getAllCateUrl(),
            'hotDealsList'   => $hotDealsList,
            'hotDealsMore'   => PathManager::getDealsListUrl(),
            'googleAdsList'  => Google::getGoogleAds(),
            'recommendMerchantList' => $recommendMerchantList,
            'recommendMerchantMore' => PathManager::getAllMerchantUrl(),
            'page'           => $page,
            'paginator'      => $paginator,
            'couponList'     => $couponList,
            'categoryID'     => $catid,
            'categoryName'   => $categoryList[$catid]['CategoryName'],
        );
        return new ViewModel($assign);
    }

    /*
     * Category Merchant Page
     */
    public function merchantAction()
    {
        //获取分类的字母
        preg_match('/^(\/category-[0-9]+-merchant-[0-9]+)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("分类id错误");
        }
        $explode = explode('-merchant-', $matches[1]);
        $catid = str_replace("/category-", "", $explode[0]);
        $merid = $explode[1];
        if (isset($matches[2])) {
            $page = str_replace(array('/', 'page-'), "", $matches[2]);
        }

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantTable = $this->_getTable('Merchant');
        $couponCategoryTable = $this->_getTable('CouponCategory');
        $merchantCategoryTable = $this->_getTable('MerchantCategory');
        $merchantPaymentTable = $this->_getTable('MerchantPayment');

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);
        if (empty($categoryList[$catid])) {
            throw new \Exception("分类id：{$catid}不存在");
        }

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        $merchantInfo = $merchantTable->getMerchantInfoByID($merid, self::SITEID);
        $merchantInfo['DescriptionCN']      = strip_tags($merchantInfo['DescriptionCN']);
        $merchantInfo['DescriptionCNShort'] = Utilities::cutString($merchantInfo['DescriptionCN'], 100);
        if (empty($merchantInfo)) {
            throw new \Exception("商家id：{$merid}不存在!");
        }
        $merchantPaymentTable = $this->_getTable('MerchantPayment');
        $merchantInfo['Payment'] = $merchantPaymentTable->getMerchantPayment($merid, false);
        $merchantOfferUrl = TrackingFE::registerMerchantLink($merchantInfo);
        $merchantInfo['MerchantOfferUrl'] = $merchantOfferUrl;

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //推荐优惠券
        $hotCouponList = $couponTable->getHotCouponList();
        
        //热门促销
        $hotDealsList = $couponTable->getHotDealsList();

        //推荐商家商家
        $recommendMerchantList = $merchantCategoryTable->getRecommendMerchantList(self::SITEID, $catid, $merid);

        //热门优惠券
        $uid = (int)$_COOKIE['UID'];
        $adapter = $couponCategoryTable->getCategoryCouponList(self::SITEID, $catid, null, null, $merid);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $couponList = $paginator->getCurrentItems()->toArray();
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponFavorite'] = false;
            if ($uid) {
                $result = $favoriteTable->isFavoriteCoupon($uid, $coupon['CouponID']);
                if ($result) {
                    $couponList[$key]['CouponFavorite'] = true;
                }
            }
            $offerUrl = TrackingFE::registerOfferLink($coupon);
            $couponList[$key]['OfferUrl'] = $offerUrl;
            $couponList[$key]['MerchantInfo'] = $merchantInfo;
        }

        $assign = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'hotCouponList'  => $hotCouponList,
            'hotCouponMore'  => PathManager::getAllCateUrl(),
            'hotDealsList'   => $hotDealsList,
            'hotDealsMore'   => PathManager::getDealsListUrl(),
            'googleAdsList'  => Google::getGoogleAds(),
            'merchantInfo'   => $merchantInfo,
            'recommendMerchantList' => $recommendMerchantList,
            'recommendMerchantMore' => PathManager::getAllMerchantUrl(),
            'page'           => $page,
            'paginator'      => $paginator,
            'couponList'     => $couponList,
            'merchantID'     => $merid,
            'categoryID'     => $catid,
            'categoryName'   => $categoryList[$catid]['CategoryName'],
            'showMerchantCouponInfo' => false, //CouponList鼠标移到商家区域，不显示效果
        );
        return new ViewModel($assign);
    }
}
?>