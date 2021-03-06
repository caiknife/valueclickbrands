<?php
/**
* AbstractContainer.php
*-------------------------
*
* 分类容器抽象类
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
* @version CVS: $Id: AbstractContainer.php,v 1.1 2013/04/15 10:56:30 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace Custom\Categories;

use Custom\Categories\Category\AbstractCategory;

abstract class AbstractContainer implements \RecursiveIterator , \Countable
{
    protected $cates = array();
    protected $index = array();
    protected $dirtyIndex = false;
    
    public function getCates(){
        return $this->cates;
    }
    protected function sort()
    {
        if (!$this->dirtyIndex) {
            return;
        }
    
        $newIndex = array();
        $index    = 0;
    
        foreach ($this->cates as $key => $v) {
            $order = $v->getOrder();
            if ($order === null) {
                $newIndex[$key] = $index;
                $index++;
            } else {
                $newIndex[$key] = $order;
            }
        }
    
        asort($newIndex);
        $this->index      = $newIndex;
        $this->dirtyIndex = false;
    }
    
    public function notifyOrderUpdated()
    {
        $this->dirtyIndex = true;
    }
    
    /**
     * 添加分类
     * @param AbstractCategory $cate
     * @return \Custom\Categories\AbstractContainer
     */
    public function addCate(AbstractCategory $cate){
        $id = $cate->getId();
        if(!empty($this->index) && array_key_exists($id, $this->index)){
            return $this;
        }
        
        $this->cates[$id] = $cate;
        $this->index[$id] = $cate->getOrder();
        $this->dirtyIndex = true;
        
        $cate->setParent($this);
        
        return $this;
    }
    
    /**
     * 添加多个分类
     * @param mixed $cates
     * @throws \Exception
     * @return \Custom\Categories\AbstractContainer
     */
    public function addCates($cates){
        if(!is_array($cates) && !$cates instanceof Traversable){
            throw new \Exception('Invalid argument: $cates must be an array, an '
                . 'instance of Traversable or an instance of '
                . 'AbstractContainer');
        }
        
        if($cates instanceof Traversable){
            $cates = iterator_to_array($cates);
        }
        
        foreach($cates as $cate){
            $this->addCate($cate);
        }
        
        return $this;
    }
    
    /**
     * 删除分类
     * @param AbstractCategory $cate
     * @return boolean
     */
    public function removeCate(AbstractCategory $cate){
        $id = $cate->getId();
        if(isset($this->index[$id])){
            unset($this->index[$id]);
            unset($this->cates[$id]);
            $this->dirtyIndex = true;
            
            return true;
        }
        
        return false;
    }
    
    /**
     * 全部删除
     * @return \Custom\Categories\AbstractContainer
     */
    public function removeCates(){
        $this->index = array();
        $this->cates = array();
        return $this;
    }
    
    /**
     * 判断是否有这个分类
     * @param AbstractCategory $cate
     * @param bool $recursive     是否递归查询
     * @return boolean
     */
    public function hasCate(AbstractCategory $cate , $recursive = null){
        if(array_key_exists($cate->getId(), $this->cates)){
            return true;
        }elseif($recursive){
            foreach($this->cates as $childer){
                if(array_key_exists($cate->getId(), $childer)){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 按属性查找分类
     * @param string $property
     * @param string $value
     * @return \Custom\Categories\Category\AbstractCategory|NULL
     */
    public function findOneBy($property, $value){
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
    
        foreach ($iterator as $cate) {
            if ($cate->get($property) == $value) {
                return $cate;
            }
        }
    
        return null;
    }
    
    /**
     * 按属性查找分类
     * @param string $property
     * @param string $value
     * @return array
     */
    public function findAllBy($property, $value){
        $found = array();
    
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
    
        foreach ($iterator as $cate) {
            if ($cate->get($property) == $value) {
                $found[] = $cate;
            }
        }
    
        return $found;
    }
    
    public function hasChildren(){
        return count($this->index) > 0;
    }
    
    public function getChildren(){
        $key = key($this->index);
        if(isset($this->cates[$key])){
            return $this->cates[$key];
        }
        
        return null;
    }
    
    public function key(){
        $this->sort();
        return key($this->index);
    }
    
    public function next(){
        $this->sort();
        next($this->index);
    }
    
    public function rewind(){
        $this->sort();
        reset($this->index);
    }
    
    public function current(){
        $this->sort();
        current($this->index);

        $key = key($this->index);
        if(!isset($this->cates[$key])){
            throw new \Exception('error key : ' . var_export($key));
        }
        
        return $this->cates[$key];
    }
    
    public function valid(){
        $this->sort();
        return current($this->index) !== false;
    }
    
    public function count(){
        return count($this->index);
    }
}