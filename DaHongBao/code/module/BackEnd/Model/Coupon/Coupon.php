<?php
/**
* Coupon.php
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
* @version CVS: $Id: Coupon.php,v 1.6 2013/04/26 09:00:21 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Model\Coupon;


use Custom\Util\Utilities;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;

use Zend\InputFilter\InputFilterAwareInterface;

class Coupon implements InputFilterAwareInterface
{
    public $CouponID;
    public $MerchantID;
    public $AffiliateID;
    public $CouponName;
    public $CouponDescription;
    public $CouponRestriction;
    public $CouponUrl;
    public $CouponImageUrl;
    public $CouponStartDate;
    public $CouponEndDate;
    public $CouponType;
    public $CouponAmount;
    public $CouponReduction;
    public $CouponDiscount;
    public $CouponBrandName;
    public $SKU;
    public $IsFree;
    public $IsFromCmus;
    public $IsActive;
    public $IsPromote;
    public $SiteID;
    public $IsAffiliateUrl;

    function exchangeArray($data)
    {
        $this->CouponID = isset($data['CouponID']) ? $data['CouponID'] : '';
        $this->MerchantID = isset($data['MerchantID']) ? $data['MerchantID'] : '';
        $this->AffiliateID = isset($data['AffiliateID']) ? $data['AffiliateID'] : '';
        $this->CouponName = isset($data['CouponName']) ? $data['CouponName'] : '';
        $this->CouponDescription = isset($data['CouponDescription']) ? $data['CouponDescription'] : '';
        $this->CouponRestriction = isset($data['CouponRestriction']) ? $data['CouponRestriction'] : '';
        $this->CouponUrl = isset($data['CouponUrl']) ? $data['CouponUrl'] : '';
        $this->CouponImageUrl = isset($data['CouponImageUrl']) ? $data['CouponImageUrl'] : '';
        $this->CouponStartDate = !empty($data['CouponStartDate']) ? $data['CouponStartDate'] : 
        Utilities::getDateTime("Y-m-d");
        $this->CouponEndDate = !empty($data['CouponEndDate']) ? $data['CouponEndDate'] : '3333-03-03 00:00:00';
        $this->CouponType = isset($data['CouponType']) ? $data['CouponType'] : '';
        $this->CouponAmount = isset($data['CouponAmount']) ? $data['CouponAmount'] : '';
        $this->CouponReduction = isset($data['CouponReduction']) ? $data['CouponReduction'] : '';
        $this->CouponDiscount = isset($data['CouponDiscount']) ? $data['CouponDiscount'] : '';
        $this->CouponBrandName = isset($data['CouponBrandName']) ? $data['CouponBrandName'] : '';
        $this->SKU = isset($data['SKU']) ? $data['SKU'] : '';
        $this->IsFree = isset($data['IsFree']) ? $data['IsFree'] : '';
        $this->IsFromCmus = isset($data['IsFromCmus']) ? $data['IsFromCmus'] : '';
        $this->IsActive = isset($data['IsActive']) ? $data['IsActive'] : '';
        $this->IsPromote = isset($data['IsPromote']) ? $data['IsPromote'] : '';
        $this->SiteID = isset($data['SiteID']) ? $data['SiteID'] : '';
        $this->IsAffiliateUrl = isset($data['IsAffiliateUrl']) ? $data['IsAffiliateUrl'] : "";
    }
    
    function toArray()
    {
        return get_object_vars($this);
    }
    
    function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception('not used');
    }
    
    function getInputFilter(){
        $filter = new InputFilter();
        $factory = new Factory();
        
        $filter->add($factory->createInput(array(
            'name' => 'CategoryID',
            'validators' => array(
                array(
                    'name' => 'NotEmpty'
                ),
            )
        )));
        
        $filter->add($factory->createInput(array(
            'name' => 'MerchantID',
            'validators' => array(
                array(
                    'name' => 'NotEmpty'
                ),
            )
        )));
        

        $filter->add($factory->createInput(array(
            'name' => 'CouponName',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty'
                )
            )
        )));
        
        $filter->add($factory->createInput(array(
            'name' => 'CouponStartDate',
            'required' => true,
            'filters' => array(
        
            ),
            'validators' => array(
                array(
                    'name' =>'Between',
                    'options' => array(
                        'inclusive' => true,
                        'max' => '3333-03-03 00:00:00',
                        'messages' => array(
                            'notBetween' => '超出最大日期:3333-03-03 00:00:00'
                        ),
                    )
                ),
                
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^20\d\d-[0-1]\d-[0-3]\d( [0-2]\d:[0-6]\d:[0-6]\d)?$/',
                        'messages' => array(
                            'regexNotMatch' => '日期格式不正确'
                        ),
                    )
                )
            )
        )));
        
        $filter->add($factory->createInput(array(
            'name' => 'CouponEndDate',
            'required' => false,
            'filters' => array(
        
            ),
            'validators' => array(
                array(
                    'name' =>'Between',
                    'options' => array(
                        'inclusive' => true,
                        'max' => '3333-03-03 00:00:00',
                        'messages' => array(
                            'notBetween' => '超出最大日期:3333-03-03 00:00:00'
                        ),
                    )
                ),
                
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^\d{4}-[0-1]\d-[0-3]\d( [0-2]\d:[0-6]\d:[0-6]\d)?$/',
                        'messages' => array(
                            'regexNotMatch' => '日期格式不正确'
                        ),
                    )
                )
            )
        )));
        
        return $filter;
    }
}