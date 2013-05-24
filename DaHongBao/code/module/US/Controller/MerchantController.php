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
* @Version  : CVS: $Id: MerchantController.php,v 1.3 2013/04/26 04:09:43 rizhang Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Merchant\Merchant;
use CommModel\Article\Article;
use CommModel\Ads\Google;
use CommModel\Coupon\Coupon;
use CommModel\Category\Category;
use Custom\Util\Utilities;
use Custom\Util\TrackingFE;
use Custom\Util\PathManager;
use Zend\Paginator\Paginator;

class MerchantController extends UsController 
{
	/*
	 * 所有商家页
	 */
    public function indexAction()
    {
        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $merchantTable = $this->_getTable('Merchant');

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //特价促销
        $specialDealsList = $couponTable->getSpecialDealsList();

        //所有商家列表
        $merchantList = $this->getMerchantList();

        $res = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'specialDealsList' => $specialDealsList,
            'specialDealsMore' => PathManager::getDealsListUrl(),
            'googleAdsList'    => Google::getGoogleAds(),
            'merchantList'  => $merchantList,
            'hotCouponList' => $hotCouponList,
            'hotCouponMore' => PathManager::getAllCateUrl(),
        );
        return new ViewModel($res);
    }

    /*
     * 商家按字母分类 [A-z]
     */
    public function keyAction()
    {
        preg_match('/^(\/all-merchant-[~A-Z])\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("商家开头错误");
        }
        $type = strtoupper(str_replace("/all-merchant-", "", $matches[1]));

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $merchantTable = $this->_getTable('Merchant');

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //特价促销
        $specialDealsList = $couponTable->getSpecialDealsList(self::SITEID);

        //所有商家列表
        $merchantList = $this->getMerchantList($type);

        $res = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'specialDealsList' => $specialDealsList,
            'specialDealsMore' => PathManager::getDealsListUrl(),
            'googleAdsList'    => Google::getGoogleAds(),
            'merchantList'     => $merchantList,
            'hotCouponList'    => $hotCouponList,
            'hotCouponMore'    => PathManager::getAllCateUrl(),
            'type' => $type,
            'allMerchantUrl'  => PathManager::getAllMerchantUrl(),
        );
        return new ViewModel($res);
    }

    /*
     * 商家 详细页面
     */
    public function detailAction()
    {
        //获取merid
        preg_match('/^(\/merchant-[0-9]+)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("商家id错误");
        }

        $merid = str_replace("/merchant-", "", $matches[1]);
        if (isset($matches[2])) {
            $page = str_replace(array('/', 'page-'), "", $matches[2]);
        }

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantTable = $this->_getTable('Merchant');

        $merchantInfo = $merchantTable->getMerchantInfoByID($merid, self::SITEID);
        if (empty($merchantInfo)) {
            throw new \Exception("商家id：{$merid}不存在!");
        }
        $merchantInfo['DescriptionCN']      = strip_tags($merchantInfo['DescriptionCN']);
        $merchantInfo['DescriptionCNShort'] = Utilities::cutString($merchantInfo['DescriptionCN'], 100);
        $merchantPaymentTable = $this->_getTable('MerchantPayment');
        $merchantInfo['Payment'] = $merchantPaymentTable->getMerchantPayment($merid, false);
        $merchantOfferUrl = TrackingFE::registerMerchantLink($merchantInfo);
        $merchantInfo['MerchantOfferUrl'] = $merchantOfferUrl;

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //热门优惠券
        $hotCouponList = $couponTable->getHotCouponList();
        
        //热门促销
        $hotDealsList = $couponTable->getHotDealsList();

        //商家优惠券, 分页
        $adapter = $couponTable->getCouponList(self::SITEID, $merid);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);

        $uid = (int)$_COOKIE['UID'];
        $couponList = $paginator->getCurrentItems()->toArray();
        foreach ($couponList as $key => $coupon) {
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
        //echo '<pre>';print_r($couponList);exit;

        $res = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'hotCouponList'  => $hotCouponList,
            'hotCouponMore'  => PathManager::getAllCateUrl(),
            'hotDealsList'   => $hotDealsList,
            'hotDealsMore'   => PathManager::getDealsListUrl(),
            'googleAdsList'  => Google::getGoogleAds(),
            'merchantInfo'   => $merchantInfo,
            'page'           => $page,
            'paginator'      => $paginator,
            'couponList'     => $couponList,
            'showMerchantCouponInfo' =>  false, //左侧不显示商家详细信息
        );
        return new ViewModel($res);
    }

    /*
     * 获取商家基本信息
     */
    public function getMerchantList($key = null)
    {
        $orderMerchant = array();
        $merchantTable = $this->_getTable('Merchant');
        $merchantList = $merchantTable->getMerchantList(self::SITEID, 'Sequence DESC');
        foreach($merchantList as $merchant) {
            $merchant['MerchantUrl'] = PathManager::getMerchantDetailUrl($merchant['MerchantID']);
            $type = Utilities::getinitial($merchant['MerchantName']);
            if (empty($orderMerchant[$type]['Url'])) {
                $orderMerchant[$type]['Url'] = PathManager::getMerchantKeyUrl($type);
            }
            $orderMerchant[$type]['Date'][] = $merchant;
        };
        ksort($orderMerchant);
        if (empty($key)) {
            //$last['~'] = array_pop($orderMerchant);//把~放到最上面
            //$orderMerchant = array_merge($last, $orderMerchant);
            return $orderMerchant;
        }
        return $orderMerchant[$key];
    }
}
?>