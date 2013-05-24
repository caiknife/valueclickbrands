<?php
/*
 * package_name : CouponCodeTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponCodeTable.php,v 1.3 2013/05/20 10:01:30 thomas_fu Exp $
 */
namespace BackEnd\Model\Coupon;

use Zend\Db\Adapter\Adapter;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Custom\Util\Utilities;

class CouponCodeTable extends TableGateway
{
    protected $table = 'CouponCode';
    
    /**
     * 判断这个CouponCode是否存在
     * @param int $couponID
     * @param string $couponCode 
     * @param string $couponPass
     * @return false|true
     */
    public function isExistCouponCode($couponID, $couponCode = '', $couponPass='') 
    {
        if (empty($couponID)) {
            return false;
        }
        $where['CouponID'] = $couponID * 1;
        if (!empty($couponCode)) {
            $where['CouponCode'] = $couponCode;
        }
        $couponInfo = $this->getInfo($where);
        if (empty($couponInfo)) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 插入数据
     * @param int $couponID
     * @param string $couponCode 
     * @param string $couponPass
     * @return int lastInsertID
     */
    public function insertCouponCode($couponID, $couponCode, $couponPass=null , $couponCodeTotalCnt=1) 
    {
        if (empty($couponID)) {
            return false;
        }
        $insertData['CouponID'] = $couponID;
        $insertData['CouponCode'] = $couponCode;
        $insertData['CouponPass'] = $couponPass;
        $insertData['CouponCodeTotalCnt'] = $couponCodeTotalCnt;
        $insertData['InsertDateTime'] = Utilities::getDateTime();
        parent::insert($insertData);
        return $this->getLastInsertValue();
    }
    
    /**
     * 更新数据
     * @param int $couponID
     * @param string $couponCode
     * @param string $couponPass
     * @return true or false
     */
    public function updateCouponCode($couponID, $couponCode, $couponPass=null) 
    {
        if (empty($couponID)) {
            return false;
        }
        $updateData['CouponCode'] = $couponCode;
        $updateData['CouponPass'] = $couponPass;
        $where['CouponID'] = $couponID;
        return parent::update($updateData, $where);
    }
    
    /**
     * 批量插入Code 重复的过滤
     * @param array $codes
     * @return int 插入的行数
     */
    public function saveCouponCodes(array $codes)
    {
        $i = 0;
        foreach($codes as $code){
            
            if(!$this->isExistCouponCode($code['CouponID'], $code['CouponCode'] , $code['CouponPass'])){
                $this->insertCouponCode($code['CouponID'], $code['CouponCode'] , $code['CouponPass'] 
                    , $code['CouponCodeTotalCnt']);
                $i += $code['CouponCodeTotalCnt'];
            }
        }
        
        return $i;
    }
    
    /**
     * 根据CouponID获取Code
     * @param unknown_type $couponId
     * @return multitype:
     */
    public function getCodeByCouponId($couponId)
    {
        return $this->getList(array('CouponID' => (int)$couponId));
    }
    
    /**
     * 返回指定Coupon的Code总数
     * @param int $couponId
     * @return int
     */
    public function getCodeCountByCouponId($couponId)
    {
        $result = $this->getInfo(array('CouponID' => $couponId) , array('sum' => new Expression('sum(CouponCodeTotalCnt)')));
        
        return $result['sum'];
    }
    
    /**
     * 更新数量
     * @param int $couponCodeID
     * @param int $count
     * @return boolean
     */
    public function setCouponCodeTotalCnt($couponCodeID , $count)
    {
        return $this->update(array('CouponCodeTotalCnt' => $count) , array('CouponCodeID' => $couponCodeID));
    }
    /**
     * Code增加数量
     * @param string $couponId
     * @param string $couponCode
     */
    public function increaseCode($couponId , $couponCode , $count = 1)
    {
        $sql = sprintf("UPDATE CouponCode SET CouponCodeTotalCnt = CouponCodeTotalCnt + %d WHERE CouponID = '%s' AND 
            CouponCode = '%s'" , $count , $couponId , $couponCode);
        return $this->getAdapter()->query($sql , Adapter::QUERY_MODE_EXECUTE);
    }
    
    /**
     * 根据CouponId删除
     * 
     * @param int $cid
     * @return number
     */
    function deleteByCid($cid){
        return $this->delete(array('CouponID' => (int)$cid));
    }
}
?>