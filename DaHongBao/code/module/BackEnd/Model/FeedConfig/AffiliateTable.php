<?php
/*
 * package_name : AffiliateTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: AffiliateTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\FeedConfig;

use Zend\Db\Sql\Where;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class AffiliateTable extends TableGateway 
{
    protected $table = 'Affiliate';
    
    /**
     * 根据AffilateID 获取Affiliate详细信息
     * @param int $affiliateID
     * @return affiliate信息
     */
    public function getInfoById($affiliateID)
    {
        if (empty($affiliateID)) {
            return array();
        }
        $where['ID'] = $affiliateID * 1;
        return $this->getInfo($where);
    }
    
    /**
     * 得到激活的商家列表
     * @return array
     */
    public function getActiveList() 
    {
        $where['IsActive'] = 'YES';
        return $this->getList($where);
    }
    
    /**
     * 获取自动下载在线的联盟列表
     * @return array
     */
    public function getAutoDownFeedList() 
    {
        $where['AutoDownLoad'] = 'YES';
        $where['IsActive'] = 'YES';
        return $this->getList($where);
    }
    
    /**
     * 根据ID返回SiteID
     * @param int $id
     * @return int
     */
    public function getSiteIDById($id)
    {
        $where = new Where();
        $where->equalTo('ID', $id);
        $select = $this->_getSelect();
        $select->where($where);
        $result = current($this->selectWith($select)->toArray());
        if(empty($result)){
            return 1;
        }
        return $result['SiteID'];
    }
    /**
     * 设置条件
     * @see \Custom\Db\TableGateway\TableGateway::formatWhere()
     */
    function formatWhere(array $data = null)
    {
        $select = $this->_getSelect();
        $this->select->reset(Select::WHERE);
        $where = $select->where;
        
        $data['IsActive'] = empty($data['IsActive'])? 'YES' : $data['IsActive'];
        $where->equalTo('IsActive', $data['IsActive']);
        
        if(isset($data['SiteId'])){
            $where->equalTo('SiteID', $data['SiteId']);
        }
        
        
        $this->select->where($where);
        return $this;
   }
}
?>