<?php
/**
* RecommendType.php
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
* @version CVS: $Id: RecommendType.php,v 1.2 2013/04/17 06:45:05 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Model\Recommend;
use Zend\InputFilter\Factory;

use Zend\InputFilter\InputFilter;

use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
class RecommendType implements InputFilterAwareInterface
{
    public $RecommendTypeID;
    public $RecommendTypeName;
    public $RecommendTypeEnName;
    public $SiteID;
    public $ContentType;
    private $inputFilter;
    
    function exchangeArray(array $data)
    {
        $this->RecommendTypeID = isset($data['RecommendTypeID']) ? $data['RecommendTypeID'] : '';
        $this->RecommendTypeName = isset($data['RecommendTypeName']) ? $data['RecommendTypeName'] : '';
        $this->SiteID = isset($data['SiteID']) ? $data['SiteID'] : '';
        $this->ContentType = isset($data['ContentType']) ? $data['ContentType'] : '';
        $this->RecommendTypeEnName = isset($data['RecommendTypeEnName']) ? $data['RecommendTypeEnName'] : '';
    }
    function toArray()
    {
        return array(
            'RecommendTypeID' => $this->RecommendTypeID,
            'RecommendTypeName' => $this->RecommendTypeName,
            'SiteID' => $this->SiteID,
            'ContentType' => $this->ContentType,
            'RecommendTypeEnName' => $this->RecommendTypeEnName
        );
    }
    function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('not used');
    }
    
    function getInputFilter(){
        if(!$this->inputFilter){
            $inputFilter = new InputFilter();
            $facory = new Factory();
            
            $inputFilter->add($facory->createInput(array(
                'name' => 'RecommendTypeName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array()
            )));
            
            $inputFilter->add($facory->createInput(array(
                'name' => 'RecommendTypeEnName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
            
                'validators' => array()
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}