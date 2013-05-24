<?php
/**
* Category.php
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
* @version CVS: $Id: Category.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Model\Category;
use Zend\InputFilter\InputFilterInterface;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;

class Category implements InputFilterAwareInterface
{
    public $CategoryID;
    public $CategoryName;
    public $CategoryEnName;
    public $CategoryUrlEnName;
    public $ParentCategoryID;
    public $IsActive;
    public $SiteID;
    public $Sequence;
    public $r_OnlineCouponCount;
    public $InsertDateTime;
    public $LastChangeDateTime;
    protected $inputFilter;
    
    function exchangeArray(array $data){
        $this->CategoryID = isset($data['CategoryID']) ? $data['CategoryID'] : '';
        $this->CategoryName = isset($data['CategoryName']) ? $data['CategoryName'] : '';
        $this->CategoryEnName = isset($data['CategoryEnName']) ? $data['CategoryEnName'] : '';
        $this->CategoryUrlEnName = isset($data['CategoryUrlEnName']) ? $data['CategoryUrlEnName'] : '';
        $this->ParentCategoryID = isset($data['ParentCategoryID']) ? $data['ParentCategoryID'] : '';
        $this->IsActive = isset($data['IsActive']) ? $data['IsActive'] : '';
        $this->SiteID = isset($data['SiteID']) ? $data['SiteID'] : '';
        $this->Sequence = isset($data['Sequence']) ? $data['Sequence'] : '';
        $this->r_OnlineCouponCount = isset($data['r_OnlineCouponCount']) ? $data['r_OnlineCouponCount'] : 0;
        $this->InsertDateTime = isset($data['InsertDateTime']) ? $data['InsertDateTime'] : '';
        $this->LastChangeDateTime = isset($data['InsertDateTime']) ? $data['InsertDateTime'] : '';
    }
    
    function toArray(){
        return array(
            'CategoryID' => $this->CategoryID,
            'CategoryName' => $this->CategoryName,
            'CategoryEnName' => $this->CategoryEnName,
            'CategoryUrlEnName' => $this->CategoryUrlEnName,
            'ParentCategoryID' => $this->ParentCategoryID,
            'IsActive' => $this->IsActive,
            'SiteID' => $this->SiteID,
            'Sequence' => $this->Sequence,
            'r_OnlineCouponCount' => $this->r_OnlineCouponCount,
            'InsertDateTime' => $this->InsertDateTime,
            'LastChangeDateTime' => $this->LastChangeDateTime
        );
    }
    
    function getArrayCopy(){
        return $this->toArray();
    }
    
    function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception('not used');
    }
    
    function getInputFilter(){
        if(!isset($this->inputFilter)){
            $inputFilter = new InputFilter();
            $inputFactory = new InputFactory();
            
            $inputFilter->add($inputFactory->createInput(array(
                'name' => 'CategoryName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array(
                    
                ),
            )));
            
            $inputFilter->add($inputFactory->createInput(array(
                'name' => 'CategoryEnName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
            
                'validators' => array(
            
                ),
            )));
            
            $inputFilter->add($inputFactory->createInput(array(
                'name' => 'CategoryUrlEnName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
            
                'validators' => array(
            
                ),
            )));
            
            $inputFilter->add($inputFactory->createInput(array(
                'name' => 'Sequence',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'Digits'
                    )
                ),
            
                'validators' => array(
            
                ),
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}