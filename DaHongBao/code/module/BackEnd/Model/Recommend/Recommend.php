<?php

/**
* Recommend.php
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
* @version CVS: $Id: Recommend.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Model\Recommend;
use Zend\InputFilter\InputFilterInterface;

use Custom\Util\Utilities;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory;
class Recommend implements InputFilterAwareInterface
{
    public $RecommendID;
    public $RecommendTypeID;
    public $ContentType;
    public $ID;
    public $MerchantID;
    public $RecommendImage;
    public $RecommendOrder;
    public $Editor;
    public $InsertDateTime;
    
    private $inpuFilter;
    function exchangeArray(array $data)
    {
        $this->RecommendID = isset($data['RecommendID']) ? $data['RecommendID'] : '';
        $this->RecommendTypeID = isset($data['RecommendTypeID']) ? $data['RecommendTypeID'] : '';
        $this->ContentType = isset($data['ContentType']) ? $data['ContentType'] : '';
        $this->ID = isset($data['ID']) ? $data['ID'] : '';
        $this->MerchantID = isset($data['MerchantID']) ? $data['MerchantID'] : '';
        $this->RecommendImage = isset($data['RecommendImage']) ? $data['RecommendImage'] : '';
        $this->RecommendOrder = isset($data['RecommendOrder']) ? $data['RecommendOrder'] : '';
        $this->Editor = isset($data['Editor']) ? $data['Editor'] : '';
        $this->InsertDateTime = isset($data['InsertDateTime']) ? $data['InsertDateTime'] : Utilities::getDateTime();
    }
    function toArray()
    {
        return array(
            "RecommendID" => $this->RecommendID,
            "RecommendTypeID" => $this->RecommendTypeID,
            "ContentType" => $this->ContentType,
            "ID" => $this->ID,
            "MerchantID" => $this->MerchantID,
            "RecommendImage" => $this->RecommendImage,
            "RecommendOrder" => $this->RecommendOrder,
            "Editor" => $this->Editor,
            "InsertDateTime" => $this->InsertDateTime
        );
    }
    
    function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('');
    }
    function getInputFilter()
    {
        if(!$this->inpuFilter){
            $inputFilter = new InputFilter();
            $facory = new Factory();
            
            $inputFilter->add($facory->createInput(array(
                'name' => 'RecommendTypeID',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty'
                    )
                )
            )));
            
            $inputFilter->add($facory->createInput(array(
                'name' => 'RecommendOrder',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Digits'
                    )
                ),
            
                'validators' => array(
                    array(
                        'name' => 'Digits'
                    )
                )
            )));
            
            $this->inpuFilter = $inputFilter;
        }
        
        return $this->inpuFilter;
    }
}