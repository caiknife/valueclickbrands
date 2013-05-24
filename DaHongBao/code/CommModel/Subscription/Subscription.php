<?php
/*
* package_name : Subscription.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Subscription.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Subscription;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class Subscription extends TableGateway
{
    protected $table = "Subscription";

    public function fetchAll($where = array(), $order = null, $limit = null, $offset = null)
    {
        $select = $this->getSql()->select();
        $select->where($where);
        if ($order !== null) {
            $select->order($order);
        } else {
            $select->order('ID DESC');
        }
        if ($limit !== null || $offset !== null) {
            $select->limit($limit);
            $select->offset($offset);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }

    public function isExistEmail($email = null, $siteid) {
        if (empty($email)) {
            return array();
        }
        $where = array(
            'Email'  => $email,
            'SiteID' =>$siteid,
        );
        return self::fetchAll($where);
    }

    public function insert($insertValues = array()) {
        $insert = $this->getSql()->insert();
        $insert->values($insertValues);
        return $this->insertWith($insert);
    }
}
?>