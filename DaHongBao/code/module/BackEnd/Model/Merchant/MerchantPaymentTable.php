<?php
/**
* MerchantPaymentTable.php
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
* @version CVS: $Id: MerchantPaymentTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Model\Merchant;

use Custom\Db\TableGateway\TableGateway;

class MerchantPaymentTable extends TableGateway
{
    protected $table = 'MerchantPayment';
    
    /**
     * 用MerchantID查询
     * @param int $mid
     * @return array
     */
    function getPaymentByMid($mid){
        $select = $this->_getSelect();
        $select->where(array('MerchantID' => $mid));
        
        $result = array(); 
        foreach($this->selectWith($select)->toArray() as $v){
            $result[] = $v['PaymentID'];
        }
        
        return $result;
    }
    
    function save($mid , array $cid){
        $mid = (int)$mid;
        if(!$mid){
            throw new \Exception('incomplete $mid');
        }
        
        $this->removeByMid($mid);
        $this->insertByMid($mid, $cid);
        
        return true;
    }
    
    /**
     * 插入
     * @param int $mid
     * @param array $cid
     * @return boolean
     */
    function insertByMid($mid , array $cid){
        $mid = (int)$mid;
        foreach($cid as $v){
            $data = array(
                'MerchantID' => $mid,
                'PaymentID' => $v
            );
            $this->insert($data);
        }
        
        return true;
    }
    
    /**
     * 删除
     * @param int $mid
     * @throws \Exception
     * @return number
     */
    function removeByMid($mid){
        $mid = (int)$mid;
        if(!$mid){
            throw new \Exception('incomplete $mid');
        }
        return $this->delete(array('MerchantID' => $mid));
    }
}