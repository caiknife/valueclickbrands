<?php

/**
* UserForm.php
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
* @author Yaron Jiang<yjiang@corp.valueclick.com>
* @copyright (C) 2004-2013 Mezimedia.com
* @license http://www.mezimedia.com PHP License 5.3
* @version CVS: $Id: UserForm.php,v 1.2 2013/04/15 13:51:57 yjiang Exp $
* @link http://www.dahongbao.com
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    function __construct() {
        parent::__construct ( 'user-form' );
        $this->setAttribute ( 'method', 'post' );
        
        $this->add ( array (
            'name' => 'Name',
            'options' => array (
                'label' => '姓名' 
            ) 
        ) );
        
        $this->add ( array (
            'name' => 'Mail',
            'options' => array (
                'label' => '电子邮箱' 
            ) 
        ) );
        
        $this->add ( array (
            'name' => 'Remark',
            'options' => array (
                'label' => '职位' 
            ) 
        ) );
        
        $this->add ( array (
            'type' => 'Zend\Form\Element\Select',
            'name' => 'DahongbaoRole',
            'options' => array (
                'label' => '角色' 
            ) 
        ) );
        
        $this->add ( array (
            'name' => 'submit',
            'attributes' => array (
                'value' => '提交',
                'type' => 'submit',
             )
        ) );
        
        $this->add(array(
            'name' => 'UserID',
            'attributes' => array(
                'value' => ''
             )
        ));
    }
    
    function setRole($roles, $selected = null) {
        $roles = $roles->toArray ();
        if (empty ( $roles )) {
            return null;
        }
        
        $re = array ();
        foreach ( $roles as $role ) {
            $row = array (
                'value' => $role ['Name'],
                'label' => $role ['Name'] 
            );
            if ($selected == $role ['Name']) {
                $row ['selected'] = 'selected';
            }
            
            $re [] = $row;
        }
        
        $this->get ( 'DahongbaoRole' )->setValueOptions ( $re );
    }
}