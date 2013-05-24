<?php
/*
 * package_name : IndexController.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: IndexController.php,v 1.2 2013/04/22 10:37:47 rizhang Exp $
 */
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Merchant\Merchant;
use CommModel\Article\Article;
use CommModel\Ads\Google;
use CommModel\Coupon\Coupon;
use CommModel\Category\Category;
use CommModel\Coupon\Favorite;
use Custom\Util\Utilities;
use Custom\Util\TrackingFE;
use Custom\Util\PathManager;
use Zend\Paginator\Paginator;

class IndexController extends UsController 
{
    public function indexAction()
    {
        // 共同数据，初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $merchantTable = $this->_getTable('Merchant');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantPaymentTable = $this->_getTable('MerchantPayment');

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //特价促销
        $specialDealsList = $couponTable->getSpecialDealsList();

        //热门商家
        $hotMerchantList = $merchantTable->getHotMerchantList(self::SITEID);

        //热门优惠券
        $uid = (int)$_COOKIE['UID'];
        $couponList = $couponTable->getCouponList(self::SITEID, null, 15);
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
        $res = array(
            'categoryList'     => $categoryList,
            'siteHelpList'     => $siteHelpList,
            'helpCenterList'   => $helpCenterList,
            'specialDealsList' => $specialDealsList,
            'specialDealsMore' => PathManager::getDealsListUrl(),
            'googleAdsList'    => Google::getGoogleAds(),
            'hotMerchantList'  => $hotMerchantList,
            'hotMerchantMore'  => PathManager::getAllMerchantUrl(),
            'couponList'       => $couponList,
            'couponMore'       => PathManager::getAllCateUrl(),
        );
        return new ViewModel($res);
    }
}
?>