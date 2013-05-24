<?php
/*
 * package_name : CategoryTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CategoryTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\Cmus;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;

class CategoryTable extends TableGateway 
{
    /**
     * 表名称
     * @var string 
     */
    protected $table = 'Category';
    
    /**
     * 存储couponCategory
     * @var
     */
    protected $couponCateList = array();
    
    /**
     * 获取Category 信息
     */
    public function getCateListByCouponID($couponID, $columns = array())
    {
        $couponID = $couponID * 1;
        if (empty($this->couponCateList)) {
            $this->fetchAll($columns);
        }
        return $this->couponCateList[$couponID];
    }
    
    /**
     * 获取CouponCateList之间关系
     */
    public function fetchAll($columns) 
    {
        $select = $this->getSql()->select();
        if (!empty($columns)) {
            $select->columns($columns);
        }
        $result = $select->join('CoupCat', 'CoupCat.Category_ = Category.Category_', array())
                         ->join('Coupon', 'Coupon.Coupon_ = CoupCat.Coupon_', array('Coupon_' => 'Coupon_'));
        $select->where(array('Coupon.IsActive' => 1));
        $couponCateList = $this->selectWith($select)->toArray();
        
        foreach ($couponCateList as $couponCateInfo) {
            $couponID = $couponCateInfo['Coupon_'];
            $this->couponCateList[$couponID][] = $couponCateInfo['Name'];
        }
        return $this->couponCateList;
    }
}
?>