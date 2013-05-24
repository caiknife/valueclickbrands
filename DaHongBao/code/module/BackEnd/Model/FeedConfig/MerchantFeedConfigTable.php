<?php
/*
 * package_name : MerchantFeedConfigTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: MerchantFeedConfigTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\FeedConfig;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Adapter\DbSelect;

class MerchantFeedConfigTable extends TableGateway 
{
     protected $table = 'MerchantFeedConfig';
     
     /**
      * 如果存在更新数据,否则插入数据，更新数据和查询条件一起作为插入数据
      * @param array $update 需要更新的数据
      * @param int $merid 更新条件
      */
     public function replace($update = array(), $merid, $affilateID) 
     {
         if (empty($update)) {
             return false;
         }
         $where['MerchantID'] = $merid * 1;
         $where['AffiliateID'] = $affilateID * 1;
         $merInfo = $this->getInfo($where);
         //update
         if (!empty($merInfo)) {
             return $this->update($update, $where);
         } else {//insert
             return $this->insert(array_merge($update, $where));
         }
     }
     
     /**
      * feedConfig配置列表
      * @param array $order
      */
     public function getFeedConfigListToPaginator($order = array())
     {
         $select = $this->_getSelect();
         $select->columns(array($select::SQL_STAR));
         $columns = array('MerchantName', 'ID' => 'MerchantID');
         $select->join('Merchant', 'MerchantFeedConfig.MerchantID = Merchant.MerchantID', $columns, $select::JOIN_LEFT);
         if (!empty($order)) {
             $select->order($order);
         }
         
         return new DbSelect($select, $this->getAdapter());
     }
     
    function formatWhere(array $data){
        $where = $this->_getSelect()->where;
        if(!empty($data['search']) && !empty($data['searchType'])){
            if('id' == $data['searchType']){
                $where->equalTo('MerchantFeedConfig.MerchantID', (int)$data['search']);
            }elseif('name' == $data['searchType']){
                $where->like('MerchantName', '%' . $data['search'] . '%');
            }
        }
        
        if(!empty($data['AffiliateID'])){
            $where->equalTo('MerchantFeedConfig.AffiliateID', $data['AffiliateID']);
        }
        
        $this->select->where($where);
        return $this;
    }
     
     /**
      * 获取商家配置信息
      * @param int $merid
      * @return array
      */
     public function getInfoByMerID($merid) 
     {
         if (empty($merid)) {
             return array();
         }
         $where['MerchantID'] = $merid * 1;
         return $this->getInfo($where);
     }
     
     /**
      * 获取配置列表根据商家ID 和 affiliateID
      * @param int $merid
      * @param int $affid
      * @return array
      */
     public function getListByMerIDAffID($merid, $affid) 
     {
         $where['MerchantID'] = $merid * 1;
         $where['AffiliateID'] = $affid * 1;
         return $this->getInfo($where);
     }
     
     /**
      * 根据商家ID获取FEED配置信息
      * @param int $merid
      */
     public function getListByMerID($merid)
     {
         if (empty($merid)) {
             return array();
         }
         $where['MerchantID'] = $merid * 1;
         return $this->getList($where);
     }
}