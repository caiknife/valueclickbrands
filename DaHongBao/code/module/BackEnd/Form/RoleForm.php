<?php
/**
* RoleForm.php
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
* @version CVS: $Id: RoleForm.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Zend\Form\Form;
class RoleForm extends Form
{
    function __construct(){
        parent::__construct('role-form');
        
        $this->add(array(
            'name' => 'RoleID',
            'attributes' => array(
                'value' => '',
            )
        ));
        
        $this->add(array(
            'name' => 'Name',
            'options' => array(
                'label' => '角色名'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'ParentName',
            'options' => array(
                'label' => '父级角色'
            ),
        ));
        
        $this->add(array(
            'name' => 'submit'
        ));
    }
    
    function setParents($role , $selected = null){
        $roles = $role->toArray();
        if (empty ( $roles )) {
            return null;
        }
        
        $re = array (array('label' => '无' , 'value' => ''));
        foreach ( $roles as $role ) {
            $row = array (
                'value' => $role ['Name'],
                'label' => $role ['Name'] 
            );
            if ($selected == $role ['Name']) {
                $row ['selected'] = 'selected';
            }
            
            $re[] = $row;
        }
        
        $this->get( 'ParentName' )->setValueOptions ( $re );
    }
}