<?php
/**
* RecommendTypeForm.php
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
* @version CVS: $Id: RecommendTypeForm.php,v 1.2 2013/04/17 06:45:05 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Custom\Form\Form;

class RecommendTypeForm extends Form
{
    function __construct(){
        parent::__construct('recommend-type-form');
        
        $this->add(array(
            'name' => 'RecommendTypeID',
            'options' => array(
            )
        ));
        
        $this->add(array(
            'name' => 'ContentType',
            'type' => 'Custom\Form\Element\Select',
            'options' => array(
                'label' => '分类',
                'value_options' => array(
                    array(
                        'label' => 'Coupon',
                        'value' => 'COUPON',
                    ),
                    array(
                        'label' => '商家',
                        'value' => 'MERCHANT',
                    ),
                    array(
                        'label' => '文章',
                        'value' => 'ARTICLE',
                    ),
                )
            )
        ));
        
        
        $this->add(array(
            'name' => 'RecommendTypeName',
            'options' => array(
                'label' => '位置名'
            )
        ));
        $this->add(array(
            'name' => 'RecommendTypeEnName',
            'options' => array(
                'label' => '位置英文名'
            )
        ));
    }
}