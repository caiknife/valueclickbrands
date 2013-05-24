<?php
/*
* package_name : file_name
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Coupon.php,v 1.15 2013/05/09 01:42:19 rizhang Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;
use Custom\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Custom\Util\Utilities;

class Coupon extends TableGateway
{
    protected $table = "Coupon";

    // ----------------- 大红包海淘  内容板块 ----------------- //
    /*
     * 特价促销，Tracking跳出， 获取特价促销5条数据
     * 系统自动推荐5条最新Deals，按时间倒序，最新的在最上面
     */
    public function getSpecialDealsList($siteid = 2, $limit = 5) {
        $where = array(
            'Coupon.SiteID'     => $siteid,
            'Coupon.IsActive'   => 'YES',
            'Coupon.CouponType' => 'DISCOUNT'
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->join("Merchant", "Coupon.MerchantID = Merchant.MerchantID", array('MerchantName'), "inner");
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        $select->limit($limit);
        $select->group("Merchant.MerchantID");
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $specialDealsList = $resultSet->toArray();
        if ($specialDealsList) {
            foreach ($specialDealsList as $key => $specialDeals) {
                $offerUrl = TrackingFE::registerOfferLink($specialDeals);
	            $specialDealsList[$key]['OfferUrl'] = $offerUrl;
	            $specialDealsList[$key]['CouponName'] = "【".$specialDeals['MerchantName']."】".$specialDeals['CouponName'];
            }
        }
        return $specialDealsList;
    }

    /*
     * 热门优惠券，Tracking跳出， 默认投放5条优惠券信息
     * 按该优惠券浏览量倒序， 按入库时间倒序， 按到期时间顺序，离到期时间越近越靠前
     */
    public function getHotCouponList($siteid = 2, $limit = 5) {
        $where = array(
            'Coupon.SiteID'     => $siteid,
            'Coupon.IsActive'   => 'YES',
            'Coupon.CouponType' => 'COUPON',
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array('ViewCnt'), 'inner');
        $select->join("Merchant", "Coupon.MerchantID = Merchant.MerchantID", array('MerchantName'), "inner");
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->order("Coupon.IsActive DESC, CouponExtra.ViewCnt DESC, Coupon.CouponEndDate ASC");
        $select->limit($limit);
        $resultSet = $this->selectWith($select);
        $hotCouponList = $resultSet->toArray();
        if ($hotCouponList) {
            foreach ($hotCouponList as $key => $hotCoupon) {
                $offerUrl = TrackingFE::registerOfferLink($hotCoupon);
                $hotCouponList[$key]['OfferUrl'] = $offerUrl;
                $hotCouponList[$key]['CouponName'] = "【".$hotCoupon['MerchantName']."】".$hotCoupon['CouponName'];
            }
        }
        return $hotCouponList;
    }

    /*
     * 热门促销，Tracking跳出， 默认投放5条优惠券信息
     * 按该优惠券浏览量倒序， 按入库时间倒序， 按到期时间顺序，离到期时间越近越靠前
     */
    public function getHotDealsList($siteid = 2, $limit = 5) {
        $where = array(
            'Coupon.SiteID'     => $siteid,
            'Coupon.IsActive'   => 'YES',
            'Coupon.CouponType' => 'DISCOUNT',
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array('ViewCnt'), 'inner');
        $select->join("Merchant", "Coupon.MerchantID = Merchant.MerchantID", array('MerchantName'), "inner");
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->order("Coupon.IsActive DESC, CouponExtra.ViewCnt DESC, Coupon.CouponEndDate ASC");
        $select->limit($limit);
        $resultSet = $this->selectWith($select);
        $hotDealsList = $resultSet->toArray();
        if ($hotDealsList) {
            foreach ($hotDealsList as $key => $hotDeals) {
                $offerUrl = TrackingFE::registerOfferLink($hotDeals);
                $hotDealsList[$key]['OfferUrl'] = $offerUrl;
                $hotDealsList[$key]['CouponName'] = "【".$hotDeals['MerchantName']."】".$hotDeals['CouponName'];
            }
        }
        return $hotDealsList;
    }

    /*
     * 获取优惠券列表，有/无分页， hot => 领取数，默认时间倒序，大红包国内 海外公用
     */
    public function getCouponList($siteid, $merid = NULL, $limit = null, $sortby = null, $excludeCouponID = null) {
        $where = array(
            'Coupon.SiteID' => $siteid,
            'Coupon.CouponType' => 'COUPON',
            //'Coupon.IsActive'   => 'YES',
        );
        if ($siteid == 2) {
            // bugzilla 315748
            $where['Coupon.IsActive'] = 'YES';
        }
        if ($merid) {
            $where['Coupon.MerchantID'] = $merid;
        }
        $select = $this->getSql()->select();
        if ($sortby) {
            //大红包，不显示CouponCode
            $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array(), 'inner');
        } else {
            //海外直接显示CuponCode，只有一个CouponCode
            $select->join('CouponCode', 'Coupon.CouponID = CouponCode.CouponID', 'CouponCode', 'left');
        }
        $select->join("Merchant", "Coupon.MerchantID = Merchant.MerchantID", array('MerchantName', 'MerchantUrl', 'AffiliateUrl'), "inner");
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->where($where);
        $columns = array(
            'CouponID', 'MerchantID', 'AffiliateID', 'CouponName', 'CouponDescription', 'CouponUrl', 
            'CouponImageUrl', 'CouponStartDate', 'CouponEndDate', 'CouponAmount', 'CouponReduction', 
            'CouponDiscount', 'IsFree', 'InsertDateTime', 'IsAffiliateUrl', 'CouponType'
        );
        $select->columns($columns);
        if ($excludeCouponID && is_numeric($excludeCouponID)) {
            $select->where("Coupon.CouponID != {$excludeCouponID}");
        }
        if ($sortby == 'hot') {
            $select->order("Coupon.IsActive DESC, CouponExtra.ReceiveCnt DESC, Coupon.CouponEndDate ASC");
        } else {
            $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        }
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        if ($limit) {
            //限制个数，无分页
            $select->limit($limit);
            $resultSet = $this->selectWith($select);
            return $resultSet->toArray();
        }
        return new DbSelect($select, $this->getAdapter());
    }

    /*
     * Coupon的详细信息
     */
    public function getCouponInfoByID($couponid, $siteid, $type = NULL, $getMerchant = false, $getCouponExt = false, $getCouponCategory = false) {
        $couponid = (int)$couponid;
        $where = array(
            'Coupon.CouponID' => $couponid,
            'Coupon.SiteID'   => $siteid,
            //'Coupon.IsActive' => 'YES',
        );
        if ($type) {
            $where['CouponType'] = $type;
        }
        $select = $this->getSql()->select();
        $select->where($where);
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantName', 'LogoFile', 'DescriptionCN', 'MerchantUrl', 'AffiliateUrl'), 'inner');
        if ($getCouponExt) {
            $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array('UsePoints', 'TotalCnt', 'ReceiveCnt', 'LeaveCnt', 'ViewCnt'), 'inner');
        }
        if ($getCouponCategory) {
            $select->join('CouponCategory', 'Coupon.CouponID = CouponCategory.CouponID', array('CategoryID'), 'inner');
        }
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $resultSet = $this->selectWith($select);
        return current($resultSet->toArray());
    }

    /*
     * 获取商家的Coupon总数
     */
    public function getMerchantCouponCount($merid, $catid = null, $siteid = 1) {
        $where = array(
            'Coupon.CouponType' => 'COUPON',
            //'Coupon.IsActive'   => 'YES',
            'Coupon.MerchantID' => $merid,
            'Coupon.SiteID'     => $siteid,
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->columns(array('CouponID'));
        $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array(), 'inner');
        if ($catid) {
            $select->join('CouponCategory', 'Coupon.CouponID = CouponCategory.CouponID', array(), 'left');
            $select->where("CouponCategory.CategoryID = {$catid}");
        }
        //echo str_replace("\"", "", $select->getSqlString());
        $resultSet = $this->selectWith($select);
        return count($resultSet->toArray());
    }

    /*
     * 获取促销列表，有分页， hot => 展示数，默认时间倒序
     */
    public function getDealsList($siteid, $sortby = null) {
        $where = array(
            'Coupon.SiteID' => $siteid,
            'Coupon.CouponType' => 'DISCOUNT',
            //'Coupon.IsActive'   => 'YES',
        );
        if ($siteid == 2) {
            // bugzilla 315748
            $where['Coupon.IsActive'] = 'YES';
        }
        $select = $this->getSql()->select();
        $select->where($where);
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantName', 'MerchantUrl', 'AffiliateUrl'), 'inner');
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        if ($sortby == 'hot') {
            $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array('ViewCnt'), 'inner');
            $select->order("Coupon.IsActive DESC, CouponExtra.ReceiveCnt DESC, Coupon.CouponEndDate ASC");
        } else {
            $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        }
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        return new DbSelect($select, $this->getAdapter());
    }

