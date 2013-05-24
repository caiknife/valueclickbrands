<?php
/*
 * package_name : CouponTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponTable.php,v 1.7 2013/05/21 02:14:11 thomas_fu Exp $
 */
namespace BackEnd\Model\Coupon;

use Zend\Db\Sql\Select;

use Zend\Paginator\Adapter\DbSelect;

use Custom\Util\Utilities;

use Zend\Db\Sql\Where;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Adapter;
class CouponTable extends TableGateway 
{
    protected $table = 'Coupon';
    
    protected $columnList = array(
        'MerchantID'            => true,
        'AffiliateID'           => true, 
        'CouponName'            => true,
        'CouponShortName'       => true,
        'CouponDescription'     => true,
        'CouponRestriction'     => true,
        'CouponUrl'             => true,
        'CouponImageUrl'        => true,
        'CouponStartDate'       => true,
        'CouponEndDate'         => true,
        'CouponType'            => true,
        'CouponStatus'          => true,
        'CouponAmount'          => true,
        'CouponReduction'       => true,
        'CouponDiscount'        => true,
        'CouponBrandName'       => true,
        'SKU'                   => true,
        'IsFromCmus'            => true,
        'SiteID'                => true,
        'IsFree'                => true,
        'IsActive'              => true,
        'IsStop'                => true,
        'IsPromote'             => true,
        'InsertDateTime'        => true,
        'IsAffiliateUrl'        => true,
        'AdsID'                 => true
    );
    
    /**
     * 缓存CouponCode结果集
     * @var array
     */
    public $couponCodeList = array();
    
    /**
     * 根据SKU获取coupon信息
     * @param string $sku
     * @return array
     */
    public function getInfoBySku($sku) {
        if (empty($sku)) {
            return false;
        }
        $where['SKU'] = $sku;
        $columns = array('CouponID', 'CouponName');
        return $this->getInfo($where, $columns);
    }
    
    /**
     * 根据联盟ID 获取couponCode列表
     * @param int $affiliateID
     * @return array
     */
    public function getCouponCodeListByAffID($affiliateID)
    {
        $affiliateID = $affiliateID * 1;
        if (isset($this->couponCodeList[$affiliateID])) {
            return $this->couponCodeList[$affiliateID];
        }
        $select = $this->getSql()->select();
        $select->join('CouponCode', 'CouponCode.CouponID = Coupon.CouponID', array('CouponCode'));
        $select->where(array('IsActive' => 'YES'));
        $couponCodeListTmp = $this->selectWith($select);
        foreach ($couponCodeListTmp as $couponCodeListTmp) {
            $this->couponCodeList[$affiliateID][$couponCodeListTmp->CouponCode] = true;
        }
        return $this->couponCodeList[$affiliateID];
    }
    
    /**
     * 根据商家ID获取此商家相关分类以及分类COUPON和DEAL条数
     * @param int $merid
     * @return array
     */
    public function getCouponCateByMerID($merid) {
        if (empty($merid)) {
            return false;
        }
        $select = $this->getSql()->select();
        $select->join('CouponCategory', 'Coupon.CouponID = CouponCategory.CouponID', array('CategoryID'));
        $select->columns(
                array(
                    'CouponCnt' => New Expression('count(Coupon.CouponID)'),
                    'CouponType'=> 'CouponType',
                )
        );
        $select->where(
            array(
                'Coupon.MerchantID' => $merid,
                'Coupon.IsActive' => 'YES',
                'Coupon.CouponType' => array('COUPON', 'DISCOUNT')
            )
        );
        $select->group(array('CouponCategory.CategoryID', 'Coupon.CouponType'));
        $result = array();
        foreach ($this->selectWith($select)->toArray() as $cateList) {
            if ($cateList['CouponType'] == 'COUPON') {
                $result[$cateList['CategoryID']]['CouponCnt'] = $cateList['CouponCnt'];
            } else {
                $result[$cateList['CategoryID']]['DiscountCnt'] = $cateList['CouponCnt'];
            }
        }
        return $result;
    }
    
    public function getCouponById($id){
        return $this->getInfo(array('CouponID' => $id));
    }
    /**
     * 返回分页Adaper
     * @return \Zend\Paginator\Adapter\DbSelect
     */
    public function getCouponsForPaginator()
    {
        $select = $this->_getSelect();
        return new DbSelect($select, $this->getAdapter());
    }
    
    /**
     * 插入INSERT 数据
     * @return insertID
     */
    public function insert($insertData) 
    {
        if (empty($insertData)) {
            throw new Expression('insert data can not empty');
        }
        $insertData = $this->dataMapping($insertData);
        if (empty($insertData)) {
            return false;
        }
        
        if(empty($insertData['SKU'])){
            $insertData['SKU'] = $this->_createSKU($insertData);
        }
        $insertData['InsertDateTime'] = Utilities::getDateTime();
        parent::insert($insertData);
        return $this->getLastInsertValue();
    }
    
