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
* @Version  : CVS: $Id: CategoryController.php,v 1.5 2013/04/22 04:02:13 rizhang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use Custom\Util\PathManager;
use CommModel\Category\Category;
use Zend\Paginator\Paginator;
use Custom\Util\TrackingFE;

class CategoryController extends FrontEndController 
{
    const PAGE = 20;//这里分页20条

    public function indexAction()
    {
        return true;
    }

    public function categoryAction()
    {
        preg_match('/^(\/category-[0-9]+)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("catid is error");
        }
        $catid = str_replace("/category-", "", $matches[1]);
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

        //category存不存在
        $categoryTable  = $this->_getTable('Category');
        $categoryName = $categoryTable->getCategoryNameByID($catid);
        if (empty($categoryName)) {
            throw new \Exception('catid {$catid} not exist');
        }

        $this->layout()->currentPage = PathManager::getDhbCouponUrl(); //导航选中

        //top 9个category
        $topCategoryList = $categoryTable->getDhbTopCategoryList(self::SITEID);

        //热门商家48个，先显示16个
        $couponTable   = $this->_getTable('Coupon');
        $merchantTable   = $this->_getTable('Merchant');
        $hotMerchantList = $merchantTable->getHotMerchantList(self::SITEID, 48, $catid);
        foreach ($hotMerchantList as $key => $hotMerchant) {
            $hotMerchantList[$key]['CouponCount'] = $hotMerchant['r_OnlineCouponCount'];
        }
        $hotMerchantList = array_values($hotMerchantList);

        //获取优惠券列表
        $couponCategoryTable = $this->_getTable('CouponCategory');
        $adapter = $couponCategoryTable->getCategoryCouponList(self::SITEID, $catid, null, $sortby);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $couponList = $paginator->getCurrentItems()->toArray();

        foreach($couponList as $key => $coupon) {
            $couponList[$key]['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($coupon['CouponID']);
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
            'sortbyNew'       => PathManager::getDhbCouponCateUrl($catid, null, 'new'),
            'sortbyHot'       => PathManager::getDhbCouponCateUrl($catid, null, 'hot'),
            'page'            => $page,
            'sortby'          => $sortby,
            'couponList'      => $couponList,
            'paginator'       => $paginator,
            'cid'             => $catid,
            'couponUrl'       => PathManager::getDhbCouponUrl(),
            'categoryName'    => $categoryTable->getCategoryNameByID($catid),
        );
        return new ViewModel($res);
    }
}
?>