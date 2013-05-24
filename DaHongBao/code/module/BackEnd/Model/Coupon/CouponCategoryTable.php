<?php
/*
 * package_name : CouponCategoryTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponCategoryTable.php,v 1.2 2013/04/17 08:57:02 yjiang Exp $
 */
namespace BackEnd\Model\Coupon;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;

class CouponCategoryTable extends TableGateway 
{
    protected $table = 'CouponCategory';
    
    /**
     * 插入couponCategory数据
     * @param int $couponID
     * @param int $categoryID
     * @return object
     */
    public function insertCategory($couponID, $categoryID) 
    {
        if (empty($couponID) || empty($categoryID)) {
            throw new \Exception(
                'couponid or categoryid can not found. couponid=' . $couponID . ', categoryid = ' . $categoryID
            );
        }
        
        $result = $this->getInfo(array('CouponID' => $couponID , 'CategoryID' => $categoryID));
        if(!empty($result)){
            return 0;
        }
        
        $insert['CouponID'] = $couponID * 1;
        $insert['CategoryID'] = $categoryID * 1;
        return parent::insert($insert);
    }
    
    /**
     * 获取CouponCategory
     * @param int $couponId
     * @return array
     */
    public function getListByCouponId($couponId)
    {
        return $this->getList(array('CouponID' => (int)$couponId));
    }
    
    /**
     * 更新Category
     * @param int $couponId
     * @param array $categories
     */
    public function save($couponId , $categories)
    {
        $this->clearByCouponId($couponId);
        foreach($categories as $v){
            $this->insertCategory($couponId, $v);
        }
    }
    
    public function getCountByCid($cid){
        return $this->getListCount(array('CategoryID' => (int)$cid));
    }
    
    /**
     * 清除
     * @param int $couponId
     * @return number
     */
    public function clearByCouponId($couponId){
        return $this->delete(array('CouponID' => (int)$couponId));
    }
}
?>