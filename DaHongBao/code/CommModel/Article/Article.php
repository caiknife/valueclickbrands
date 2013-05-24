<?php
/**
* Article.php
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
* @version CVS: $Id: Article.php,v 1.2 2013/04/18 10:19:33 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace CommModel\Article;
use \Zend\Stdlib\ArraySerializableInterface;
use \Zend\InputFilter\Factory as InputFactory;
use \Zend\InputFilter\InputFilter;
use \Zend\InputFilter\InputFilterAwareInterface;
use \Zend\InputFilter\InputFilterInterface;
class Article implements InputFilterAwareInterface, ArraySerializableInterface
{
    public $ArticleID;
    public $Title;
    public $SiteID;
    public $Keyword;
    public $Summary;
    public $Detail;
    public $AuthorID;
    public $CategoryID;
    public $CreatDateTime;
    public $LastChangeDateTime;
    public $State;
    public $Order;
    private $inputFilter;
    function exchangeArray(array $data)
    {
        $this->ArticleID = isset($data['ArticleID']) ? $data['ArticleID'] : '';
        $this->Title = isset($data['Title']) ? $data['Title'] : '';
        $this->SiteID = isset($data['SiteID']) ? $data['SiteID'] : '';
        $this->Keyword = isset($data['Keyword']) ? $data['Keyword'] : '';
        $this->Summary = isset($data['Summary']) ? $data['Summary'] : '';
        $this->Detail = isset($data['Detail']) ? $data['Detail'] : '';
        $this->AuthorID = isset($data['AuthorID']) ? $data['AuthorID'] : '';
        $this->CategoryID = isset($data['CategoryID']) ? $data['CategoryID'] : '';
        $this->CreatDateTime = isset($data['CreatDateTime']) ? $data['CreatDateTime'] : '';
        $this->LastChangeDateTime = isset($data['LastChangeDateTime']) ? $data['LastChangeDateTime'] : '';
        $this->State = isset($data['State']) ? $data['State'] : '2';
        $this->Order = isset($data['Order']) ? $data['Order'] : 0;
    }
    function toArray()
    {
        return array(
            'ArticleID' => $this->ArticleID,
            'Title' => $this->Title,
            'SiteID' => $this->SiteID,
            'Keyword' => $this->Keyword,
            'Summary' => $this->Summary,
            'Detail' => $this->Detail,
            'AuthorID' => $this->AuthorID,
            'CategoryID' => $this->CategoryID,
            'CreatDateTime' => $this->CreatDateTime,
            'LastChangeDateTime' => $this->LastChangeDateTime,
            'State' => $this->State,
            'Order' => $this->Order
        );
    }
    function getArrayCopy()
    {
        return $this->toArray();
    }
    function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('not used');
    }
    function getInputFilter()
    {
        if(! $this->inputFilter){
            $inputFilter = new InputFilter();
            $inputFacory = new InputFactory();
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'Title',
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
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => '4',
                            'max' => '150'
                        )
                    )
                )
            )
            ));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'CategoryID',
                'required' => true,
                'filters' => array(),
                'validators' => array()
            )
            ));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'SiteID',
                'required' => true,
                'filters' => array(),
                'validators' => array()
            )
            ));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'State',
                'required' => true,
                'filters' => array(),
                'validators' => array()
            )));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'Keyword',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'HtmlEntities'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => '4',
                            'max' => '150'
                        )
                    )
                )
            )
            ));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'Summary',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => '4',
                            'max' => '900'
                        )
                    )
                )
            )
            ));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'Detail',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/[^(<script)]/'
                        )
                    )
                )
            )
            ));
            
            $inputFilter->add($inputFacory->createInput(array(
                'name' => 'Order',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Digits'
                    )
                ),
                'validators' => array(
                )
            )
            ));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}