<?php
/*
* package_name : UserOnline.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: UserOnline.php,v 1.1 2013/04/26 08:41:53 rizhang Exp $
*/
namespace CommModel\Coupon;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class UserOnline extends TableGateway
{
    protected $table = "UserOnline";
    /*
     * 用户是否已经登陆了
     */
    public function isUserOnline($authKey) {
        $where = array(
            'AuthKey'      => $authKey,
        );
        $select = $this->getSql()->select();
        $select->columns(array('UID'));
        $select->where($where);
        $resultSet = $this->selectWith($select);
        return current($resultSet->toArray());
    }
}