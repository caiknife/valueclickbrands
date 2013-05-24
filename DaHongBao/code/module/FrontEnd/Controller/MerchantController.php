<?php
/*
 * package_name : MerchantController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: MerchantController.php,v 1.8 2013/04/22 04:22:06 rizhang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use Custom\Util\PathManager;
use CommModel\Coupon\Coupon;
use CommModel\Merchant\Merchant;
use CommModel\Ads\Google;
use Custom\Util\Utilities;
use Custom\Util\TrackingFE;

class MerchantController extends FrontEndController 
{
    /*
     * Merchant Coupon Page
     */
    public function detailAction()
    {
        //获取merid，无分页，获取前50条
        preg_match('/^(\/merchant-[0-9]+)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("merid is error");
        }

        $couponTable   = $this->_getTable('Coupon');
        
        $merid = str_replace("/merchant-", "", $matches[1]);

        $this->layout()->currentPage = PathManager::getDhbCouponUrl(); //导航选中
        //$this->layout()->siteConfg = $this->_getSiteConfg();
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

        $merchantTable = $this->_getTable('Merchant');
        $merchantInfo = $merchantTable->getMerchantInfoByID($merid, self::SITEID);
        if (empty($merchantInfo)) {
            throw new \Exception("merchant $merid does not exist!");
        }
        $merchantInfo['DescriptionCNSort'] = Utilities::cutString($merchantInfo['DescriptionCN'], 300);

        $merchantOfferUrl = TrackingFE::registerDHBMerchantLink($merchantInfo);
        $merchantInfo['MerchantOfferUrl'] = $merchantOfferUrl;

        //商家优惠券, 无分页
        $couponList = $couponTable->getCouponList(self::SITEID, $merid, 51, 'new');
        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($coupon['CouponID']);
            $couponList[$key]['MerchantInfo'] = $merchantInfo;
            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($coupon['MerchantID']);
            TrackingFE::registerOfferLink($coupon, true, false);
        }
        //echo '<pre>';print_r($couponList);exit;

        //商家优惠券总数
        $merchantCouponCount = $couponTable->getMerchantCouponCount($merid);

        //右侧googleAds
        $googleAdsList = Google::getGoogleAds($merchantInfo['MerchantName']);//keyword为当前商家名

        //右侧推荐商家
        $recommendMerchantList = $merchantTable->getDhbRecommendMerchantCouponList(self::SITEID);

        //右侧热券推荐
        $hotCouponList = $couponTable->getDhbHotCouponList(self::SITEID);

        $res = array(
            'topCategoryList' => $topCategoryList,
            'hotMerchantList' => $hotMerchantList,
            'hotMerchantShow' => false, //是否全展示，没有更多
            'merchantInfo'    => $merchantInfo,
            'couponList'      => $couponList,
            'merchantCouponCount' => $merchantCouponCount,
            'couponUrl'       => PathManager::getDhbCouponUrl(),
            'googleAdsList'   => $googleAdsList,
            'recommendMerchantList' => $recommendMerchantList,
            'hotCouponList'   => $hotCouponList,
            'topCategoryAll'  => PathManager::getDhbCouponUrl(),
        );
        return new ViewModel($res);
    }
}
?>