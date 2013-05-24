<?php
/*
 * package_name : MerchantAliasTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: MerchantAliasTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\Merchant;

use Custom\Util\Utilities;

use Custom\Db\TableGateway\TableGateway;

class MerchantAliasTable extends TableGateway
{
    protected $table = 'MerchantAlias';
    
    /**
     * 根据AffiliateID获取商家列表
     * @param int $affiliateID
     * @return array|null|boolean
     */
    public function getMerListByAffID($affiliateID) 
    {
        if (empty($affiliateID)) {
            return false;
        }
        $where['AffiliateID'] = $affiliateID * 1;
        return $this->getList($where);
    }
    
   
    
    /**
     * 根据商家别名和联盟ID得到商家ID
     * @param string $merAliasName
     * @praam string $affiliateID
     * @return array
     */
    public function getInfoByName($merAliasName, $affiliateID)
    {
        if (empty($merAliasName) || empty($affiliateID)) {
            return false; 
        }
        $affiliateID = $affiliateID * 1;
        $where = array(
            'MerchantAliasName' => $merAliasName, 
            'AffiliateID' => $affiliateID
        );
        $merInfo = $this->getInfo($where);
        if (!empty($merInfo)) {
            return $merInfo['MerchantID'];
        } else {
            return false;
        }
    }
    
    /**
     * 插入
     * @param int $id
     * @param string $alias
     * @param int $affiliateID
     * @return number
     */
    public function save($id , $alias , $affiliateID = 0)
    {
        $this->insert(array('MerchantID' => $id , 'MerchantAliasName' => $alias 
            , 'AffiliateID' => $affiliateID , 'CreateDateTime' => Utilities::getDateTime()));
        
        return $this->lastInsertValue;
    }
    
}
?>