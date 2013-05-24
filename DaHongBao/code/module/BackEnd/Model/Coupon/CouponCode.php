<?php
/**
* CouponCode.php
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
* @version CVS: $Id: CouponCode.php,v 1.2 2013/04/19 08:15:02 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Model\Coupon;

use Custom\Util\Utilities;

class CouponCode
{
    public $CouponCodeID;
    public $CouponID;
    public $CouponCode;
    public $CouponPass;
    public $CouponCodeTotalCnt;
    public $InsertDateTime;
    public $LastChangeDateTime;
    
    function exchangArray(array $data){
        $this->CouponCodeID = isset($data['CouponCodeID'])? $data['CouponCodeID'] : '';
        $this->CouponID = isset($data['CouponID'])? $data['CouponID'] : '';
        $this->CouponCode = isset($data['CouponCode'])? $data['CouponCode'] : '';
        $this->CouponPass = isset($data['CouponPass'])? $data['CouponPass'] : '';
        $this->CouponCodeTotalCnt = isset($data['CouponCodeTotalCnt'])? $data[''] : '';
        $this->InsertDateTime = isset($data['InsertDateTime'])? $data[''] : '';
        $this->LastChangeDateTime = isset($data['LastChangeDateTime'])? $data[''] : '';
    }
    
    function toArray(){
        return get_object_vars($this);
    }
    
    /**
     * 处理CSV格式Codes
     * @param string $data
     * @return multitype:\CouponCode
     */
    static function csvToArray($data , $couponId = null){
        $re = array();
        $data = str_getcsv($data , "\n");
        $time = Utilities::getDateTime();
        foreach($data as $v){
            $v = trim($v);
            if(empty($v)){
                continue;
            }
            $v = explode(',' , $v);
            
            $v[0] = trim($v[0]);
            if(empty($v[0])){
                continue;
            }
            
            $couponCode = new CouponCode();
            $couponCode->CouponCode =  $v[0];
            $couponCode->CouponPass = !empty($v[1])? trim($v[1]) : '';
            $couponCode->CouponCodeTotalCnt = !empty($v[2]) ? trim($v[2]) : 1;
            $couponCode->CouponID = $couponId;
            $couponCode->LastChangeDateTime = $couponCode->InsertDateTime = $time;
    
            $re[] = $couponCode->toArray();
        }
    
        return $re;
    }
    
    /**
     * Codes数组生成CSV格式
     * @param array $data
     * @return string
     */
    static function arrayToCsv(array $data){
        foreach($data as $v){
            $rows[] = sprintf("%s,%s,%d" , $v['CouponCode'] , $v['CouponPass'] , $v['CouponCodeTotalCnt']);
        }
        
        return implode("\n", $rows);
    }
}