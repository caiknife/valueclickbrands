<?php
/*
 * package_name : CouponTable.php
 * ------------------
 * 得到couponmountain 数据
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\Cmus;

use Custom\Db\TableGateway\TableGateWay;
use Zend\Db\Sql\Expression;

class CouponTable extends TableGateWay
{
    /**
     * 表名称
     * @var string 
     */
    protected $table = 'Coupon';
    
    /**
     * 获取所有coupon 信息
     * @param true|false 是否是获取coupon记录数
     * @param int $offset
     * @param int $limit 
     * @return array result
     */
    public function fetchAll($isFetchCount = false, $offset = 0, $limit = 100)
    {
        $select = $this->getSql()->select();
        $slect = $select->join('Merchant', 'Merchant.Merchant_ = Coupon.Merchant_', array());
        $subWhereForEndDate = clone $select->where;
        $select->where(
            array(
                'Coupon.IsActive' => 1, 
                'Merchant.IsActive' => 1, 
                'Merchant.isDelete' => 0, 
                'Coupon.isDelete' => 0,
                'CouponType != 2',
            )
        );
        $subWhereForEndDate->greaterThanOrEqualTo('Coupon.ExpireDate', New Expression('curdate()'));
        $subWhereForEndDate->or;
        $subWhereForEndDate->equalTo('Coupon.ExpireDate', '0000-00-00');
        $select->where->addPredicate($subWhereForEndDate);
        if ($isFetchCount === true) {
            $select->columns(array('ListCount' => New Expression('count(*)')));
            $countInfo = current($this->selectWith($select)->toArray());
            return $countInfo['ListCount'];
        }
        $offset = $offset * 1;
        if ($offset < 0) {
            $offset = 0;
        }
        $slect->offset($offset);
        if (!empty($limit)) {
            $limit = $limit * 1;
            $select->limit($limit);
        }
        $select->order('Coupon.Coupon_ DESC');
        var_dump($select->getSqlString());
        return $this->selectWith($select)->toArray();
    }
}