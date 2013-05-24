<?php
/*
* package_name : CouponCategory.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: CouponCategory.php,v 1.3 2013/05/09 01:42:19 rizhang Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Util\PathManager;
use Custom\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;

class CouponCategory extends TableGateway
{
	protected $table = "CouponCategory";

    /*
     * 获取Category优惠券列表，有分页
     */
    public function getCategoryCouponList($siteid, $cateid = NULL, $limit = null, $sortby = null, $merid = null) {
        $where = array(
            'Coupon.SiteID' => $siteid,
            'Coupon.CouponType' => 'COUPON',
            //'Coupon.IsActive'   => 'YES',
            'CouponCategory.CategoryID' => $cateid
        );
        if ($siteid == 2) {
            // bugzilla 315748
            $where['Coupon.IsActive'] = 'YES';
        }
        $select = $this->getSql()->select();
        $select->join('Coupon',    'Coupon.CouponID = CouponCategory.CouponID', '*', 'inner');
        if ($sortby) {
            //大红包，不显示CouponCode
            $select->join('CouponExtra', 'CouponCategory.CouponID = CouponExtra.CouponID', array(), 'inner');
        } else {
            //海外直接显示CuponCode
            $select->join('CouponCode', 'CouponCategory.CouponID = CouponCode.CouponID', 'CouponCode', 'left');
        }
        $select->join('Merchant', 'Coupon.MerchantID = Merchant.MerchantID', array('MerchantName'), 'inner');
        $select->join("Affiliate", "Coupon.AffiliateID = Affiliate.ID", array('Name', 'UrlVarible'), "left");
        if ($merid) {
            $where['Coupon.MerchantID'] = $merid;
        }
        $select->where($where);
        if ($sortby == 'hot') {
            $select->order("Coupon.IsActive DESC, CouponExtra.ReceiveCnt DESC, CouponExtra.ViewCnt DESC, Coupon.CouponEndDate ASC");
        } else {
            $select->order("Coupon.IsActive DESC, Coupon.CouponEndDate ASC");
        }
        $select->where($where);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        return new DbSelect($select, $this->getAdapter());
    }
}
?>