//------------- 大红包首页 特殊逻辑  -------------//

    /*
     * 获取首页热门商家
     */
    public function getIndexHotMerchantList($limit = 12) {
    	$hotMerchant = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeHotMerchant.php");
    	return array_slice($hotMerchant, 0, $limit);
    }

    /*
     * 大红包 右侧大广告区域
     */
    public function getIndexBigBanner($siteid = 1, $limit = 5) {
        $homeBanner = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeBanner.php");
        foreach ($homeBanner as $key => $banner) {
            $banner['dispos'] = $key + 1;
            $homeBanner[$key]['Url'] = TrackingFE::registerDHBBannerLink($banner, 4);
        }
        return array_slice($homeBanner, 0, $limit);
    }

    /*
     * 大红包 获取最新优惠券
     * 5张最新优惠券， 默认排列逻辑：按时间倒序排列最新的优惠券，每个商家不大于2张(小于等于2);
     */
    public function getDhbIndexNewCouponList($siteid = 1, $limit = 5) {
        $newCoupon = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeNewCoupon.php");
        return array_slice($newCoupon, 0, $limit);
    }

    /*
     * 大红包    精选商家优惠券
     * 20张最新优惠券，按领取数量最多的优惠券倒序排列，每个商家不大于2张(小于等于2)
     * 人工推荐优惠券做置顶设置
     */
    public function getDhbIndexMerchantCouponList($siteid = 1, $limit = 20) {
        $merchantCoupon = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeMerchantCoupon.php");
        return array_slice($merchantCoupon, 0, $limit);
    }

    /*
     * 大红包    精选促销优惠券
     * 左侧：显示8条促销信息（商家名、信息标题、有效期）； 默认排列逻辑：按时间倒序排，一个商家仅允许出现3次以下； 人工干预逻辑：人工推荐促销信息做置顶设置；
     */
    public function getDhbIndexDealsList($siteid = 1, $leftLimit = 8, $rightLimit = 6) {
    	$dealsLeft  = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeHotDeals_Left.php");
    	$dealsRight = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeHotDeals_Right.php");
        $hotDealsList['left']  = array_slice($dealsLeft,  0, $leftLimit);
        $hotDealsList['right'] = array_slice($dealsRight, 0, $rightLimit);
        return $hotDealsList;
    }

    /*
     * 大红包 左下角广告区域
     */
    public function getIndexLeftBanner($siteid = 1, $limit = 1) {
        $homeleftBanner = Utilities::getPhpArrayCache(RECOMMEND_PATH."homeLeftBanner.php");
        foreach ($homeleftBanner as $key => $banner) {
            $banner['dispos'] = $key + 1;
            $homeleftBanner[$key]['Url'] = TrackingFE::registerDHBBannerLink($banner, 5);
        }
        return array_slice($homeleftBanner, 0, $limit);
    }

    /*
     * 大红包右侧：热券推荐，最新优惠券排行区域（couponDtail页面的称呼）
     */
    public function getDhbHotCouponList($siteid, $limit = 10, $exculdeID = null) {
        //列举10条领取或使用量最高的优惠券，不限商家，时间倒序，优惠券详细页排除当前优惠券ID
        $where = array(
            'Coupon.SiteID'   => $siteid,
            //'Coupon.IsActive' => 'YES',
            'CouponType' => 'COUPON',
        );
        $select = $this->getSql()->select();
        $select->columns(array('CouponID', 'CouponName', 'CouponUrl', 'IsAffiliateUrl', 'CouponType'));
        $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array(), 'inner');
        $select->where($where);
        if ($exculdeID) {
            $select->where('Coupon.CouponID != '.$exculdeID);
        }
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantID', 'MerchantName', 'MerchantUrl', 'AffiliateUrl'), 'inner');
        $select->order("Coupon.IsActive DESC, CouponExtra.ReceiveCnt DESC, CouponExtra.LeaveCnt DESC, Coupon.CouponEndDate ASC");
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->limit($limit);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $hotCouponList = $resultSet->toArray();
        if (empty($hotCouponList)) {
            return array();
        }
        foreach ($hotCouponList as $key => $hotCoupon) {
            $hotCouponList[$key]['CouponDetailUrl']   = PathManager::getDhbCouponDetailUrl($hotCoupon['CouponID']);
            TrackingFE::registerDHBOfferLink($hotCoupon, true, false);
        }
        return $hotCouponList;
    }

    /*
     * 大红包右侧：最新发布优惠券，相关优惠券推荐（有cateid）
     * 按时间倒序显示最新10条促销信息，不限商家
     */
    public function getDhbNewCouponList($siteid, $limit = 10, $exculdeID = null, $cateid = null) {
        $where = array(
            'Coupon.SiteID'     => $siteid,
            //'Coupon.IsActive'   => 'YES',
            'Coupon.CouponType' => 'COUPON',
        );
        $select = $this->getSql()->select();
        $select->columns(array('CouponID', 'CouponName', 'CouponUrl', 'IsAffiliateUrl', 'CouponType'));
        $select->where($where);
        if ($exculdeID) {
            $select->where('Coupon.CouponID != '.$exculdeID);
        }
        if ($cateid) {
            $select->join('CouponCategory', 'Coupon.CouponID = CouponCategory.CouponID', array(), 'inner');
            $select->where('CouponCategory.CategoryID = '.$cateid);
        }
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantID','MerchantName', 'MerchantUrl', 'AffiliateUrl'), 'inner');
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        $select->limit($limit);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $newCouponList = $resultSet->toArray();
        if (empty($newCouponList)) {
            return array();
        }
        foreach ($newCouponList as $key => $newCoupon) {
            $newCouponList[$key]['CouponDetailUrl']   = PathManager::getDhbCouponDetailUrl($newCoupon['CouponID']);
            TrackingFE::registerDHBOfferLink($newCoupon, true, false);
        }
        return $newCouponList;
    }

    /*
     * 大红包右侧：最新精选折扣
     * 按时间倒序显示最新10条促销信息
     */
    public function getDhbNewDealsList($siteid, $limit = 10) {
        $where = array(
            'Coupon.SiteID'     => $siteid,
            //'IsActive'   => 'YES',
            'CouponType' => 'DISCOUNT',
        );
        $select = $this->getSql()->select();
        $select->columns(array('CouponID', 'CouponName', 'CouponUrl', 'IsAffiliateUrl', 'CouponType'));
        $select->where($where);
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantID','MerchantName', 'MerchantUrl', 'AffiliateUrl'), 'inner');
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        $select->limit($limit);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $newDealsList = $resultSet->toArray();
        if (empty($newDealsList)) {
            return array();
        }
        foreach ($newDealsList as $key => $newDeals) {
            $newDealsList[$key]['DealsDetailUrl']   = PathManager::getDhbDealsDetailUrl($newDeals['CouponID']);
            TrackingFE::registerDHBOfferLink($newDeals, true, false);
        }
        return $newDealsList;
    }

    /*
     * 大红包coupon搜索右侧：今日精选促销
     * 取10条最新促销信息，一个商家仅显示一条，商家LOGO，无链接
     */
    public function getDhbTodayDealsList($siteid, $limit = 10) {
        //按时间倒序显示最新10条促销信息
        $where = array(
            'Coupon.SiteID'   => $siteid,
            //'Coupon.IsActive' => 'YES',
            'CouponType'      => 'DISCOUNT',
        );
        $select = $this->getSql()->select();
        $select->columns(array('CouponID', 'CouponName', 'MerchantID', 'CouponUrl', 'IsAffiliateUrl', 'CouponType'));
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantID', 'LogoFile', 'MerchantName','MerchantUrl', 'AffiliateUrl'), 'inner');
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->where($where);
        $select->group('Coupon.MerchantID');
        $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        $select->limit($limit);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $todayDealsList = $resultSet->toArray();
        if (empty($todayDealsList)) {
            return array();
        }
        foreach ($todayDealsList as $key => $todayDeals) {
            $todayDealsList[$key]['DealsDetailUrl']   = PathManager::getDhbDealsDetailUrl($todayDeals['CouponID']);
            TrackingFE::registerDHBOfferLink($todayDeals, true, false);
        }
        return $todayDealsList;
    }
}