<?php
/*
* package_name : Merchant.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Merchant.php,v 1.8 2013/04/26 09:26:47 rizhang Exp $
*/
namespace CommModel\Merchant;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;

class Merchant extends TableGateway
{
    protected $table = "Merchant";

    public function fetchAll($where = array(), $columns = array(), $order = null, $limit = null, $offset = null)
    {
        $select = $this->getSql()->select();
        $select->where($where);
        if ($columns) {
            $select->columns($columns);
        }
        if ($order !== null) {
            $select->order($order);
        } else {
            $select->order('MerchantID DESC');
        }
        if ($limit !== null || $offset !== null) {
            $select->limit($limit);
            $select->offset($offset);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }

    /*
     * 所有商家页面，返回基本信息
     */
    public function getMerchantList($siteid, $order = null) {
        $where = array(
            'SiteID'   => $siteid,
            'IsActive' => 'YES',
        );
        $columns = array('MerchantID', 'MerchantName', 'LogoFile');
        return self::fetchAll($where, $columns, $order);
    }

    /*
     * 商家详细信息
     */
    public function getMerchantInfoByID($merid = null, $siteid) {
        if (empty($merid)) {
            return array();
        }
        $merid = (int)$merid;
        $select = $this->getSql()->select();
        $where = array(
            'Merchant.SiteID'     => $siteid,
            'Merchant.MerchantID' => $merid,
            'Merchant.IsActive'   => 'YES',
        );
        $select->where($where);
        //$columns = array();
        $select->columns(array('MerchantID', 'MerchantName', 'MerchantUrl', 'DescriptionCN', 'LogoFile', 'SupportCN', 'SupportDeliveryCN', 'MainSales', 'AffiliateUrl', 'Instructions'));
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $resultSet = $this->selectWith($select);
        return current($resultSet->toArray());
    }

    /*
     * 热门商家
     */
    public function getHotMerchantList($siteid, $limit = 4, $catid = null) {
        $where = array(
            'SiteID'   => $siteid,
            'IsActive' => 'YES',
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->columns(array('MerchantID', 'MerchantName', 'LogoFile'));
        if ($catid) {
            $select->join('MerchantCategory', 'Merchant.MerchantID = MerchantCategory.MerchantID', array('r_OnlineCouponCount'), 'inner');
            $select->where("MerchantCategory.CategoryID = $catid AND MerchantCategory.r_OnlineCouponCount > 0");
        }
        $select->order('Sequence DESC');
        $select->limit($limit);
        $resultSet = $this->selectWith($select);
        $hotMerchantList = $resultSet->toArray();
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        if ($hotMerchantList) {
	        foreach ($hotMerchantList as $key => $hotMerchant) {
	            if ($siteid == '1') {
	                $hotMerchantList[$key]['MerchantDetailUrl'] = PathManager::getDhbMerchantDetailUrl($hotMerchant['MerchantID']);
	            } elseif ($siteid == '2') {
	                $hotMerchantList[$key]['MerchantDetailUrl'] = PathManager::getMerchantDetailUrl($hotMerchant['MerchantID']);
	            }
	        }
        }
        return $hotMerchantList;
    }

    /*
     * 大红包   右侧  精选商家优惠券
     */
    public function getDhbRecommendMerchantCouponList($siteid, $limit = 10) {
        //按照商家等级排序，选择前10个商家，每个商家取一条领取或使用量最高的优惠券
        $where = array(
            'Coupon.SiteID'   => $siteid,
            'Merchant.SiteID' => $siteid,
            'Coupon.IsActive'   => 'YES',
            'Merchant.IsActive' => 'YES',
            'CouponType' => 'COUPON',
        );
        $select = $this->getSql()->select();
        $select->columns(array('MerchantID','MerchantName', 'MerchantUrl', 'AffiliateUrl'));
        $select->join('Coupon', 'Coupon.MerchantID = Merchant.MerchantID', array('CouponID', 'CouponName', 'CouponUrl', 'IsAffiliateUrl', 'CouponType'), 'inner');
        $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array(), 'inner');
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        $select->group('Merchant.MerchantID');
        $select->where($where);
        $select->where('Coupon.CouponID > 0');
        $select->order('Merchant.Sequence DESC');
        $select->order('CouponExtra.ReceiveCnt DESC');
        $select->limit($limit);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $recommendMerchantList = $resultSet->toArray();
        if (empty($recommendMerchantList)) {
            return array();
        }
        foreach ($recommendMerchantList as $key => $recommendMerchant) {
            $recommendMerchantList[$key]['CouponDetailUrl']   = PathManager::getDhbCouponDetailUrl($recommendMerchant['CouponID']);
            $recommendMerchantList[$key]['MerchantDetailUrl'] = PathManager::getDhbMerchantDetailUrl($recommendMerchant['MerchantID']);
            TrackingFE::registerDHBOfferLink($recommendMerchant, true, false);
        }
        return $recommendMerchantList;
    }


    /*
     * 是不是MerchantName
     */
    public function isMerchantName($siteid, $name)
    {
        $select = $this->getSql()->select();
        $subWhereForName = clone $select->where;
        $select->where(
            array(
            'SiteID' => $siteid,
            'IsActive' => 'YES',
            )
        );
        $subWhereForName->equalTo('MerchantName', $name);
        $subWhereForName->or;
        $subWhereForName->equalTo('MerchantNameEN', $name);
        $select->where->addPredicate($subWhereForName);
        $select->columns(array('MerchantID'));
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $resultArr = current($resultSet->toArray());
        return $resultArr['MerchantID'];
    }
}