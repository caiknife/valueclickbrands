<?php
/*
 * package_name : MerchantTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: MerchantTable.php,v 1.4 2013/05/20 02:44:46 yjiang Exp $
 */
namespace BackEnd\Model\Merchant;

use Custom\Util\Utilities;

use Zend\Db\Sql\Where;

use Zend\Db\Sql\Select;

use Zend\Paginator\Adapter\DbSelect;

use Custom\Db\TableGateway\TableGateway;

class MerchantTable extends TableGateway 
{
    const MERCHANT_ACTIVE_YES = 'YES';
    const MERCHANT_ACTIVE_NO = 'NO';
    
    protected $table = 'Merchant';
    
    protected $merchantList = array();

    /**
     * 获取分页
     * @param array $where
     * @return \Zend\Paginator\Adapter\DbSelect
     */
    public function getListToPaginator($order = array())
    {
        $select = $this->_getSelect();
        if (empty($order)) {
            $order = array('Merchant.Sequence' => 'DESC' , 'Merchant.MerchantID' => 'DESC');
        }
        $select->order($order);
//         var_dump($select->getSqlString());
        return new DbSelect($select, $this->getAdapter());
    }
    
    public function getListByActive($isActive = 'YES') {
        $where['IsActive'] = $isActive == 'YES' ? 'YES' : 'NO';
        return $this->getList($where);
    }
    
    /**
     * 根据名字获取列表
     * @param string $name
     * @return array
     */
    public function getListByName($name)
    {
        $where = $this->_getSelect()->where;
        $where->like('MerchantName' , '%' . $name . '%');
        $where->or;
        $where->like('MerchantNameEN' , '%' . $name . '%');
        
        return $this->getList($where);
    }
    
    /**
     * 得到feed文件不是来自于联盟商家列表
     */
    public function getNotFromAffMerList() 
    {
        $where['AffiliateID'] = 0;
        return $this->getList($where);
    }
    
    /**
     * 根据站点ID得到商家列表
     * @param int $siteID
     * @return array
     */
    public function getMerListBySiteID($siteID) 
    {
        if (empty($siteID)) {
            return array();
        }
        $where['SiteID'] = $siteID * 1;
        return $this->getList($where);
    }
    
    /**
     * 获取新插入的ID
     * @param int $siteId
     * @return number
     */
    public function getNewMerchantId($siteId = 1){
        $select = $this->_getSelect();
        $select->limit(1);
        $select->order('MerchantID DESC');
        $select->where(array('SiteID' => $siteId));
        $select->columns(array('MerchantID'));
        
        $result = $this->selectWith($select);
        $row = $result->current();
        
        return (int)$row->MerchantID + 1;
    }
    
    /**
     * 更新
     * @param mixed $data
     * @return number
     */
    public function update($data , $where = null){
        if($data instanceof Merchant){
            $data = $data->toArray();
        }
        $id = $data['MerchantID'];
        return parent::update($data , array('MerchantID' => $id));
    }
    
    /**
     * 插入
     * @param mixed $data
     * @return Ambigous <number, \Custom\Db\TableGateway\false, boolean>
     */
    public function insert($data){
        if($data instanceof Merchant){
            $data = $data->toArray();
        }
        if(!empty($data['MerchantID'])){
            if($this->getInfo(array('MerchantID' => $data['MerchantID']))){
                throw new \Exception('已存在的商家');
            }
        }
        $data['RegisterDateTime'] = Utilities::getDateTime();
        return parent::insert($data);
    }
    /**
     * 改变Active
     * @param mixed $id
     * @param string $state
     * @return boolean
     */
    public function changeActive($id , $state){
        $where = $this->_getSelect()->where;
        if(is_array($id)){
            $where->in('MerchantID' , $id);
        }else{
            $id = (int)$id;
            $where->equalTo('MerchantID', $id);
        }
        return parent::update(array('IsActive' => $state) , $where);
    }
    
    /**
     * 删除记录
     * @param mixed $id
     * @return number
     */
    public function removeForId($id){
        if(is_array($id)){
            $where = new Where();
            $where->in('MerchantID' , $id);
            return $this->delete($where);
        }
    
        $id = (int)$id;
        return $this->delete(array('MerchantID' => $id));
    }
    
    /**
     * 
     * @see \Custom\Db\TableGateway\TableGateway::formatWhere()
     */
    function formatWhere(array $data){
        $where = $this->getSql()->select()->where;
        $this->_getSelect();
        
        if(!empty($data['SiteId'])){
            $where->equalTo('SiteID', $data['SiteId']);
        }
        
        if(!empty($data['MerchantName'])){
            $where->like('MerchantName', '%' . $data['MerchantName'] . '%');
        }
        
        if(!empty($data['search']) && !empty($data['searchType'])){
            if('id' == $data['searchType']){
                $where->equalTo('MerchantID', (int)$data['search']);
            }elseif('name' == $data['searchType']){
                $where->like('MerchantName', '%' . $data['search'] . '%');
            }
        }
        
        if(!empty($data['CategoryID'])){
            $where->equalTo('MerchantCategory.CategoryID', $data['CategoryID']);
            $this->select->join('MerchantCategory' , 'MerchantCategory.MerchantID = Merchant.MerchantID' , array('CategoryID') , Select::JOIN_INNER);
        }
        
        if(!empty($data['AffiliateID'])){
            $where->equalTo('AffiliateID', $data['AffiliateID']);
        }
        
        if(!empty($data['IsActive'])){
            $where->equalTo('IsActive', $data['IsActive']);
        }
        
        $this->select->where($where);
        return $this;
    }
    
    /**
     * 获取商家的英文名称和中文名称
     * @param int $merid
     * @return array
     */
    public function getNameByMerID($merid) 
    {
        if (empty($merid)) {
            return array();
        }
        $where['MerchantID'] = $merid * 1;
        return $this->getInfo($where, array('MerchantName', 'MerchantNameEN' , 'MerchantAliasName'));
    }
    
    /**
     * 根据merid 获取商家信息详细信息
     * @param int $merid
     * @return Merchant信息
     */
    public function getInfoById($merid)
    {
        if (empty($merid)) {
            return array();
        }
        $where['MerchantID'] = $merid * 1;
        return $this->getInfo($where);
    }
}