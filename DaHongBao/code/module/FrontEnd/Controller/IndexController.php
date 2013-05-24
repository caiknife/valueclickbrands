<?php
/*
 * package_name : IndexController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: IndexController.php,v 1.4 2013/04/18 12:16:52 rizhang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use CommModel\Merchant\Merchant;
use Custom\Util\PathManager;


class IndexController extends FrontEndController 
{
    public function indexAction()
    {
        $this->layout()->currentPage = '/'; //导航选中

        $couponTable   = $this->_getTable('Coupon');

        //热门商家
        $hotMerchantList = $couponTable->getIndexHotMerchantList();
        foreach ($hotMerchantList as $key => $hotMerchant) {
            $hotMerchantList[$key]['CouponCount'] = $couponTable->getMerchantCouponCount($hotMerchant['MerchantID']);
        }

        //大广告区域
        $rightAdsList = $couponTable->getIndexBigBanner();

        //最新优惠券，5条
        $newCouponList = $couponTable->getDhbIndexNewCouponList();

        //精选商家优惠券
        $hotMerchantCouponlist = $couponTable->getDhbIndexMerchantCouponList();

        //精选促销
        $hotDealsList = $couponTable->getDhbIndexDealsList();
        
        //左侧banner 250*250
        $leftBanner = $couponTable->getIndexLeftBanner();

        $res = array(
            'hotMerchantList' => $hotMerchantList,
            'rightAdsList'    => $rightAdsList,
            'newCouponList'   => $newCouponList,
            'newCouponMore'   => PathManager::getDhbCouponUrl(),
            'hotMerchantCouponlist' => $hotMerchantCouponlist,
            'hotDealsList'          => $hotDealsList,
            'leftBanner'            => $leftBanner,
        );
        //echo '<pre>';print_r($hotMerchantList);exit;
        return new ViewModel($res);
    }
}
?>