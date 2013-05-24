<?php
/**
* CategoryController.php
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
* @version CVS: $Id: CategoryController.php,v 1.2 2013/04/17 08:57:02 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use BackEnd\Model\Category\Category;

use BackEnd\Form\CategoryForm;

use BackEnd\Model\Category\CategoryTable;

use Custom\Mvc\Controller\AbstractActionController;

class CategoryController extends AbstractActionController
{
    function indexAction(){
        $params = $this->params()->fromQuery();
        $siteId = $params['SiteId'] = !empty($params['SiteId'])? $params['SiteId'] : 1;
        $params['IsActive'] = !empty($params['IsActive']) ? $params['IsActive'] : 'YES';
        $re = $params;
        
        $re['cateList'] = $this->_getCategoryList($params);
        
        
        $re['query'] = http_build_query($params);
        $re['sites'] = $this->sites;
        
        return $re;
    }
    
    function saveAction(){
        $request = $this->getRequest();
        $params = $this->params()->fromQuery();
        $query = http_build_query($params);
        $siteId = $params['SiteId'] = !empty($params['SiteId'])? $params['SiteId'] : 1;
        $form = new CategoryForm();
        $category = new Category(); 
        
        if($request->isPost()){
            $params = $this->params()->fromPost();
            $form->setData($params);
            $form->setInputFilter($category->getInputFilter());
            
            if($form->isValid()){
                $category->exchangeArray($form->getData());
                $tabel = $this->_getCategoryTable();
                $tabel->save($category->toArray());
            }else{
                return array('form' => $form , 'query' => $query);
            }
            return $this->redirect()->toUrl('/category/index?' . $query);
        }
        
        $form->get('SiteID')->setValue($siteId);
        
        if(isset($params['id'])){
            $tabel = $this->_getCategoryTable();
            $category->exchangeArray($tabel->getInfoById($params['id']));
            
            $form->bind($category);
            unset($params['id']);
        }
        
        return array('form' => $form , 'query' => $query);
    }
    
    function activeAction(){
        return $this->_setActive(CategoryTable::ACTIVE_YES);
    }
    
    function inActiveAction(){
        return $this->_setActive(CategoryTable::ACTIVE_NO);
    }
    
    private function _setActive($state){
        $request = $this->getRequest();
        $params = $request->getQuery();
        
        $id = $this->params()->fromQuery('id');
        unset($params['id']);
        if($request->isPost()){
            $id = $request->getPost('CategoryID');
        }
        if(!$id){
            throw new \Exception('incomplete CategoryID');
        }
        
        $categoryTable = $this->_getCategoryTable();
        $categoryTable->changeActive($id , $state);
        
        return $this->redirect()->toUrl('/category/index?' . http_build_query($params));
    }
    
    private function _getCategoryList(array $params){
        $categoryTable = $this->_getCategoryTable();
        $categoryTable->formatWhere($params);
        $categories = $categoryTable->getList();
        $re = array();
        foreach($categories as $v){
            $v['MerchantCount'] = $this->_getMerchantCount($v['CategoryID']);
            $v['CouponCount'] = $this->_getConponCount($v['CategoryID']);
            $re[$v['CategoryID']] = $v;
        }
        return $re;
    }
    
    private function _getConponCount($cid){
        $couponCateTable = $this->_getTable('CouponCategoryTable');
        return $couponCateTable->getCountByCid($cid);
    }
    private function _getMerchantCount($cid){
        $merchantCategoryTable = $this->_getMerchantCategoryTable();
        return $merchantCategoryTable->getCountByCid($cid);
    }
    
    private function _getMerchantCategoryTable(){
        return $this->getServiceLocator()->get('MerchantCategoryTable');
    }
    private function _getCategoryTable(){
        return $this->getServiceLocator()->get('CategoryTable');
    }
}