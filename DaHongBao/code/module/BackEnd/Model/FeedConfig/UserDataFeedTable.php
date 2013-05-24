<?php
/*
 * package_name : DataFeedUserData.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: UserDataFeedTable.php,v 1.4 2013/04/23 04:27:12 thomas_fu Exp $
 */
namespace BackEnd\Model\FeedConfig;

use Zend\Db\Sql\Where;

use Zend\Db\Sql\Select;

use Custom\Db\TableGateway\TableGateway;
use Custom\Util\Utilities;
use Zend\Paginator\Adapter\DbSelect;

class UserDataFeedTable extends TableGateway 
{
    protected $table = 'UserDataFeed';

    /**
     * 每个COUPON之间分隔符
     * @var string
     */
    public $couponsSeparator = "\n";
    
    /**
     * coupon和couponpass之间分隔符
     * @var string
     */
    public $couponPassSeparator = ',';
 
     
    /**
     * 获取这个商家下面所有的FEED
     * @param int $merid
     * @return array
     */
    public function getListByMerID($merid) 
    {
        if (empty($merid)) {
            return false;
        }
        $where['MerchantID'] = $merid * 1;
        $where['IsActive'] = 'YES';
        return $this->getList($where);
    }
    
    /**
     * 更新couponCode
     * @param int $id userdatafeedID
     * @param string $couponCode
     * @param string $couponPass
     */
    public function updateCouponCode($id, $couponCode, $couponPass) 
    {
        if (empty($id) || empty($couponCode)) {
            throw new \Exception('UserDataFEED ID or CouponCode Is empty');
        }
        $where['ID'] = $id * 1;
        $userDataFeedInfo = $this->getInfo($where);
        if (empty($userDataFeedInfo)) {
            throw new \Exception('UserDataFEED ID can not found.');
        }
        $newCouponCode = $couponCode . $this->couponPassSeparator . $couponPass;
        $update['CouponCode'] = $userDataFeedInfo['CouponCode'] . $this->couponsSeparator . $newCouponCode;
        return parent::update($update, $where);
    }
    
    /**
     * 跟新userdatafeed ACTIVE
     * @param int $id
     * @param string $active
     * @throws \Exception
     * @return boolean
     */
    public function updateActiveByID($id, $active = 'NO') 
    {
        if (empty($id)) {
                throw new \Exception('UserDataFEED ID Is empty');
        }
        $where['ID'] = $id * 1;
        $update['IsActive'] = $active == 'NO' ? 'NO' : 'YES';
        return $this->update($update, $where);
    }
    
    /**
     * 删除的数据
     * @param intval $merID
     * @param intval $affiliateID
     */
    public function deleteUserData($merID = 0, $affiliateID = 0) 
    {
        if ($merID > 0) {
            $where['MerchantID'] = $merID * 1;
        }
        if ($affiliateID > 0) {
            $where['AffiliateID'] = $affiliateID * 1;
        }
        $where['Status'] = 'INIT';
        return $this->delete($where);
    }
     
    /**
     * 根据sku 查询 datafeed是否存在库中
     * @param string $sku
     * @return false|array,  如果存在此条记录,返回此条数据
     */
    public function isExistDataFeedBySku($sku) 
    {
        if (empty($sku)) {
            return false;
        }
        $where['SKU'] = $sku;
        $userDataInfo = $this->getInfo($where);

        if (empty($userDataInfo)) {
            return false;
        } else {
            return $userDataInfo;
        }
    }
     
    /**
     * 根据AffiliateID 获取有效的FEED列表
     * @param int $affiliateID
     * @param int $limit
     * @return array
     */
    public function getListByAffID($affiliateID, $limit = '') 
    {
        if (empty($affiliateID)) {
        return false;
        }
        $where['AffiliateID'] = $affiliateID * 1;
        $where['IsActive'] = 'YES';
        return $this->getList($where, '', $limit);
    }
    
    /**
     * 根据ID获取数据
     * @param int $id
     * @return Ambigous <multitype:, mixed>
     */
    public function getInfoByID($id){
        return $this->getInfo(array('ID' => (int)$id));
    }
     
    /**
     * 删除数据被ID
     * @param int $id
     */
    public function deleteByID($id) 
    {
        if (empty($id)) {
            return false;
        }
        $where['ID'] = $id * 1;
        return parent::delete($where);
    }
    
    /**
     * 已上线
     * @param int $id
     * @return boolean
     */
    function statusOnline($id){
        return parent::update(array('Status' => 'ONLINE') , array('ID' => (int)$id));
    }
    
    /**
     * 已下线
     * @param int $id
     * @return boolean
     */
    function statusDelete($id){
        return parent::update(array('Status' => 'DELETE') , array('ID' => (int)$id));
    }
     
    /**
     * 插入数据
     * @param array $data
     * @return array
     */
    public function insert($data) 
    {
        $data['InsertDateTime'] = Utilities::getDateTime('Y-m-d H:i:s');
        return parent::insert($data);
    }
     
    /**
     * 得到联盟Feed数量
     * @param int $affiliateID
     * $return count
     */
    public function getListCountByAffID($affiliateID) 
    {
        if (empty($affiliateID)) {
            return false;
        }
        $where['AffiliateID'] = $affiliateID;
        return parent::getListCount($where);
    }
     
    /**
     * 得到商家Feed数量
     * @param int $merid
     * $return count
     */
    public function getListCountByMerID($merid) 
    {
        if (empty($merid)) {
            return false;
        }
        $where['MerchantID'] = $merid;
        return parent::getListCount($where);
    }
    
    /**
     * 获取分页用Adapter
     * @return \Zend\Paginator\Adapter\DbSelect
     */
    public function getListToPaginator()
    {
        $select = $this->_getSelect();
        return new DbSelect($select , $this->getAdapter());
    }
    
    /**
     * 更新MerchantID
     * @param int $merchantId
     * @param int $MerchantFeedName
     * @param int $AffiliateID
     * @return boolean
     */
    public function updateMerchant($merchantId , $merchantFeedName , $affiliateID)
    {
        return $this->update(array('MerchantID' => $merchantId) , array('MerchantFeedName' 
            => $merchantFeedName , 'AffiliateID' => $affiliateID));
    }
    
    /**
     * 设置查询条件
     * @see \Custom\Db\TableGateway\TableGateway::formatWhere()
     */
    public function formatWhere($data)
    {
        $select = $this->_getSelect();
        $select->reset(Select::WHERE);
        $where = $this->select->where;
        
        if(!empty($data['CouponName'])){
            $where->like('CouponName', '%' . $data['CouponName'] . '%');
        }
        
        if(isset($data['AffiliateID']) && $data['AffiliateID'] !== ''){
            $where->equalTo('AffiliateID', $data['AffiliateID']);
        }
        
        if(!empty($data['CouponType'])){
            $where->equalTo('CouponType', $data['CouponType']);
        }
        
        if(!empty($data['Merchant'])){
            if('id' == $data['MerchantType']){
                $where->equalTo('MerchantID', $data['Merchant']);
            }else{
                $where->like('MerchantFeedName', '%' . $data['Merchant'] . '%');
            }
        }
        
        $where->equalTo('Status', 'INIT');
        
        $this->select->where($where);
        
        return $this;
    }
}
?>