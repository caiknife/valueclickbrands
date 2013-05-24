<?php
/**
* CategoryForm.php
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
* @version CVS: $Id: CategoryForm.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Zend\Form\Form;

class CategoryForm extends Form
{
    function __construct(){
        parent::__construct('category-form');
        
        $this->_addElement();
    }
    
    private function _addElement(){
        $this->add(array(
            'name' => 'CategoryID',
        ));
        
        $this->add(array(
            'name' => 'CategoryName',
            'options' => array(
                'label' => '类别名'
            ),
            
            'attributes' => array(
                
            )
        ));
        
        $this->add(array(
            'name' => 'CategoryEnName',
            'options' => array(
                'label' => 'CategoryEnName'
            ),
        
            'attributes' => array(
        
            )
        ));
        
        $this->add(array(
            'name' => 'CategoryUrlEnName',
            'options' => array(
                'label' => 'CategoryUrlEnName'
            ),
        
            'attributes' => array(
        
            )
        ));
        
        $this->add(array(
            'name' => 'IsActive',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => '上线',
                'checked_value' => 'YES',
                'unchecked_value' => 'NO'
            ),
        
            'attributes' => array(
             
            )
        ));
        
        $this->add(array(
            'name' => 'SiteID',
            'options' => array(
        
            ),
        
            'attributes' => array(
        
            )
        ));
        
        $this->add(array(
            'name' => 'Sequence',
            'options' => array(
                'label' => '排序'
            ),
        
            'attributes' => array(
        
            )
        ));
        
        $this->add(array(
            'name' => 'submit'
        ));
    }
}