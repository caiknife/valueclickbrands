<?php
/**
* CouponForm.php
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
* @version CVS: $Id: CouponForm.php,v 1.3 2013/04/23 07:14:42 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Zend\InputFilter\Factory;

use Zend\InputFilter\InputFilter;

use Custom\Util\Utilities;

use Zend\Form\Form;

class CouponForm extends Form
{
    function __construct()
    {
        parent::__construct('coupon-form');
        
        $this->_addElement();
    }
    
    private function _addElement()
    {
        $this->add(array(
            'name' => 'CouponID',
            'options' => array(
            ),
            'attributes' => array(
                
            ),
        ));
        
        $this->add(array(
            'name' => 'UserDataFeedId',
            'options' => array(
            ),
            'attributes' => array(
                
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponCode',
            'options' => array(
                'label' => 'CouponCode'
            ),
            'attributes' => array(
                'title' => '每行为一条Coupon，Coupon格式为：code,密码,使用次数'
            ),
        ));
        
        $this->add(array(
            'name' => 'MerchantID',
            'options' => array(
                'label' => '商家ID(数字)'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'AffiliateID',
            'type' => 'Custom\Form\Element\Select',
            'options' => array(
                'label' => 'Affiliate'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponName',
            'options' => array(
                'label' => 'Coupon名'
            ),
            'attributes' => array(
        
            ),
        ));
        
        
        $this->add(array(
            'name' => 'CouponDescription',
            'options' => array(
                'label' => 'Coupon详情'
            ),
            'attributes' => array(
                'class' => 'cleditor',
            )
        ));
        
        $this->add(array(
            'name' => 'CouponRestriction',
            'options' => array(
                'label' => 'Coupon使用限制'
            ),
            'attributes' => array(
                'class' => 'cleditor',
            )
        ));
        
        $this->add(array(
            'name' => 'CouponUrl',
            'options' => array(
                'label' => 'Coupon地址'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponImageUrl',
            'type' => 'Zend\Form\Element\File',
            'required' => false,
            'options' => array(
                'label' => '图片地址(限制250KB)'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponStartDate',
            'options' => array(
                'label' => '开始时间(YY-MM-DD)'
            ),
            'attributes' => array(
                'value' => Utilities::getDateTime("Y-m-d")
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponEndDate',
            'options' => array(
                'label' => '结束时间(YY-MM-DD)'
            ),
            'attributes' => array(
                'value' => ''
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponType',
            'type' => 'Custom\Form\Element\Select',
            'options' => array(
                'label' => 'Coupon类别',
                'value_options' => array(
                    array('label' => 'COUPON' , 'value' => 'COUPON'),
                    array('label' => 'DISCOUNT' , 'value' => 'DISCOUNT'),
                    array('label' => 'ACTIVITY' , 'value' => 'ACTIVITY'),
                    array('label' => 'OTHER' , 'value' => 'OTHER'),
                )
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponAmount',
            'options' => array(
                'label' => '总额'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponReduction',
            'options' => array(
                'label' => '优惠额'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponDiscount',
            'options' => array(
                'label' => '折扣'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CouponBrandName',
            'options' => array(
                'label' => '商品名'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'IsFree',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '免费',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
                
            ),
            'attributes' => array(
                'checked' => 'checked'
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
                'checked' => 'checked'
            ),
        ));
        
        $this->add(array(
            'name' => 'IsPromote',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '推荐',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'IsAffiliateUrl',
            'type' => 'Custom\Form\Element\Checkbox',
            'options' => array(
                'label' => '不拼接链接',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
            'attributes' => array(
                
            ),
        ));
        
        $this->add(array(
            'name' => 'SiteID',
            'type' => 'Custom\Form\Element\Select',
            'options' => array(
                'label' => '站点',
                'value_options' => array(
                    array('label' => '中国' , 'value' => '1'),
                    array('label' => '美国' , 'value' => '2')
                )
            ),
            'attributes' => array(
        
            ),
        ));
        
        $this->add(array(
            'name' => 'CategoryID',
            'type' => 'Custom\Form\Element\MultiCheckbox',
            'required' => false,
            'options' => array(
                'label' => '分类',
                'value_options' => array()
            ),
            'attributes' => array(),
            'validators' => array()
        ));
        
        $this->add(array(
            'name' => 'submit',
            'options' => array(
                'label' => '提交'
            ),
        ));
    }
}