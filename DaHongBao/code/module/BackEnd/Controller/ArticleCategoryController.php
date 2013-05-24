<?php
/**
* ArticleCategoryController.php
*-------------------------
*
* 文章分类管理
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
* @version CVS: $Id: ArticleCategoryController.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;
use Custom\Mvc\Controller\AbstractActionController;
use BackEnd\Form\ArticleCategoryForm;
use CommModel\Article\ArticleCategory;
class ArticleCategoryController extends AbstractActionController
{
    function indexAction()
    {
        $siteId = $this->params()->fromQuery('SiteID' , 1);
        $re['articleCount'] = $this->_getArticleCount($siteId);
        $re['sites'] = $this->sites;
        $re['SiteID'] = $siteId;
        $re['list'] = $this->_getCateList($siteId);
        
        return $re;
    }
    function saveAction()
    {
        $request = $this->getRequest();
        $table = $this->_getArticleCategoryTable();
        $articleCategory = new ArticleCategory();
        $form = new ArticleCategoryForm();
        
        if($request->isPost()){
            $form->setParentOptions($table->getAll());
            $form->setInputFilter($articleCategory->getInputFilter());
            $articleCategory->exchangeArray($this->params()->fromPost());
            $table->save($articleCategory);
            return $this->redirect()->toUrl('/ArticleCategory/index?SiteID=' . $articleCategory->SiteID);
        }
        
        if(! $siteId = $this->params()->fromQuery('siteid')){
            throw new \Exception('Error:not siteId');
        }
        
        $form->setSiteId($siteId);
        
        if($id = $this->params()->fromQuery('id')){
            $id = (int)$id;
            
            $cate = $table->getOneForId($id);
            $form->setData($cate->toArray());
            $form->setParentOptions($table->getAll($siteId) , $cate->ParentID);
        }else{
            $form->setParentOptions($table->getAll($siteId));
        }
        return array(
            'form' => $form
        );
    }
    function deleteAction()
    {
        if($id = $this->params()->fromQuery('id')){
            $id = (int)$id;
            $table = $this->_getArticleCategoryTable();
            $table->remove($id);
        }
        return $this->redirect()->toRoute('backend' , array(
            'controller' => 'ArticleCategory',
            'action' => 'index'
        ));
    }
    
    /**
     * 获取列表
     * @param int $siteId
     */
    private function _getCateList($siteId){
        $cateTable = $this->_getArticleCategoryTable();
        return $cateTable->getAll($siteId);
    }
    
    private function _getArticleCount($siteId){
        $articleTable = $this->_getTable('ArticleTable');
        return $articleTable->getCountGroupByCate($siteId);
    }
    
    private function _getArticleCategoryTable()
    {
        return $this->getServiceLocator()->get('ArticleCategoryTable');
    }
}