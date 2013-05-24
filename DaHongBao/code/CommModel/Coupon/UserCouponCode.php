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
* @Version  : CVS: $Id: UserCouponCode.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class UserCouponCode extends TableGateway
{
    protected $table = "CouponCodeUser";
    /*
     * 是否已经获取该coupon code
     */
    public function isUserCouponCode($uid, $couponid) {
        $where = array(
            'UID'      => $uid,
            'CouponID' => $couponid,
        );
        $select = $this->getSql()->select();
        $select->columns(array('CouponID'));
        $select->where($where);
        if ($limit !== null || $offset !== null) {
            $select->limit($limit);
            $select->offset($offset);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    
    /*
     * 收录用户的coupon code信息
     */
    public function insertUserCouponCode($insertValues = array()) {
        $insert = $this->getSql()->insert();
        $insert->values($insertValues);
        return $this->insertWith($insert);
    }
}