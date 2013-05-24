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
* @Version  : CVS: $Id: User.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class User extends TableGateway
{
    protected $table = "User";
    /*
     * 获取用户当前积分
     */
    public function getUserRankPoints($uid) {
        $where = array(
            'UID'      => $uid,
        );
        $select = $this->getSql()->select();
        $select->columns(array('RankPoints'));
        $select->where($where);
        $resultSet = $this->selectWith($select);
        return current($resultSet->toArray());
    }
    
    /*
     * 领取优惠券，扣除用户相应的积分
     */
    public function reduceUserRankPoints($uid, $rankPoints) {
        return $this->getAdapter()->query("UPDATE User SET RankPoints = RankPoints - {$rankPoints} WHERE UID = {$uid}", "execute");
    }

}