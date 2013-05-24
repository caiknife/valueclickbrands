<?php
/**
* MerchantForm.php
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
* @version CVS: $Id: MerchantForm.php,v 1.3 2013/04/26 08:25:15 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Zend\Form\Form;

class MerchantForm extends Form
{
    function __construct(){
        parent::__construct('merchant-form');
        
        $this->_addElement();
    }
    
    private function _addElement(){
        $this->add(array(
            'name' => 'Sequence',
            'options' => array(
                'label' => '排序'
            ),
            'attrbutes' => array(),
        ));
        
        $this->add(array(
            'name' => 'MerchantID',
            'attributes' => array(
                'value' => '',
            )
        ));
        
        $this->add(array(
            'name' => 'MerchantName',
            'options' => array(
                'label' => '商家名'
            ),
        ));
        
        $this->add(array(
            'name' => 'MerchantNameEN',
            'options' => array(
                'label' => '商家英文名'
            ),
        ));
        
        $this->add(array(
            'name' => 'MerchantUrl',
            'options' => array(
                'label' => '商家URL'
            ),
        ));
        
        $this->add(array(
            'name' => 'AffiliateUrl',
            'options' => array(
                'label' => '联盟URL'
            ),
        ));
        
        $this->add(array(
            'name' => 'CompanyName',
            'options' => array(
                'label' => '公司名'
            ),
        ));
        
        $this->add(array(
            'name' => 'DescriptionCN',
            'options' => array(
                'label' => '公司简述'
            ),
            'attributes' => array(
                'class' => 'cleditor',
            )
        ));
        
        $this->add(array(
            'name' => 'Instructions',
            'options' => array(
                'label' => '使用说明'
            ),
            'attributes' => array(
                'class' => 'cleditor',
            )
        ));
        
        $this->add(array(
            'name' => 'DescriptionEn',
            'options' => array(
                'label' => 'Company Introduction'
            ),
            'attributes' => array(
                'class' => 'cleditor',
            )
        ));
        
        $this->add(array(
            'name' => 'LogoFile',
            'type' => 'Zend\Form\Element\File',
            'required' => false,
            'options' => array(
                'label' => '商家Logo'
            ),
        ));
        
        $this->add(array(
            'name' => 'ContactCSEmail',
            'options' => array(
                'label' => '商家电子邮件'
            ),
        ));
        
        $this->add(array(
            'name' => 'ContactCSPhone',
            'options' => array(
                'label' => '商家电话'
            ),
        ));
        
        $this->add(array(
            'name' => 'ContactCSAddress',
            'options' => array(
                'label' => '商家地址'
            ),
        ));
        
        $this->add(array(
            'name' => 'IsActive',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '上线',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
            'attributes' => array(
                'checked'=>'checked'
            ),
        ));
        
        $this->add(array(
            'name' => 'Authorized',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '认证',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
            'attributes' => array(
                'checked'=>'checked'
            ),
        ));
        
        $this->add(array(
            'name' => 'SupportCN',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '支持中文',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
            
        ));
        
        $this->add(array(
            'name' => 'SupportDeliveryCN',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '支持直邮',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
        ));
        
        $this->add(array(
            'name' => 'MainSales',
            'options' => array(
                'label' => '主营业务'
            ),
            'attributes' => array(
                'class' => 'cleditor',
            )
        ));
        
        $this->add(array(
            'name' => 'SiteID',
        ));
        
        $this->add(array(
            'name' => 'AffiliateID',
            'type' => 'Custom\Form\Element\Select',
            'options' => array(
                'label' => '联盟'
            ),
        ));
        
        
        $this->add(array(
            'name' => 'CategoryID',
            'type' => 'Custom\Form\Element\MultiCheckbox',
            'options' => array(
                'label' => '分类',
                'value_options' => array()
            ),
        ));
        
        $this->add(array(
            'name' => 'ShippingID',
            'type' => 'Custom\Form\Element\MultiCheckbox',
            'required' => false,
            'options' => array(
                'label' => '配送方式',
                'value_options' => array()
            ),
        ));
        
        $this->add(array(
            'name' => 'PaymentID',
            'type' => 'Custom\Form\Element\MultiCheckbox',
            'required' => false,
            'options' => array(
                'label' => '支付方式',
                'value_options' => array()
            ),
        ));
        
        $this->add(array(
            'name' => 'submit'
        ));
        
        $this->add(array(
            'name' => 'MerchantAliasName',
            'required' => false,
            'options' => array(
                'label' => '商家别名（半角分号分隔）',
                
            )
        ));
    }
    
}