    /**
     * 更新Coupon
     * @param array $data
     * @return boolean
     */
    public function save($data)
    {
        $id = $data['CouponID'];
        unset($data['CouponID']);
        $data['SKU'] = $this->_createSKU($data);
        return parent::update($data , array('CouponID' => $id));
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
     * 生成SKU
     * @param array $data 插入的标准数据 
     * @return string
     */
    protected function _createSKU($data){
        return md5(
                    $data['MerchantID'] . $data['CouponName'] . $data['CouponDescription'] 
                    . $data['CouponUrl'] . $data['CouponStartDate'] . $data['CouponEndDate']
                );
    }
    
    /**
     * 商家ID返回在线Conpon数量
     * @param int $merId
     * @return int
     */
    public function getCouponCountByMer($merId)
    {
        $merId = (int)$merId;
        $where['MerchantID'] = $merId;
        return $this->getListCount($where);
    }
    
    /**
     * 变更上线状态
     * @param array $id
     * @param string $state
     * @return boolean
     */
    public function changeActive(array $id , $state)
    {
        $where = $this->_getSelect()->where;
        $where->in('CouponID' , $id);
        
        return $this->update(array('IsActive' => $state) , $where);
    }
    
    /**
     * 更新图片
     * @param int $couponId
     * @param string $imageFile
     * @return boolean
     */
    public function updateImage($couponId , $imageFile){
        return $this->update(array('CouponImageUrl' => $imageFile) , array('CouponID' => (int)$couponId));
    }
    
    /**
     * (non-PHPdoc)
     * @see \Custom\Db\TableGateway\TableGateway::formatWhere()
     */
    public function formatWhere($data)
    {
        $select = $this->_getSelect();
        $where = $select->where;
        $select->reset(Select::WHERE);

        $select->join('Merchant', 'Merchant.MerchantID = Coupon.MerchantID' 
            , array('MerchantName') , Select::JOIN_INNER);
        
        if(!empty($data['SiteID'])){
            $where->equalTo('Coupon.SiteID', (int)$data['SiteID']);
        }
        
        if(!empty($data['IsActive'])){
            $where->equalTo('Coupon.IsActive', $data['IsActive']);
        }else{
            $where->equalTo('Coupon.IsActive' , 'YES');
        }
        
        if(!empty($data['CouponName'])){
            $where->like('Coupon.CouponName', '%' . $data['CouponName'] . '%');
        }
        
        if(!empty($data['MerchantSearch'])){
            if('MerchantID' == $data['MerchantSearchType']){
                $where->equalTo('Coupon.MerchantID', (int)$data['MerchantSearch']);
            }elseif('MerchantName' == $data['MerchantSearchType']){
                $where->like('Merchant.MerchantName' , '%' . $data['MerchantSearch'] . '%');
            }
        }
        
        if(!empty($data['CategoryID'])){
            $select->join('CouponCategory', 'CouponCategory.CouponID = Coupon.CouponID'
                , array() , Select::JOIN_INNER);
            $where->equalTo('CouponCategory.CategoryID', (int)$data['CategoryID']);
        }
        
        if(!empty($data['AffiliateID'])){
            $where->equalTo('Coupon.AffiliateID', $data['AffiliateID']);
        }
        
        if(!empty($data['CouponType'])){
            $where->equalTo('Coupon.CouponType' , $data['CouponType']);
        }
        
        $select->order(array('InsertDateTime' => 'DESC'));
        $select->where($where);
        $this->select = $select;
    }
    
    /**
     * 下线过期的优惠卷
     * @param string $couponEndDate
     * @param string $siteType
     * @return int onffline count
     */
    public function setOfflineCoupon($couponEndDate, $siteType = 'CN') 
    {
        $siteID = $siteType == 'CN' ? 1 : 2; 
        $where = $this->_getSelect()->where;
        $where->lessThanOrEqualTo('CouponEndDate', $couponEndDate);
        $where->equalTo('IsActive', 'YES');
        $where->equalTo('SiteID', $siteID);
        $couponList = $this->getList($where, array('CouponID'));
        foreach ($couponList as $couponInfo) {
            $this->update(array('IsActive' => 'NO'), array('CouponID' => $couponInfo['CouponID']));
        }
        return count($couponList);
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
     * 根据联盟ID和联盟中的广告ID 返回coupon信息
     * @param int $adsID
     * @param int $affiliateID
     * @return array
     */
    public function getInfoByAdsID($adsID, $affiliateID) 
    {
        if (empty($adsID)) {
            return array();
        }
        $where['AdsID'] = (int) $adsID;
        $where['AffiliateID'] = (int) $affiliateID;
        $couponInfo = $this->getInfo($where);
        if (empty($couponInfo)) {
            return array();
        } else {
            return $couponInfo;
        }
    }
    
    /**
     * 下线产品
     * @param array $onlineCoupon  在线的coupon列表
     * @param int $affiliateID  联盟ID
     * @return boolean
     */
    public function updateCouponOffLine($onlineCoupon = array(), $affiliateID) 
    {
        if (empty($onlineCoupon) || empty($affiliateID)) {
            return false;
        }
        $sql = sprintf(
                "UPDATE {$this->table} SET IsActive = '%s' WHERE IsActive = 'YES' AND AffiliateID = %d AND CouponID NOT IN ('%s')",
                'NO', $affiliateID, implode("','", $onlineCoupon)
        );
        return $this->getAdapter()->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }
    
    /**
     * 根据affiliateID获取coupon数量
     * @param int $affiliateID
     * @return int couponCnt
     */
    public function getListCountByAffiliateID($affiliateID) 
    {
        if (empty($affiliateID)) {
            return false;
        }
        $where['AffiliateID'] = (int) $affiliateID;
        $where['IsActive'] = 'YES';
        return $this->getListCount($where);
    }
}
?>