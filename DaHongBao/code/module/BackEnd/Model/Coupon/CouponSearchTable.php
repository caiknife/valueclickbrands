<?php
/*
 * package_name : CouponSearchTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponSearchTable.php,v 1.6 2013/04/26 08:25:15 yjiang Exp $
 */
namespace BackEnd\Model\Coupon;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Custom\Util\WordSplitter;
use Custom\Util\Utilities;

class CouponSearchTable extends TableGateway 
{
    protected $table = 'CouponSearch';
    protected $columnList = array(
        'CouponID'          => true,
        'MerchantID'        => true,
        'MerchantAliasName' => true,
        'CouponName'        => true,
        'MerchantName'      => true,
        'MerchantNameEN'    => true,
        'CouponDescription' => true,
        'CouponRestriction' => true,
        'CouponStartDate'   => true,
        'CouponEndDate'     => true,
        'CouponType'        => true,
        'SiteID'            => true
    );
    
    /**
     * 插入数据
     * @param array $insertData
     * @return int 插入条数
     */
    public function insert($insertData) 
    {
        if (empty($insertData)) {
            return false;
        }
        $insertData = $this->dataMapping($insertData);
        $insertData['InsertDateTime'] = Utilities::getDateTime('Y-m-d H:i:s');
        $insertData = $this->_formatSearchWord($insertData);
        return parent::insert($insertData);
    }
    
    /**
     * 更新数据
     * @param array $data
     * @return boolean
     */
    public function save(array $data)
    {
        $id = $data['CouponID'];
        $data = $this->dataMapping($data);
        $data = $this->_formatSearchWord($data);
        unset($data['CouponID']);
        return $this->update($data , array('CouponID' => $id));
    }
    
    /**
     * 更新商家Alias
     * @param int $mid
     * @param string $alias
     * @return boolean
     */
    public function upDateMerchantAlias($mid , $alias){
        return $this->update(array('MerchantAliasName' => $alias) , array('MerchantID' => (int)$mid));
    }
    
    /**
     * 判断是否存在
     * @param int $data
     * @return boolean
     */
    public function hasInfoByCouponId($id)
    {
        $result = $this->getList(array('CouponID' => $id));
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 格式化标准数组
     * @param array $data
     * @return array
     */
    public function dataMapping($data) 
    {
       $result = array();
       foreach ($data as $dataKey => $dataValue) 
       {
           if (isset($this->columnList[$dataKey])) {
               $result[$dataKey] = $dataValue;
           }
       }
       return $result;
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
    
    /**
     * 拆分搜索词
     * @param array $insertData
     * @return array | false
     */
    private function _formatSearchWord($insertData) 
    {
        if (empty($insertData)) {
            return false;
        }
        if ($insertData['SiteID'] == 1) {
            
            //merchantName分词
            $merchantNameArr = WordSplitter::instance()->execute($insertData['MerchantName']);
            $insertData['MerchantName'] = implode(' ', $merchantNameArr);
            
            //couponName分词
            $couponNameArr = WordSplitter::instance()->execute($insertData['CouponName']);
            $insertData['CouponName'] = implode(' ', $couponNameArr);
            
            //desc分词
            if (!empty($insertData['CouponDescription'])) {
                $destNameArr = WordSplitter::instance()->execute($insertData['CouponDescription']);
                $insertData['CouponDescription'] = implode(' ', $destNameArr);
            }
        } else {
            $insertData['MerchantName'] = $insertData['MerchantName'];
            $insertData['CouponName'] = $insertData['CouponName'];
            $insertData['CouponDescription'] = $insertData['CouponDescription'];
        }
        return $insertData;
    }
}