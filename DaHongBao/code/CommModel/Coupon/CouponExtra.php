<?php
/*
* package_name : CouponExtra.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: CouponExtra.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class CouponExtra extends TableGateway
{
    protected $table = "CouponExtra";

    /*
     * 展示数+1
     */
    public function addCouponViewCnt($couponid) {
        return $this->getAdapter()->query("UPDATE CouponExtra SET ViewCnt = ViewCnt + 1 WHERE CouponID = {$couponid}", "execute");
    }

    /*
     * 获取优惠券，相应的数量减少增加
     */
    public function updateCouponAllCnt($couponid) {
        //优惠券CouponID对应的，领取数+1，剩下数-1
        $this->getAdapter()->query("UPDATE CouponExtra SET ReceiveCnt = ReceiveCnt + 1 WHERE CouponID = {$couponid}", "execute");
        $this->getAdapter()->query("UPDATE CouponExtra SET LeaveCnt = LeaveCnt - 1 WHERE CouponID = {$couponid}", "execute");
        return true;
    }
}