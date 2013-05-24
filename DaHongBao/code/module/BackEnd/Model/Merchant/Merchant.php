<?php
/**
* Merchant.php
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
* @version CVS: $Id: Merchant.php,v 1.2 2013/04/26 04:05:26 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Model\Merchant;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
class Merchant implements InputFilterAwareInterface
{
    public $MerchantID;
    public $MerchantName;
    public $MerchantNameEN;
    public $MerchantUrl;
    public $CompanyName;
    public $DescriptionCN;
    public $DescriptionEn;
    public $LogoFile;
    public $ContactCSEmail;
    public $ContactCSPhone;
    public $ContactCSAddress;
    public $IsActive;
    public $Authorized;
    public $SupportCN;
    public $SupportDeliveryCN;
    public $MainSales;
    public $SiteID;
    public $AffiliateID;
    public $RegisterDateTime;
    public $LastChangeDateTime;
    public $Sequence;
    public $Instructions;
    public $AffiliateUrl;
    public $MerchantAliasName;
    
    private $inputFilter;
    function exchangeArray(array $data)
    {
        $this->MerchantID = isset($data['MerchantID']) ? $data['MerchantID'] : '';
        $this->MerchantName = isset($data['MerchantName']) ? $data['MerchantName'] : '';
        $this->MerchantNameEN = isset($data['MerchantNameEN']) ? $data['MerchantNameEN'] : '';
        $this->MerchantUrl = isset($data['MerchantUrl']) ? $data['MerchantUrl'] : '';
        $this->CompanyName = isset($data['CompanyName']) ? $data['CompanyName'] : '';
        $this->DescriptionCN = isset($data['DescriptionCN']) ? $data['DescriptionCN'] : '';
        $this->DescriptionEn = isset($data['DescriptionEn']) ? $data['DescriptionEn'] : '';
        $this->LogoFile = isset($data['LogoFile']) ? $data['LogoFile'] : '';
        $this->ContactCSEmail = isset($data['ContactCSEmail']) ? $data['ContactCSEmail'] : '';
        $this->ContactCSPhone = isset($data['ContactCSPhone']) ? $data['ContactCSPhone'] : '';
        $this->ContactCSAddress = isset($data['ContactCSAddress']) ? $data['ContactCSAddress'] : '';
        $this->IsActive = isset($data['IsActive']) ? $data['IsActive'] : '';
        $this->Authorized = isset($data['Authorized']) ? $data['Authorized'] : '';
        $this->SupportCN = isset($data['SupportCN']) ? $data['SupportCN'] : '';
        $this->SupportDeliveryCN = isset($data['SupportDeliveryCN']) ? $data['SupportDeliveryCN'] : '';
        $this->MainSales = isset($data['MainSales']) ? $data['MainSales'] : '';
        $this->SiteID = isset($data['SiteID']) ? $data['SiteID'] : '';
        $this->AffiliateID = isset($data['AffiliateID']) ? $data['AffiliateID'] : '';
        $this->RegisterDateTime = isset($data['RegisterDateTime']) ? $data['RegisterDateTime'] : '';
        $this->LastChangeDateTime = isset($data['LastChangeDateTime']) ? $data['LastChangeDateTime'] : '';
        $this->Sequence = isset($data['Sequence']) ? $data['Sequence'] : 0;
        $this->Instructions = isset($data['Instructions']) ? $data['Instructions'] : '';
        $this->AffiliateUrl = isset($data['AffiliateUrl']) ? $data['AffiliateUrl'] : '';
        $this->MerchantAliasName = isset($data['MerchantAliasName']) ? $data['MerchantAliasName'] 
        : $this->MerchantName;
    }
    function toArray()
    {
        return array(
            'MerchantID' => $this->MerchantID,
            'MerchantName' => $this->MerchantName,
            'MerchantNameEN' => $this->MerchantNameEN,
            'MerchantUrl' => $this->MerchantUrl,
            'CompanyName' => $this->CompanyName,
            'DescriptionCN' => $this->DescriptionCN,
            'DescriptionEn' => $this->DescriptionEn,
            'LogoFile' => $this->LogoFile,
            'ContactCSEmail' => $this->ContactCSEmail,
            'ContactCSPhone' => $this->ContactCSPhone,
            'ContactCSAddress' => $this->ContactCSAddress,
            'IsActive' => $this->IsActive,
            'Authorized' => $this->Authorized,
            'SupportCN' => $this->SupportCN,
            'SupportDeliveryCN' => $this->SupportDeliveryCN,
            'MainSales' => $this->MainSales,
            'SiteID' => $this->SiteID,
            'AffiliateID' => $this->AffiliateID,
            'RegisterDateTime' => $this->RegisterDateTime,
            'LastChangeDateTime' => $this->LastChangeDateTime,
            'Sequence' => $this->Sequence,
            'Instructions' => $this->Instructions,
            'AffiliateUrl' => $this->AffiliateUrl,
            'MerchantAliasName' => $this->MerchantAliasName
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
        if(! isset($inputFilter)){
            $inputFilter = new InputFilter();
            $inputFacoty = new InputFactory();
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'MerchantName',
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

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'MerchantNameEN',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array()

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'MerchantUrl',
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

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'CompanyName',
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

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'DescriptionCN',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array()

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'DescriptionEn',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array()

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'ContactCSEmail',
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
                        'name' => 'EmailAddress'
                    )
                )
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'ContactCSPhone',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array()

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'ContactCSAddress',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                
                'validators' => array()

                
            )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
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
                
                'validators' => array()
                )
            ));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'CategoryID',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty'
                    )
                )
            )));
            
            $inputFilter->add($inputFacoty->createInput(array(
                'name' => 'MerchantAliasName',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                )
            )));
            
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}