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
 * @Version  : CVS: $Id: MerchantTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\Cmus;

use Custom\Db\TableGateway\TableGateway;

class MerchantTable extends TableGateway
{
    /**
     * 表名称
     * @var string 
     */
    protected $table = 'Merchant';
    
    /**
     * 缓存MerchantList
     * @var array
     */
    public $merchantList = array(); 
    
    /**
     * 获取Merchant 信息
     * @param int $merchantID
     * @param array $columns
     * @return array
     */
    public function getMerchantByID($merchantID, $columns = array()) 
    {
        if (empty($merchantID)) {
            return array();
        }
        if (empty($this->merchantList)) {
            $this->merchantList = $this->fetchAll($columns);
        }
        $merchantID = $merchantID * 1;
        return $this->merchantList[$merchantID];
    }
    
    /**
     * 获取Merchant列表
     * @param array $column
     * @return array $merchantList
     */
    public function fetchAll($columns = array()) 
    {
        $this->merchantList = array();
        $where['isActive'] = '1';
        foreach ($this->getList($where, $columns) as $merchant) {
            $this->merchantList[$merchant['Merchant_']] = $merchant;
        }
        return $this->merchantList;
    }
}