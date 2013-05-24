<?php
/*
* package_name : CouponSearch.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: CouponSearch.php,v 1.10 2013/05/09 01:42:19 rizhang Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;
use Custom\Db\TableGateway\TableGateway;
use Custom\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Expression;

class CouponSearch extends TableGateway
{
    protected $table = "CouponSearch";

/*
     * 搜索页面getSearchSortByList
     */
    public function getSearchList($siteid, $CouponType, $keyword, $sortby = null) {
        $where = array(
            'CouponSearch.SiteID' => $siteid,
            'CouponSearch.CouponType' => $CouponType,
        );
        if ($siteid == 2) {
            // bugzilla 315748
            $where['Coupon.IsActive'] = 'YES';
        }
        $select = $this->getSql()->select();
        $select->columns(array("Rank" => new Expression("(MATCH (CouponSearch.CouponName) AGAINST ('".$keyword."') * 50 + MATCH (CouponSearch.MerchantName) AGAINST ('".$keyword."') * 50 + MATCH (CouponSearch.MerchantAliasName) AGAINST ('".$keyword."') * 50 + MATCH (CouponSearch.CouponDescription) AGAINST ('".$keyword."')  * 5)")));
        $select->where($where);
        $select->where("MATCH (CouponSearch.CouponName, CouponSearch.MerchantName, CouponSearch.MerchantAliasName, CouponSearch.CouponDescription) AGAINST ('".$keyword."' IN BOOLEAN MODE)");
        $select->join('Coupon', 'Coupon.CouponID = CouponSearch.CouponID', '*', 'inner');
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantID', 'MerchantName','MerchantUrl', 'AffiliateUrl'), 'inner');
        $select->join("Affiliate", "Merchant.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        //$select->order("Rank DESC");
        if ($sortby) {
            //大红包，不显示CouponCode
            $select->join('CouponExtra', 'Coupon.CouponID = CouponExtra.CouponID', array('ViewCnt'), 'inner');
        } else {
            //海外直接显示CuponCode，只有一个CouponCode
            $select->join('CouponCode', 'Coupon.CouponID = CouponCode.CouponID', 'CouponCode', 'left');
        }
        if ($sortby == 'hot') {
            $select->order("Coupon.IsActive DESC, Rank DESC, CouponExtra.ReceiveCnt DESC, Coupon.CouponEndDate ASC");
        } else {
            $select->order("Coupon.IsActive DESC, Rank DESC, Coupon.CouponEndDate ASC");
        }
        
        $select->group('CouponSearch.CouponID');
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        return new DbSelect($select, $this->getAdapter());
    }
}