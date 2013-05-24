<?php
/**
* AclForm.php
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
* @version CVS: $Id: AclForm.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Form;

use Zend\Form\Form;

class AclForm extends Form
{
    function __construct(){
        parent::__construct('acl-form');
        
        $this->add(array(
            'name' => 'role',
        ));
        
        $this->add(array(
            'type' => '\Zend\Form\Element\Select',
            'name' => 'resources',
            'options' => array(
                'label' => '选择要添加的资源',
            ),
            
            'attributes' => array(
                'multiple' => 'multiple',
            )
        ));
        
        $this->add(array(
            'type' => '\Zend\Form\Element\Select',
            'name' => 'deresources',
            'options' => array(
                'label' => '选择要删除的资源'
            ),
            
            'attributes' => array(
                'multiple' => 'multiple',
            )
        ));
        
        $this->add(array(
            'name' => 'submit'
        ));
    }
    
    function setResource($resources){
        if(empty($resources)){
            return $this;
        }
        if(!is_array($resources)){
            throw new \Exception('Resource是数组');
        }
        
        $values = array();
        foreach($resources as $v){
            $values[] = array('value' => $v , 'label' => $v);
        }
        
        $this->get('resources')->setValueOptions($values);
        $this->get('resources')->setAttribute('size', 5);
        return $this;
    }
    
    function setDeResource($resources){
        if(empty($resources)){
            return $this;
        }
        if(!is_array($resources)){
            throw new \Exception('Resource是数组');
        }
        
        $values = array();
        foreach($resources as $v){
            $values[] = array('value' => $v , 'label' => $v);
        }
        $this->get('deresources')->setValueOptions($values);
        $this->get('deresources')->setAttribute('size', 5);
        return $this;
    }
}