<?php
/*
 * package_name : CouponExtraTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponExtraTable.php,v 1.2 2013/04/19 08:15:02 yjiang Exp $
 */
namespace BackEnd\Model\Coupon;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Custom\Util\Utilities;

class CouponExtraTable extends TableGateway 
{
    protected $table = 'CouponExtra';
    
    /**
     * 插入CouponExtra信息
     * @param int $couponID
     * @param int $totalCnt
     * @param int $leaveCnt
     * @return int
     */
    public function insertCouponCnt($couponID, $couponCnt = 1) 
    {
        if (empty($couponID)) {
            throw new \Exception('Couponid is empty');
        }
        $insert['CouponID'] = $couponID * 1;
        $couponCnt = $couponCnt * 1;
        $insert['TotalCnt'] = $couponCnt;
        $insert['LeaveCnt'] = $couponCnt;
        $insert['InsertDateTime'] = Utilities::getDateTime();
        return parent::insert($insert);
    }
    
    /**
     * 更新couponExtra中的TotalCnt, LeaveCnt数量
     * @param int $couponID
     * @param int $couponCnt
     * @throws null
     */
    public function updateCouponCnt($couponID, $couponCnt = 1) 
    {
        if (empty($couponID)) {
            throw new \Exception('Couponid is empty');
        }
        $where['CouponID'] = $couponID * 1;
        $couponExtraInfo = $this->getInfo($where);
        if (empty($couponExtraInfo)) {
            return false;
        }
        $update['TotalCnt'] = $couponExtraInfo['TotalCnt'] + $couponCnt;
        $update['LeaveCnt'] = $couponExtraInfo['LeaveCnt'] + $couponCnt;
        return parent::update($update, $where);
    }
    
    function deleteByCid($cid){
        return $this->delete(array('CouponID' => (int)$cid));
    }
    
    public function save($couponId , $couponCodeCount = 1)
    {
        if(false === $this->updateCouponCnt($couponId , $couponCodeCount)){
            $this->insertCouponCnt($couponId , $couponCodeCount);
        }
        
        return true;
    }
}
?>