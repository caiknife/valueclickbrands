<?php
/**
* Form.php
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
* @version CVS: $Id: Form.php,v 1.1 2013/04/15 10:56:30 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace Custom\Form;

use Zend\Form\Form as Father;

class Form extends Father
{
    function __construct($name = null){
        parent::__construct($name);
        
        $this->add(array(
            'name' => 'SiteID',
            'type' => 'Custom\Form\Element\Select',
            'options' => array(
                'label' => '站点',
                'value_options' => array(
                    array(
                        'label' => '中国',
                        'value' => 1,
                    ),
                    
                    array(
                        'label' => '美国',
                        'value' => 2,
                    ),
                )
            )
        ));
        
        
        $this->add(array(
            'name' => 'submit'
        ));
    }
}