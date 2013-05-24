<?php
/**
* ArticleCategoryForm.php
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
* @version CVS: $Id: ArticleCategoryForm.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Form;

use Custom\Categories\AbstractContainer;

use Zend\Form\Form;

class ArticleCategoryForm extends Form
{
    function __construct(){
        parent::__construct('articleCategory-form');
        
        $this->add(array(
            'name' => 'Name',
            'options' => array(
                'label' => '分类英文名',
            ),
            'attributes' => array(
                'notemsg' => '只能使用英文'
            ),
        ));
        
        $this->add(array(
            'name' => 'CategoryID',
        ));
        
        $this->add(array(
            'name' => 'CnName',
            'options' => array(
                'label' => '分类中文名',
            )
        ));
        
        $this->add(array(
            'type' => '\Zend\Form\Element\Select',
            'name' => 'ParentID',
            'options' => array(
                'label' => '选择上级分类',
                'value_options' => array()
            ),
        ));
        
        $this->add(array(
            'name' => 'SiteID'
        ));
        
        $this->add(array(
            'name' => 'submit',
        ));
    }
    
    public function setSiteId($id){
        $this->get('SiteID')->setValue($id);
        return $this;
    }
    
    public function setParentOptions(AbstractContainer $cates , $selected = null){
        $list = new \RecursiveIteratorIterator($cates, \RecursiveIteratorIterator::SELF_FIRST);
        $re = array();
        $re[] = array(
            'label' => '无' ,
            'value' => 0,
        );
        foreach($list as $v){
            $depth = $list->getDepth();
            $myDepth = str_repeat('----', $depth);
            if($depth > 0){
                $myDepth = '└' . $myDepth;
            }
            
            $row = array(
                'label' => $myDepth . $v->CnName ,
                'value' => $v->CategoryID,
            );
            
            if($selected == $v->CategoryID){
                $row['selected'] = 'selected';
            }
            
            $re[] = $row;
        }
        
        $this->get('ParentID')->setValueOptions($re);
        
        return $this;
    }
}