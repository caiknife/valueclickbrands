<?php

/**
* Login.php
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
* @version CVS: $Id: LoginForm.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Form;

use Zend\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form
{
    protected $inputFilter;
    
    public function __construct(){
        parent::__construct('login-form');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => '用户名'
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => '密码'
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Login',
                'type' => 'submit',
            ),
        ));
        
        $this->setInputFilter($this->getInputFilter());
    }
    
    public function getInputFilter(){
        if(!$this->inputFilter){
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'username',
                'required' => true,
                'validators' => array(
                    array('name' => 'StringLength' , 'options' => array('min' => 3 , 'max' => 20)),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'password',
                'required' => true,
                'validators' => array(
                    array('name' => 'Alnum'),
                ),
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}