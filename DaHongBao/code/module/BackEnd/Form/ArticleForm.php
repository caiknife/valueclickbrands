<?php
/**
* ArticleForm.php
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
* @version CVS: $Id: ArticleForm.php,v 1.2 2013/04/18 10:19:33 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Form;

use Custom\Categories\AbstractContainer;
use Zend\Form\Form;
class ArticleForm extends Form
{
    function __construct(){
        parent::__construct('article-form');
        
        $this->add(array(
            'name' => 'ArticleID',
            'attributes' =>array(
                'value' => ''
            )
        ));
        $this->add(array(
            'name' => 'Title',
            'options' => array(
                'label' => '标题(最少4个字，最多50个字)(*)'
            ),
        ));
        $this->add(array(
            'name' => 'SiteID',
        ));
        $this->add(array(
            'name' => 'Keyword',
            'options' => array(
                'label' => '关键词(最少4个字，最多50个字)(*)'
            )
        ));
        $this->add(array(
            'name' => 'Summary',
            'options' => array(
                'label' => '简述(限制300字)',
            ),
            'attributes' => array(
                'rows' => '3',
                'cols' => '20'
            )
        ));
        $this->add(array(
            'name' => 'Detail',
            'options' => array(
                'label' => '文章内容(*)',
            ),
            'attributes' => array(
                'id' => 'editor',
            )
        ));
        $this->add(array(
            'name' => 'CategoryID',
            'type' => '\Custom\Form\Element\Select',
            'options' => array(
                'label' => '选择类别',
                'value_options' => array()
            )
        ));
        $this->add(array(
            'name' => 'State',
            'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => '文章状态',
                'value_options' => array(
                    array('label' => '发布' , 'value' => '1'),
                    array('label' => '草稿' , 'value' => '2'),
                )
            ),
        ));
        $this->add(array(
            'name' => 'Order',
            'options' => array(
                'label' => '排序(数字*)',
            )
        ));
        $this->add(array(
            'name' => 'submit'
        ));
    }
    
    function setSelectedState($selected){
        $states = $this->get('State')->getValueOptions();
        
        foreach($states as $k => $v){
            if($selected == $v['value']){
                $states[$k]['selected'] = 'selected';
                break;
            }
        }
        $this->get('State')->setValueOptions($states);
        
        return $this;
    }
    
    public function setCategoryIDOptions(AbstractContainer $cates , $selected = null){
        $list = new \RecursiveIteratorIterator($cates, \RecursiveIteratorIterator::SELF_FIRST);
        $re = array();
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
    
        $this->get('CategoryID')->setValueOptions($re);
    
        return $this;
    }
}