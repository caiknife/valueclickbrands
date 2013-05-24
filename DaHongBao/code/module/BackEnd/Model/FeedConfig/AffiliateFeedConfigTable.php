<?php
/*
 * package_name : AffiliateFeedConfig.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: AffiliateFeedConfigTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\FeedConfig;

use Custom\Db\TableGateway\TableGateway;

class AffiliateFeedConfigTable extends TableGateway
{
     protected $table = 'AffiliateFeedConfig';
     
     /**
      * 如果存在更新数据,否则插入数据，更新数据和查询条件一起作为插入数据
      * @param array $update 需要更新的数据
      * @param int $merid 更新条件
      */
     public function replace($update = array(), $affiliateID) 
     {
         if (empty($update)) {
             return false;
         }
         
         $where['AffiliateID'] = $affiliateID * 1;
         $affiliateInfo = $this->getInfoByID($where['AffiliateID']);
         //update
         if (!empty($affiliateInfo)) {
             return $this->update($update, $where);
         } else {//insert
             return $this->insert(array_merge($update, $where));
         }
     }
     
     /**
      * 根据AffliateID 获取相关配置
      * @param int $affiliateID
      * @return array
      */
     public function getInfoByID($affiliateID)
     {
         if (empty($affiliateID)) {
             return array();
         }
         $where['AffiliateID'] = $affiliateID * 1;
         return $this->getInfo($where);
     }
}