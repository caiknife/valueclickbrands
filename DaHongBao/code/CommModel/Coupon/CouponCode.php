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
* @Version  : CVS: $Id: CouponCode.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class CouponCode extends TableGateway
{
    protected $table = "CouponCode";

    /*
     * 获取一条能被使用的CouponCode CouponCodeTotalCnt > 0
     */
    public function getCouponCodeInfoByID($couponid) {
        $where = array(
            'CouponID' => $couponid,
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->where("CouponCodeTotalCnt > 0");
        $select->order("CouponCodeTotalCnt DESC");
        $select->limit(1);
        $resultSet = $this->selectWith($select);
        return current($resultSet->toArray());
    }
    
    /*
     * 修改CouponCode对应的CouponCodeTotalCnt和LastChangeDateTime
     */
    public function updateCouponCodeInfo($id) {
        return $this->getAdapter()->query("UPDATE CouponCode SET CouponCodeTotalCnt = CouponCodeTotalCnt - 1, LastChangeDateTime = NOW() WHERE CouponCodeID = {$id}", "execute");
    }
}