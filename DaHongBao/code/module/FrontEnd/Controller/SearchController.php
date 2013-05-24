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
* @Version  : CVS: $Id: SearchController.php,v 1.12 2013/04/26 02:18:18 yjiang Exp $
*/
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use Custom\Util\Utilities;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;
use Custom\Util\WordSplitter;
use Zend\Paginator\Paginator;

class SearchController extends FrontEndController 
{
    const PAGE = 30;//这里分页25条

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
            $url = PathManager::getDhbSearchCouponUrl($q);
        } elseif ($type == 'deals') {
            $url = PathManager::getDhbSearchDealsUrl($q);
        }
        TrackingFE::execStatHeader($url, '301');
        exit;
    }

    /*
     * Search coupon page
     */
    public function couponAction()
    {
        preg_match('/^(\/s-quan-(.*)+)\//', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches)) {
            throw new \Exception("search  error");
        }
        $str = str_replace(array('/s-quan-', 'page-'), '', $matches[1]);
        $str = explode('/', $str);
        $keyword = Utilities::decode($str[0]);

        // 中文分词
        $destNameArr = WordSplitter::instance()->execute($keyword);
        $match = explode(" ", implode(" ",$destNameArr));
        foreach($match as $index => $m) {
            $condition .= "({$m}) ";
        }

        $urlKeyword = $str[0];
        $page = $str[1];

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $categoryTable = $this->_getTable('Category');
        $merchantTable = $this->_getTable('Merchant');
        $couponSearchTable = $this->_getTable('CouponSearch');

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
            $url = PathManager::getDhbCouponCateUrl($seCategoryID);
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
            $url = PathManager::getDhbMerchantDetailUrl($seMerchantID);
            TrackingFE::execStatHeader($url, '301');
            exit;
        }

        $this->layout()->currentPage = PathManager::getDhbCouponUrl(); //导航选中
        $this->layout()->keyword  = $keyword;
        $this->layout()->searchType = 'coupon';

        // 搜索开始
        $requestApiStartTime = Utilities::getMicrotime();

        // 搜索结果
        $adapter = $couponSearchTable->getSearchList(self::SITEID, 'COUPON', $condition);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $couponList = $paginator->getCurrentItems()->toArray();
        if (empty($couponList)) {
            $this->response->setStatusCode(404);
            TrackingFE::execNotFoundHeader('404');
            //throw new \Exception('抱歉！您输入的‘'.$keyword.'’暂无匹配到相关信息，请更新关键词搜索，如：一号店优惠券。');
        } else {
	        foreach($couponList as $key => $coupon) {
	            $couponList[$key]['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($coupon['CouponID']);
	            $couponList[$key]['MerchantInfo'] = $merchantTable->getMerchantInfoByID($coupon['MerchantID'], self::SITEID);
	            $couponList[$key]['MerchantInfo']['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($coupon['MerchantID']);
	            TrackingFE::registerDHBOfferLink($coupon, true, false);
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

        //右侧：今日精选促销
        $todayDealsList = $couponTable->getDhbTodayDealsList(self::SITEID);
        
        //右侧：最新优惠券发布
        $newCouponList = $couponTable->getDhbNewCouponList(self::SITEID);

        $this->layout()->keyword  = $keyword;
        $this->layout()->searchType = 'coupon';

        $assign = array(
            'keyword'        => htmlspecialchars($keyword),
            'urlKeyword'     => $urlKeyword,
            'couponUrl'      => PathManager::getDhbCouponUrl(),
            'page'           => $page,
            'paginator'      => $paginator,
            'couponList'     => $couponList,
            'todayDealsList' => $todayDealsList,
            'newCouponList'  => $newCouponList,
            'searchErrorTpl' => 'front-end/search/coupon',
        );
        return new ViewModel($assign);
    }

    /*
     * Search deals page
     */
    public function dealsAction()
    {
        preg_match('/^(\/s-cuxiao-(.*)+)\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches)) {
            throw new \Exception("search  error");
        }

        $str = str_replace(array('/s-cuxiao-', 'page-', 'sortby-'), "", $matches[1]);
        $str = explode('/', $str);
        $keyword = Utilities::decode($str[0]);

        // 中文分词
        $destNameArr = WordSplitter::instance()->execute($keyword);
        $match = explode(" ", implode(" ",$destNameArr));
        foreach($match as $index => $m) {
            $condition .= "({$m}) ";
        }

        $urlKeyword = $str[0];
        $str = $str[1];
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

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        $this->layout()->currentPage = PathManager::getDhbDealsUrl(); //导航选中
        $this->layout()->keyword  = $keyword;
        $this->layout()->searchType = 'deals';

        // 搜索开始
        $requestApiStartTime = Utilities::getMicrotime();

        //搜索结果
        $couponTable       = $this->_getTable('Coupon');
        $merchantTable     = $this->_getTable('Merchant');
        $couponSearchTable = $this->_getTable('CouponSearch');
        $adapter = $couponSearchTable->getSearchList(self::SITEID, 'DISCOUNT', $condition, $sortby);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $dealsList = $paginator->getCurrentItems()->toArray();
        if (empty($dealsList)) {
            $this->response->setStatusCode(404);
            TrackingFE::execNotFoundHeader('404');
            //throw new \Exception('抱歉！您输入的‘'.$keyword.'’暂无匹配到相关信息，请更新关键词搜索，如：一号店优惠券。');
        } else {
	        foreach($dealsList as $key => $deals) {
	            $dealsList[$key]['DealsdetailUrl'] = PathManager::getDhbDealsDetailUrl($deals['CouponID']);
	            $dealsList[$key]['OfferUrl'] = TrackingFE::registerDHBOfferLink($deals, true, true);
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

        //右侧热券推荐
        $hotCouponList = $couponTable->getDhbHotCouponList(self::SITEID, 20);

        $this->layout()->keyword  = $keyword;
        $this->layout()->searchType = 'deals';

        $assign = array(
            'keyword'       => htmlspecialchars($keyword),
            'urlKeyword'    => $urlKeyword,
            'dealsUrl'      => PathManager::getDhbDealsUrl(),
            'sortbyNew'     => PathManager::getDhbSearchDealsUrl(Utilities::encode($keyword), null, 'new'),
            'sortbyHot'     => PathManager::getDhbSearchDealsUrl(Utilities::encode($keyword), null, 'hot'),
            'page'          => $page,
            'sortby'        => $sortby,
            'paginator'     => $paginator,
            'dealsList'     => $dealsList,
            'hotCouponList' => $hotCouponList,
            'searchErrorTpl' => 'front-end/search/deals',
        );
        return new ViewModel($assign);
    }

    function str2hex($str) {
        $str = (string)$str;
        $result = "";
        for($i=0,$len=mb_strlen($str);$i<$len;$i++) {
            $result .= bin2hex( mb_substr($str, $i, 1) ) . "S";
        }
        return trim($result, "S");
    }

    function my_strtolower($str) {
        return mb_strtolower($str);

        $len = strlen($str);
        $A = ord('A');
        $Z = ord('Z');
        for($i = 0; $i < $len; $i++) {
            if(ord($str[$i]) >= $A && ord($str[$i]) <= $Z) {
                $str[$i] = chr(ord($str[$i]) + 32);
            }
        }
        return $str;
    }
}
?>