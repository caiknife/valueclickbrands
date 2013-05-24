<?php
/**
* RecommendForm.php
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
* @version CVS: $Id: RecommendForm.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Custom\Form\Form;

class RecommendForm extends Form
{
    function __construct(){
        parent::__construct('recommend-form');
        
        $this->add(array(
            'name' => 'RecommendID',
            'options' => array(
            )
        ));
        $this->add(array(
            'name' => 'ContentType',
            'options' => array(
            )
        ));
        $this->add(array(
            'name' => 'RecommendTypeID',
            'type' => 'Custom\Form\Element\MultiCheckbox',
            'options' => array(
                'label' => '位置'
            )
        ));
        $this->add(array(
            'name' => 'ID',
            'options' => array(
            )
        ));
        
        $this->add(array(
            'name' => 'MerchantID',
            'options' => array(
            )
        ));
        
        $this->add(array(
            'name' => 'RecommendImage',
            'type' => 'Zend\Form\Element\File',
            'options' => array(
                'label' => '图片(限250KB)'
            )
        ));
        
        $this->add(array(
            'name' => 'RecommendOrder',
            'options' => array(
                'label' => '排序'
            ),
            'attributes' => array(
                'value' => 0
            ),
        ));
        
        
    }
}