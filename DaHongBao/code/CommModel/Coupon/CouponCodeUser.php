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
* @Version  : CVS: $Id: CouponCodeUser.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class CouponCodeUser extends TableGateway
{
    protected $table = "CouponCodeUser";

    /*
     * 插入一条用户领取优惠券记录
     */
    public function insertCouponCodeUser($insertValues = array()) {
        $insert = $this->getSql()->insert();
        $insert->values($insertValues);
        return $this->insertWith($insert);
    }
}