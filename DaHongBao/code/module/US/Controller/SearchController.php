<?php
/*
* package_name : SearchController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: SearchController.php,v 1.10 2013/04/27 07:33:14 yjiang Exp $
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

class SearchController extends UsController 
{
    const PAGE = 25;//这里分页25条

    public function indexAction()
    {
        $q = $this->params()->fromQuery('q');
        $q = trim($q);
        $type = $this->params()->fromQuery('searchType');
        if (empty($q)) {
            throw new \Exception("keyword is empty");
        }
        if($q && $type){
            $logSearch = array (
                'channelid'        => 0,
                'categoryid'       => 0,
                'costtime'         => 0,
                'desturl'          => '',
                'isrealsearch'     => 'USER',
                'iscached'         => 'NO',
                'keyword'          => $q,
                'matchkeyword'     => '',
                'productid'        => 0,
                'responsetime'     => 0,
                'resultcount'      => 0,
                'resultsize'       => 0,
                'resulttype'       => 0,
                'searchenginetype' => \Tracking_Constant::SERVICE_SMARTER,
                'source'           => \Tracking_Session::getInstance()->getSource(),
                'totalcosttime'    => 0,
            );
            \Tracking_Logger::getInstance()->search($logSearch);
        }
        $q = Utilities::encode($q);
        if ($type == 'coupon') {
            $url = PathManager::getSearchCouponUrl($q);
        } elseif ($type == 'deals') {
            $url = PathManager::getSearchDealsUrl($q);
        }
        TrackingFE::execStatHeader($url, '301');
        exit;
    }

    /*
     * Search coupon page
     */
    public function couponAction()
    {
        preg_match('/^(\/s-coupon-(.*)+)\//', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches)) {
            throw new \Exception("search  error");
        }
        $str = str_replace(array('/s-coupon-', 'page-'), '', $matches[1]);
        $str = explode('/', $str);
        $keyword = Utilities::decode($str[0]);
        $urlKeyword = $str[0];
        $page = $str[1];

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantTable = $this->_getTable('Merchant');
        $couponSearchTable = $this->_getTable('CouponSearch');
        $merchantPaymentTable = $this->_getTable('MerchantPayment');

        // 是不是分类
        $requestApiStartTime = Utilities::getMicrotime();
        $seCategoryID = $categoryTable->isCategoryName(self::SITEID, $keyword);
        if ($seCategoryID) {
            // Tracking SearchLog
            $costtime = Utilities::getMicrotime() - $requestApiStartTime;
            TrackingFE :: addSearchLog(array(
                "keyword"        => $keyword,
                "totalcosttime"  => $costtime,
                "resultcount"    => 0,
                "costtime"       => $costtime,
                "resulttype"     => 21,
                "iscached"       => false,
                "resultsize"     => 1,
                "ResponseTime"   => 0,
            ));
            $url = PathManager::getCateListUrl($seCategoryID);
            TrackingFE::execStatHeader($url, '301');
            exit;
        }
        // 是不是商家
        $requestApiStartTime = Utilities::getMicrotime();
        $seMerchantID = $merchantTable->isMerchantName(self::SITEID, $keyword);
        if ($seMerchantID) {
            // Tracking SearchLog
            $costtime = Utilities::getMicrotime() - $requestApiStartTime;
            TrackingFE :: addSearchLog(array(
                "keyword"        => $keyword,
                "totalcosttime"  => $costtime,
                "resultcount"    => 0,
                "costtime"       => $costtime,
                "resulttype"     => 22,
                "iscached"       => false,
                "resultsize"     => 1,
                "ResponseTime"   => 0,
            ));
            $url = PathManager::getMerchantDetailUrl($seMerchantID);
            TrackingFE::execStatHeader($url, '301');
            exit;
        }

        $this->layout()->keyword  = $keyword;
        $this->layout()->searchType = 'coupon';

        // 搜索开始
        $requestApiStartTime = Utilities::getMicrotime();

        // 搜索结果
        $adapter = $couponSearchTable->getSearchList(self::SITEID, 'COUPON', addslashes($keyword));
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $couponList = $paginator->getCurrentItems()->toArray();
        if (empty($couponList)) {
            //throw new \Exception('抱歉！您输入的‘'.$keyword.'’暂无匹配到相关信息，请更新关键词搜索，如：一号店优惠券。');
            $this->response->setStatusCode(404);
            TrackingFE::execNotFoundHeader('404');
        } else {
            $uid = (int)$_COOKIE['UID'];
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
	            $couponList[$key]['MerchantInfo']['DescriptionCN'] = Utilities::cutString(strip_tags($couponList[$key]['MerchantInfo']['DescriptionCN']), 200);
	        }
        }

        // Tracking SearchLog
        $costtime = Utilities::getMicrotime() - $requestApiStartTime;
        TrackingFE :: addSearchLog(array(
            "keyword"        => $keyword,
            "totalcosttime"  => $costtime,
            "resultcount"    => $paginator->getTotalItemCount(),
            "costtime"       => $costtime,
            "resulttype"     => $couponList > 0 ? 0 : 4,
            "iscached"       => false,
            "resultsize"     => 1,
            "ResponseTime"   => 0,
        ));

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        $assign = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'googleAdsList'  => Google::getGoogleAds(),
            'keyword'        => htmlspecialchars($keyword),
            'urlKeyword'     => $urlKeyword,
            'page'           => $page,
            'paginator'      => $paginator,
            'couponList'     => $couponList,
            'searchErrorTpl' => 'us/search/coupon',
        );
        return new ViewModel($assign);
    }

    /*
     * Search deals page
     */
    public function dealsAction()
    {
        preg_match('/^(\/s-deals-(.*)+)\//', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches)) {
            throw new \Exception("search  error");
        }
        $str = str_replace(array('/s-deals-', 'page-'), '', $matches[1]);
        $str = explode('/', $str);
        $keyword = Utilities::decode($str[0]);
        $urlKeyword = $str[0];
        $page = $str[1];

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantTable = $this->_getTable('Merchant');
        $couponSearchTable = $this->_getTable('CouponSearch');

        $this->layout()->keyword  = $keyword;
        $this->layout()->searchType = 'deals';

        // 搜索开始
        $requestApiStartTime = Utilities::getMicrotime();

        //搜索结果
        $adapter = $couponSearchTable->getSearchList(self::SITEID, 'DISCOUNT', addslashes($keyword));
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $dealsList = $paginator->getCurrentItems()->toArray();
        if (empty($dealsList)) {
            //throw new \Exception('抱歉！您输入的‘'.$keyword.'’暂无匹配到相关信息，请更新关键词搜索，如：一号店优惠券。');
            $this->response->setStatusCode(404);
            TrackingFE::execNotFoundHeader('404');
        } else {
	        foreach($dealsList as $key => $deals) {
	            $offerUrl = TrackingFE::registerOfferLink($deals);
	            $dealsList[$key]['OfferUrl'] = $offerUrl;
	            $dealsList[$key]['CouponName'] = Utilities::cutString(strip_tags($dealsList[$key]['CouponDescription']), 200);
	        }
        }

        // Tracking SearchLog
        $costtime = Utilities::getMicrotime() - $requestApiStartTime;
        TrackingFE :: addSearchLog(array(
            "keyword"        => $keyword,
            "totalcosttime"  => $costtime,
            "resultcount"    => $paginator->getTotalItemCount(),
            "costtime"       => $costtime,
            "resulttype"     => $dealsList > 0 ? 0 : 4,
            "iscached"       => false,
            "resultsize"     => 1,
            "ResponseTime"   => 0,
        ));

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        $assign = array(
            'categoryList'   => $categoryList,
            'siteHelpList'   => $siteHelpList,
            'helpCenterList' => $helpCenterList,
            'googleAdsList'  => Google::getGoogleAds(),
            'keyword'        => htmlspecialchars($keyword),
            'urlKeyword'     => $urlKeyword,
            'page'           => $page,
            'paginator'      => $paginator,
            'dealsList'      => $dealsList,
            'searchErrorTpl' => 'us/search/deals',
        );
        return new ViewModel($assign);
    }
}
?>