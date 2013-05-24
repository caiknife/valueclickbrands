<?php
/**
* SmcnMerchantTable.php
*-------------------------
*
* 
*
* PHP versions 5
*
* LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine 
* that helps consumers to make smarter buying decisions online. We empower consumers to compare 
* the attributes of over one million products in the common channels and common categories
* and to read user product reviews in order to make informed purchase decisions. Consumers can then 
* research the latest promotional and pricing information on products listed at a wide selection of 
* online merchants, and read user reviews on those merchants.
* The copyrights is reserved by http://www.mezimedia.com. 
* Copyright (c) 2006, Mezimedia. All rights reserved.
*
* @author Yaron Jiang <yjiang@corp.valueclick.com.cn>
* @copyright (C) 2004-2013 Mezimedia.com
* @license http://www.mezimedia.com PHP License 5.0
* @version CVS: $Id: SmcnMerchantTable.php,v 1.1 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Model\Merchant;

use Custom\Util\Utilities;

use Zend\Db\Sql\Select;

use Custom\Db\TableGateway\TableGateway;
use BackEnd\Model\Merchant\Merchant;

class SmcnMerchantTable extends TableGateway
{
    protected $table = 'Merchant';
    
    /**
     * 根据ID获取结果
     * @param int $id
     */
    function getListByID($id)
    {
        $id = (int)$id;
        if(!$id){
            return array();
        }
        $this->formatWhere(array('MerchantID' => $id));
        return $this->selectWith($this->select)->toArray();
    }
    
    /**
     * 返回单条详细
     * @param int $id
     * @return Ambigous <multitype:, mixed>
     */
    function getInfoByID($id)
    {
        $result = array();
        $info = $this->getInfo(array('MerchantID' => (int)$id));
        if($info){
            $merchant = new Merchant();
            $merchant->exchangeArray(array(
                'MerchantID' => $info['MerchantID'],
                'MerchantName' => $info['MerchantName'],
                'MerchantNameEN' => $info['MerchantNameEN'],
                'MerchantUrl' => $info['ContactCSUrl'],
                'CompanyName' => $info['CompanyName'],
                'DescriptionCN' => $info['ContactCSDescription'],
                'ContactCSEmail' => $info['ContactCSEmail'],
                'ContactCSPhone' => $info['ContactCSPhone'],
                'ContactCSAddress' => $info['ContactCSAddress'],
                'IsActive' => 'No',
                'Authorized' => $info['Authorized'],
                'SupportCN' => 'YES',
                'SupportDeliveryCN' => 'YES',
                'MainSales' => $info['SaleScope'],
                'SiteID' => 1,
                'LogoFile' => $info['BigLogoFile'],
            ));
            $result = $merchant->toArray();
        }
        
        return $result;
    }
    
    /**
     * 根据MerchantName获取结果
     * @param string $name
     */
    function getListByName($name)
    {
        $this->formatWhere(array('MerchantName' => $name));
        return $this->selectWith($this->select)->toArray();
    }
    
    function formatWhere($data)
    {
        $this->_getSelect();
        $this->select->join('MerAccount', 'MerAccount.MerchantID = Merchant.MerchantID' , array() , Select::JOIN_INNER);
        
        $where = $this->select->where;
        
        $where->equalTo('MerAccount.BidStatus', 'LIVE');
        if(!empty($data['MerchantID'])){
            $where->equalTo('Merchant.MerchantID', $data['MerchantID']);
        }
        
        if(!empty($data['MerchantName'])){
            $where->like('MerchantName', $data['MerchantName'] . '%');
        }
        
        $this->select->where($where);
        
    }
}