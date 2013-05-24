<?php
/**
* ArticleCategory.php
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
* @version CVS: $Id: ArticleCategory.php,v 1.1 2013/04/15 10:56:26 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace CommModel\Article;

use \Zend\Stdlib\ArraySerializableInterface;
use \Zend\InputFilter\Factory as InputFactory;
use \Zend\InputFilter\InputFilter;
use \Zend\InputFilter\InputFilterAwareInterface;
use \Zend\InputFilter\InputFilterInterface;

class ArticleCategory implements InputFilterAwareInterface , ArraySerializableInterface
{
    public $CategoryID;
    public $Name;
    public $ParentID;
    public $CnName;
    public $isActive;
    public $Order;
    public $CreatDateTime;
    public $LastChangeDateTime;
    public $SiteID;
    private $inputFilter;
    
    function exchangeArray(Array $data){
        $this->CategoryID = isset($data['CategoryID'])?$data['CategoryID']:'';
        $this->Name = isset($data['Name'])?$data['Name']:'';
        $this->ParentID = isset($data['ParentID'])?$data['ParentID']:0;
        $this->CnName = isset($data['CnName'])?$data['CnName']:'';
        $this->Order = isset($data['Order'])?$data['Order'] : '';
        $this->isActive = isset($data['isActive'])?$data['isActive']:1;
        $this->SiteID = isset($data['SiteID'])?$data['SiteID']:1;
    }
    
    function getArrayCopy(){
        return array(
            'CategoryID' => $this->CategoryID,
            'Name' => $this->Name,
            'ParentID' => $this->ParentID,
            'CnName' => $this->CnName,
            'isActive' => $this->isActive,
            'SiteID' => $this->SiteID,
        );
    }
    
    function toArray(){
        return $this->getArrayCopy();
    }
    
    function setInputFilter(InputFilterInterface $inputFilter){
        throw new \Exception('no used');
    }
    
    function getInputFilter(){
        if(!$this->inputFilter){
            $inputFilter = new InputFilter();
            $inputFacory = new InputFactory();
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'Name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/[a-zA-z]+/',
                        )
                    ),
                )
            )));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'CnName',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            )));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'ParentID',
                'required' => true,
                'validators' => array(
                )
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}